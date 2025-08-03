<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Variant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// session_start();

class CheckoutController extends Controller
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

    public function login_checkout()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();
        return view('pages.login.login', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
        ]);
    }
    public function checkout()
    {

        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $customer_id = Session::get('customer_id');
        $customer    = Customer::find($customer_id);
        return view('pages.checkout.checkout', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
            'customer'    => $customer,
        ]);
    }
    public function save_checkout_customer(Request $request)
    {

        Session::put('shipping_name', $request->shipping_name);
        Session::put('shipping_phone', $request->shipping_phone);
        Session::put('shipping_email', $request->shipping_email);
        Session::put('shipping_address', $request->shipping_address);
        Session::put('shipping_city', $request->shipping_city);
        Session::put('shipping_district', $request->shipping_district);
        Session::put('shipping_ward', $request->shipping_ward);
        Session::put('shipping_note', $request->shipping_note);
        Session::put('shipping_method', $request->shipping_method);
        Session::put('total', $request->total);
        return Redirect::to('/home/payment');
    }

    public function save_note_coupon(Request $request)
    {
        $couponCode   = trim($request->coupon_code);
        $shippingNote = trim($request->shipping_note);

        // Lưu ghi chú nếu có
        if (! empty($shippingNote)) {
            Session::put('cart_note', $shippingNote);
        }

        // Nếu không nhập mã thì chỉ lưu note và tiếp tục
        if (empty($couponCode)) {
            return redirect('/home/checkouts');
        }

        // Kiểm tra mã giảm giá trong DB
        $coupon = DB::table('tbl_discount_coupon')
            ->whereRaw('UPPER(coupon_code) = ?', [$couponCode])
            ->first();
        if (! $coupon) {
            return redirect('/home/pages/cart/cart')->with('error_coupon', 'Mã khuyến mãi không hợp lệ. Vui lòng thử lại.');
        }

        if ($coupon->used_count >= $coupon->usage_limit) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá đã hết lượt sử dụng');
        }

        // Kiểm tra nếu đã áp dụng mã này rồi
        if (Session::has('cart_coupon') && Session::get('cart_coupon') === $couponCode) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá này đã được áp dụng.');
        }
        if ($coupon->end_date < now() && $coupon->end_date) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá này quá hạn sử dụng.');
        }
        // Lưu mã vào session nếu hợp lệ
        Session::put('cart_coupon', [
            'id'            => $coupon->coupon_id,
            'code'          => $coupon->coupon_code,
            'discount_type' => $coupon->discount_type,
            'discount'      => $coupon->discount_value, // số tiền giảm
        ]);

        return redirect('/home/checkouts')->with('message', 'Mã giảm giá đã được áp dụng');
    }

    // Route: /home/checkouts/apply-coupon
    public function apply_coupon(Request $request)
    {
        $code = trim($request->coupon_code);

        // Tìm mã giảm giá
        $coupon = DB::table('tbl_discount_coupon')
            ->whereRaw('UPPER(coupon_code) = ?', [$code])
            ->first();
        // Nếu không tìm thấy
        if (! $coupon) {
            return redirect('/home/checkouts')->with('error_coupon', 'Mã khuyến mãi không hợp lệ. Vui lòng thử lại.');
        }

        // Nếu mã đã hết lượt sử dụng
        if ($coupon->used_count >= $coupon->usage_limit) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá đã hết lượt sử dụng.');
        }

        // Nếu mã đã được áp dụng trước đó
        if (Session::has('coupon') && Session::get('coupon.code') === $coupon->coupon_code) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá này đã được áp dụng.');
        }

        if ($coupon->end_date < now() && $coupon->end_date) {
            return redirect()->back()->with('error_coupon', 'Mã giảm giá này quá hạn sử dụng.');
        }

        // Lưu mã vào session
        Session::put('coupon', [
            'id'            => $coupon->coupon_id,
            'code'          => $coupon->coupon_code,
            'discount_type' => $coupon->discount_type,
            'discount'      => $coupon->discount_value, // số tiền giảm
        ]);
        return redirect()->back()->with('success_coupon', 'Áp dụng mã giảm giá thành công!');
    }

    public function payment()
    {
        $coupon_code     = Session::get('cart_coupon');
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        return view('pages.checkout.payment', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
        ]);
    }

    public function place_order(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $request->validate([
            'payment' => 'required|string',
        ]);
        $coupon_code = Session::get('cart_coupon');
        $coupon_id   = Coupon::where('coupon_code', $coupon_code)->first();
        $code        = Str::random(11);
        DB::beginTransaction();
        try {
            //get payment method
            $data                     = [];
            $data['payment_method']   = $request->payment;
            $data['payment_status']   = 'pending';
            $data['transaction_code'] = $code;

            $payment_id = DB::table('tbl_payment')->insertGetId($data);
            Session::put('payment_id', $payment_id);
            Session::put('payment_method', $request->payment);

            //insert order

            $code                    = Str::random(11);
            $coupon                  = Session::get('coupon');
            $shipping_method         = Session::get('shipping_method');
            // $city_code               = Session::get('shipping_city');
            // $district_code           = Session::get('shipping_district');
            // $subtotal                = $request->subtotal;
            // $free_shipping_threshold = 500000;

            // $inner_districts = ['001', '004', '007', '009'];
            // $is_inner        = ($city_code == '79' && in_array($district_code, $inner_districts));

            // if ($subtotal >= $free_shipping_threshold) {
            //     if ($shipping_method === 'internal') {
            //         $shipping_fee    = 0;
            //         $shipping_method = 'free';
            //     } elseif ($shipping_method === 'fast') {
            //         $shipping_fee = $is_inner ? 15000 : 25000; // chỉ phí phụ
            //     } else {
            //         $shipping_fee = 0;
            //     }
            // } else {
            //     if ($shipping_method === 'internal') {
            //         $shipping_fee = $is_inner ? 15000 : 25000;
            //     } elseif ($shipping_method === 'fast') {
            //         $shipping_fee = $is_inner ? 65000 : 75000;
            //     } else {
            //         $shipping_fee = 0;
            //     }
            // }

            $order_data                      = [];
            $order_data['customer_id']       = Session::get('customer_id');
            $order_data['payment_id']        = $payment_id;
            $order_data['admin_id']          = null;
            $order_data['delivery_id']       = null;
            $order_data['coupon_id']         = is_array($coupon) && isset($coupon['id']) ? $coupon['id'] : null;
            $order_data['order_total']       = $request->total;
            $order_data['order_status']      = 'Đang chờ xử lý';
            $order_data['shipping_name']     = Session::get('shipping_name');
            $order_data['shipping_phone']    = Session::get('shipping_phone');
            $order_data['shipping_email']    = Session::get('shipping_email');
            $order_data['shipping_address']  = Session::get('shipping_address');
            $order_data['shipping_city']     = Session::get('shipping_city');
            $order_data['shipping_district'] = Session::get('shipping_district');
            $order_data['shipping_ward']     = Session::get('shipping_ward');
            $order_data['shipping_note']     = Session::get('shipping_note');
            // $order_data['shipping_method'] = Session::get('shipping_method');
            $order_data['shipping_method'] = $shipping_method;
            $order_data['shipping_fee']    = $request->shipping_fee;
            $order_data['order_code']      = $code;
            $order_data['created_at']      = now();

            $order_id = DB::table('tbl_order')->insertGetId($order_data);

            Session::put('order_id', $order_id);
            //insert usage coupon

            if ($coupon && is_array($coupon) && isset($coupon['id'])) {
                $usage_data                = [];
                $usage_data['customer_id'] = Session::get('customer_id');
                $usage_data['coupon_id']   = is_array($coupon) && isset($coupon['id']) ? $coupon['id'] : null;

                $usage_id = DB::table('tbl_coupon_usage')->insertGetId($usage_data);
            }

            //insert order details
            $content = Cart::content();
            foreach ($content as $v_content) {
                $order_details_data                           = [];
                $order_details_data['order_id']               = $order_id;
                $order_details_data['product_id']             = $v_content->id;
                $order_details_data['product_name']           = $v_content->name;
                $order_details_data['product_price']          = $v_content->price;
                $order_details_data['product_sales_quantity'] = $v_content->qty;
                $order_details_data['product_color']          = $v_content->options->color;
                $order_details_data['product_size']           = $v_content->options->size;
                $order_details_data['image']                  = asset('uploads/products/' . $v_content->options->image);
                DB::table('tbl_order_details')->insert($order_details_data);
            }
            //Xoa gio hang sau khi xac nhan thanh toan thanh cong
            Cart::destroy();

            DB::commit();

            if ($data['payment_method'] == 'cod') {
                return view('pages.checkout.cod', [
                    'category'    => $cate_product,
                    'brand'       => $brand_product,
                    'subcategory' => $subcate_product,
                ]);
            } elseif ($data['payment_method'] == 'vnpay') {

                $customer_id = Customer::where('customer_id', Session::get('customer_id'))->first();
                $order       = Order::where('order_id', Session::get('order_id'))->first();
                return Redirect('/home/place-order/vnpay')
                    ->with('category', $cate_product)
                    ->with('brand', $brand_product)
                    ->with('subcategory', $subcate_product);
            }
        } catch (\Exception $e) {
            DB::rollBack(); // thất bại, quay lui
            Session::put('error', 'Đặt hàng thất bại. Vui lòng thử lại!' . $e->getMessage());
            return Redirect::back();
        }
    }

    public function delete_coupon(Request $request)
    {
        Session::forget('cart_coupon');
        return response()->json(['success' => 'Xóa mã giảm giá thành công']);
    }
    public function manage_order()
    {
        $this->AuthLogin();
        $shippers  = DB::table('tbl_admin')->get();
        $all_order = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->leftJoin('tbl_admin as a', 'tbl_order.admin_id', '=', 'a.admin_id')
            ->leftJoin('tbl_admin as b', 'tbl_order.delivery_id', '=', 'b.admin_id')
            ->select(
                'tbl_order.*',
                'tbl_payment.payment_status',
                'tbl_customer.customer_name',
                'a.admin_name as admin_name',
                'b.admin_name as shipper_name'
            )
            ->orderBy('tbl_order.order_id', 'desc')
            ->get();

        foreach ($all_order as $order) {
            if ($order->order_status == 'Đã xác nhận') {
                $this->updateStockAndSales($order);
            }
        }
        $manager_order = view('admin.order.manage_order')->with('all_order', $all_order);
        return view('admin_layout', [
            'admin.order.manage_order' => $manager_order,
            'admin.order.shippers'     => $shippers,
        ]);
    }

    protected function updateStockAndSales($order)
    {
        $orderDetails = OrderDetails::where('order_id', $order->order_id)->get();

        foreach ($orderDetails as $details) {
            $variant_id = DB::table('tbl_product_variants')
                ->where('tbl_product_variants.variants_id', $details->product_id)
                ->value('tbl_product_variants.variants_id');

            if ($variant_id) {
                $variant = Variant::find($variant_id);
                if ($variant) {
                    $variant->stock = $variant->stock - $details->product_sales_quantity;
                    $variant->save();
                }
            }

            $product_id = Product::join('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
                ->where('tbl_product_variants.variants_id', $details->product_id)
                ->value('tbl_product.product_id');
            if ($product_id) {
                $product = Product::find($product_id);
                if ($product) {
                    $product->total_sold += $details->product_sales_quantity;
                    $product->save();
                }
            }
        }
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

    public function view_order($order_id)
    {
        $order_details = DB::table('tbl_order_details')
            ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->where('tbl_order_details.order_id', $order_id)
            ->select('tbl_order_details.*', 'tbl_order.coupon_id')
            ->get();

        $order_info = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('tbl_order.order_id', $order_id)
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
        return view('admin.order.view_order', compact('order_details', 'order_info', 'wardName', 'districtName', 'cityName'));
    }
    public function edit_order($order_id)
    {
        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->first();
        $shippers = DB::table('tbl_admin')
            ->where('admin_role', 'shipper')
            ->get();

        $statusOptions = [
            'Đang chờ xử lý' => 'Đang chờ xử lý',
            'Đã xác nhận'    => 'Đã xác nhận',
            'Đang giao hàng' => 'Đang giao hàng',
            'Đã giao hàng'   => 'Đã giao hàng',
            'Đã huỷ'         => 'Đã huỷ',
        ];

        return view('admin.order.update_order', compact('order', 'shippers', 'statusOptions'));
    }
    public function update_order($order_id, Request $request)
    {
        $request->validate([
            'order_status' => ['required', Rule::in(['Đang chờ xử lý', 'Đã xác nhận', 'Đang giao hàng', 'Đã giao hàng', 'Đã huỷ'])],
            'delivery_id'  => 'nullable|exists:tbl_admin,admin_id',
        ]);

        DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->update([
                'admin_id'     => Session::get('admin_id'),
                'order_status' => $request->order_status,
                'delivery_id'  => $request->delivery_id,
            ]);

        return redirect('/admin/order/manage-order')->with('success', 'Cập nhật đơn hàng thành công');
    }

    public function showPaymentPage()
    {
        $order = DB::table('tbl_order')->latest()->first(); // Lấy order mẫu, bạn thay bằng logic của bạn

        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        return view('pages.checkout.vnpay.vnpay', [
            'order'       => $order,
            'order_code'  => $order->order_code,
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
        ]);
    }

    public function vnpay_payment(Request $request)
    {
        $data           = $request->all();
        $vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl  = route('vnpay.return');
        $vnp_TmnCode    = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');

        $vnp_TxnRef    = $data['order_code'];
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount    = $data['amount'] * 100;
        $vnp_Locale    = 'vn';
        $vnp_IpAddr    = $_SERVER['REMOTE_ADDR'];

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => $vnp_OrderType,
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query    = "";
        $hashdata = "";
        $i        = 0;
        foreach ($inputData as $key => $value) {
            $hashdata .= ($i ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
            $i++;
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . 'vnp_SecureHash=' . $vnpSecureHash;

        $returnData = [
            'code'    => '00',
            'message' => 'success',
            'data'    => $vnp_Url,
        ];

        return redirect()->away($vnp_Url);
    }
    // public function vnpayReturn(Request $request)
    // {
    //     $vnp_HashSecret = env('VNP_HASHSECRET');
    //     $inputData = $request->all();

    //     $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;
    //     unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

    //     ksort($inputData);
    //     $hashData = urldecode(http_build_query($inputData));
    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    //     // Load danh mục
    //     $cate_product = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
    //     $brand_product = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
    //     $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

    //     if ($secureHash === $vnp_SecureHash) {
    //         $orderCode = $inputData['vnp_TxnRef'] ?? null;
    //         $order = Order::where('order_code', $orderCode)->first();

    //         if (!$order) {
    //             return redirect('/')->with('error', 'Không tìm thấy đơn hàng #' . $orderCode);
    //         }

    //         if ($inputData['vnp_ResponseCode'] === '00') {
    //             DB::table('tbl_payment')
    //                 ->where('payment_id', $order->payment_id)
    //                 ->update(['payment_status' => 'success']);

    //             return view('pages.checkout.vnpay.success', compact('cate_product', 'brand_product', 'subcate_product'))
    //                 ->with('success', 'Thanh toán thành công cho đơn hàng #' . $orderCode);
    //         } else {
    //             DB::table('tbl_payment')
    //                 ->where('payment_id', $order->payment_id)
    //                 ->update(['payment_status' => 'fail']);

    //             return view('pages.checkout.vnpay.fail', compact('cate_product', 'brand_product', 'subcate_product'))
    //                 ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $inputData['vnp_ResponseCode']);
    //         }
    //     } else {
    //         return redirect('/home/place-order/vnpay')
    //             ->with('error', 'Dữ liệu không hợp lệ!')
    //             ->with(compact('cate_product', 'brand_product', 'subcate_product'));
    //     }
    // }
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $inputData      = $request->all();

        // Lấy secure hash do VNPAY gửi
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;

        // Loại bỏ những tham số không tham gia vào hash
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        // Sắp xếp mảng theo key (tăng dần)
        ksort($inputData);

        // Tạo chuỗi dữ liệu đúng chuẩn VNPAY mẫu (có urlencode key & value)
        $hashData = '';
        $i        = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        // Tạo secure hash
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Load danh mục để hiển thị view
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        // So sánh hash để xác thực dữ liệu
        if ($secureHash === $vnp_SecureHash) {
            $orderCode = $inputData['vnp_TxnRef'] ?? null;
            $order     = Order::where('order_code', $orderCode)->first();

            if (! $order) {
                return redirect('/home/place-order/vnpay')
                    ->with('error', 'Không tìm thấy đơn hàng #' . $orderCode)
                    ->with('category', $cate_product)
                    ->with('brand', $brand_product)
                    ->with('subcategory', $subcate_product);
            }

            if ($inputData['vnp_ResponseCode'] === '00') {
                DB::table('tbl_payment')
                    ->where('payment_id', $order->payment_id)
                    ->update(['payment_status' => 'success']);

                return view('pages.checkout.vnpay.success')
                    ->with('success', 'Thanh toán thành công cho đơn hàng #' . $orderCode)
                    ->with('category', $cate_product)
                    ->with('brand', $brand_product)
                    ->with('subcategory', $subcate_product);
            } else {
                DB::table('tbl_payment')
                    ->where('payment_id', $order->payment_id)
                    ->update(['payment_status' => 'fail']);

                return view('pages.checkout.vnpay.fail')
                    ->with('error', 'Giao dịch không thành công. Mã lỗi: ' . $inputData['vnp_ResponseCode'])
                    ->with('category', $cate_product)
                    ->with('brand', $brand_product)
                    ->with('subcategory', $subcate_product);
            }
        } else {
            return redirect('/home/place-order/vnpay')
                ->with('error', 'Dữ liệu không hợp lệ!')
                ->with('category', $cate_product)
                ->with('brand', $brand_product)
                ->with('subcategory', $subcate_product);
        }
    }
}
