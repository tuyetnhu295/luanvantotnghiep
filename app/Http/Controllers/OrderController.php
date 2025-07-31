<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function print_order($checkout_code)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        $pdf->set_option('defaultFont', 'DejaVu Sans');

        return $pdf->stream("hoa-don-$checkout_code.pdf");
    }

    public function findNameByCode($list, $code)
    {
        foreach ($list as $item) {
            if ($item['code'] == $code) {
                return $item['name'];
            }
        }
        return 'Không rõ'; //
    }

    public function print_order_convert($checkout_code)
    {
        $order_details = OrderDetails::join('tbl_order', 'tbl_order.order_id', '=', 'tbl_order_details.order_id')
            ->join('tbl_product', 'tbl_order_details.product_id', '=', 'tbl_product.product_id')
            ->where('tbl_order.order_code', $checkout_code)
            ->get();

        $order = Order::leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('order_code', $checkout_code)->first();

        if (! $order) {
            return '<p>Không tìm thấy đơn hàng</p>';
        }

        $customer = Customer::where('customer_id', $order->customer_id)->first();

        $cities    = json_decode(file_get_contents(public_path('data/tinh_tp.json')), true);
        $districts = json_decode(file_get_contents(public_path('data/quan_huyen.json')), true);
        $wards     = json_decode(file_get_contents(public_path('data/xa_phuong.json')), true);

        $wardName     = $this->findNameByCode($wards, $order?->shipping_ward ?? null);
        $districtName = $this->findNameByCode($districts, $order?->shipping_district ?? null);
        $cityName     = $this->findNameByCode($cities, $order?->shipping_city ?? null);

        $output = '<style>
        body { font-family: DejaVu Sans, sans-serif; }
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 8px; }
        th { background-color: #f2f2f2; }
        </style>';

        $output .= '<h2 style="text-align: center;">HÓA ĐƠN ĐẶT HÀNG</h2>';
        $output .= '<p><strong>Mã đơn hàng:</strong> ' . $order->order_code . '</p>';
        $output .= '<p><strong>Khách hàng:</strong> ' . $customer->customer_name . '</p>';
        $output .= '<p><strong>Ngày đặt:</strong> ' . \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') . '</p><br>';
        $output .= '<h4>Thông tin giao hàng</h4>';
        $output .= '<p>Địa chỉ:' . $order->shipping_address . ',' . $wardName . ',' . $districtName . ',' . $cityName . '</p>';
        $output .= '<p>Ghi chú:' . $order->shipping_note . '</p>';

        $output .= '<table width="100%">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>';

        $order_subtotal = 0;
        $total          = 0;
        $coupon_total   = 0;
        foreach ($order_details as $detail) {
            $subtotal = $detail->product_price * $detail->product_sales_quantity;
            $order_subtotal += $subtotal;

            if ($order->discount_type == 'percentage') {
                $coupon_total = ($order_subtotal * $order->discount_value) / 100;
            } elseif ($order->discount_type == 'fixed') {
                $coupon_total = $order->discount_value;
            }

            $shipping_fee = $order->shipping_fee;

            $total = $order_subtotal - $coupon_total + $shipping_fee;
            $output .= '<tr>
            <td>' . $detail->product_name . '</td>
            <td>' . $detail->product_sales_quantity . '</td>
            <td>' . number_format($detail->product_price, 0, ',', '.') . '₫</td>
            <td>' . number_format($subtotal, 0, ',', '.') . '₫</td></tr>';
        }

        $output .= '<tr>
        <td colspan="3" style="text-align: right;"><strong>Tạm tính:</strong></td>
        <td>' . number_format($order_subtotal * 1.1, 0, ',', '.') . '₫</td></tr>';

        $output .= '<tr>
        <td colspan="3" style="text-align: right;"><strong>Giảm giá:</strong></td>
        <td>' . number_format($coupon_total, 0, ',', '.') . '₫</td></tr>';

        $output .= '<tr>
        <td colspan="3" style="text-align: right;"><strong>Phí vận chuyển:</strong></td>
        <td>' . number_format($shipping_fee, 0, ',', '.') . '₫</td></tr>';

        $output .= '<tr>
        <td colspan="3" style="text-align: right;"><strong>Tổng cộng:</strong></td>
        <td>' . number_format($total * 1.1, 0, ',', '.') . '₫</td></tr>';

        $output .= '</tbody></table>';
        $output .= '<br><br><br>';
        $output .= '
        <div style="text-align: right; margin-top: 50px;">
            <p style="margin: 0;"><strong>NGƯỜI NHẬN</strong></p>
            <p style="margin: 0;">(Ký tên)</p>
        </div>';

        return $output;
    }

    public function manage_order_returns()
    {
        $returns = ReturnModel::join('tbl_order', 'tbl_order.order_id', '=', 'tbl_returns.order_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_order.customer_id')
            ->select(
                'tbl_returns.*',
                'tbl_customer.customer_name',
                'tbl_order.order_code'
            )
            ->get();
        return view('admin.order.manage_order_returns', compact('returns'));
    }

    public function view_order_returns($code)
    {
        $this->AuthLogin();

        $order_details = DB::table('tbl_order_details')
            ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->join('tbl_product_variants', 'tbl_order_details.product_id', '=', 'tbl_product_variants.variants_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->join('tbl_returns', 'tbl_returns.order_id', '=', 'tbl_order.order_id')
            ->where('tbl_returns.return_code', $code)
            ->select('tbl_order_details.*', 'tbl_order.coupon_id', 'tbl_product.slug_product', 'tbl_product.product_image')
            ->get();

        $order_info = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->join('tbl_returns', 'tbl_returns.order_id', '=', 'tbl_order.order_id')
            ->leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('tbl_returns.return_code', $code)
            ->select(
                'tbl_order.*',
                'tbl_customer.customer_name',
                'tbl_customer.customer_phone',
                'tbl_customer.customer_email',
                'tbl_discount_coupon.*',
                'tbl_payment.payment_method',
                'tbl_payment.transaction_code'
            )
            ->first();

        $cities    = json_decode(file_get_contents(public_path('data/tinh_tp.json')), true);
        $districts = json_decode(file_get_contents(public_path('data/quan_huyen.json')), true);
        $wards     = json_decode(file_get_contents(public_path('data/xa_phuong.json')), true);

        $wardName     = $this->findNameByCode($wards, $order_info?->shipping_ward ?? null);
        $districtName = $this->findNameByCode($districts, $order_info?->shipping_district ?? null);
        $cityName     = $this->findNameByCode($cities, $order_info?->shipping_city ?? null);

        $returns = ReturnModel::join('tbl_order', 'tbl_order.order_id', '=', 'tbl_returns.order_id')
            ->where('tbl_returns.return_code', $code)
            ->first();

        $return_item = ReturnItem::join('tbl_product_variants', 'tbl_product_variants.variants_id', 'tbl_return_items.product_id')
            ->join('tbl_returns', 'tbl_returns.return_id', '=', 'tbl_return_items.return_id')
            ->join('tbl_order', 'tbl_order.order_id', '=', 'tbl_returns.order_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->join('tbl_size_product', 'tbl_size_product.size_id', '=', 'tbl_product_variants.size_id')
            ->where('tbl_returns.return_code', $code)
            ->select(
                'tbl_return_items.*',
                'tbl_product.product_name',
                'tbl_product.product_price',
                'tbl_product.product_image',
                'tbl_size_product.size_name',
                'tbl_color_product.color_name',
            )
            ->get();
        return view('admin.order.view_order_returns', compact(
            'order_details',
            'order_info',
            'returns',
            'return_item',
            'wardName',
            'districtName',
            'cityName'
        ));
    }

    public function edit_order_returns($code)
    {
        $returns       = ReturnModel::where('return_code', $code)->first();
        $statusOptions = [
            'pending'    => 'Đang chờ xử lý',
            'approved'   => 'Đã chấp nhận',
            'rejected'   => 'Bị từ chối',
            'processing' => 'Đang xử lý',
        ];
        return view('admin.order.update_order_returns', compact('returns', 'statusOptions'));
    }

    public function update_order_returns(Request $request, $code)
    {
        DB::table('tbl_returns')
            ->where('return_code', $code)
            ->update([

                'status' => $request->status,

            ]);

        return redirect('/admin/order/manage-order-returns')->with('message', 'Cập nhật đơn hàng thành công');
    }

    public function order_shipper()
    {
        $shipper = Session::get('admin_id');
        $all_order = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->where('delivery_id', $shipper)
            ->whereNotIn('order_status', ['Đang chờ xử lý', 'Đã hủy'])
            ->select(
                'tbl_order.*',
                'tbl_payment.payment_status',
                'tbl_customer.customer_name'
            )
            ->orderBy('tbl_order.order_id', 'desc')
            ->get();

        return view('admin.shipping.list')->with('all_order', $all_order);
    }

    public function edit_order($order_id)
    {
        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->first();

        return view('admin.shipping.edit', compact('order'));
    }
    public function update_order($order_id, Request $request)
    {

        DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->update([
                'order_status' => $request->order_status,
            ]);
        DB::table('tbl_payment')
            ->join('tbl_order', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->where('order_id', $order_id)
            ->update([
                'payment_status' => $request->payment_status,
            ]);

        return redirect('/admin/delivery/orders')->with('success', 'Cập nhật đơn hàng thành công');
    }
}
