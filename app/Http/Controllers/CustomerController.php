<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FavoriteProduct;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\UsageModel;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

// session_start();

class CustomerController extends Controller
{
    //User

    public function register()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        return view('pages.login.register', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
        ]);
    }

    public function add_customer(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|max:20',
            'email'    => 'required|email|unique:tbl_customer,customer_email',
            'password' => 'required|min:6|confirmed',
        ]);

        $data                      = [];
        $data['customer_name']     = $request->name;
        $data['customer_email']    = $request->email;
        $data['customer_phone']    = $request->phone;
        $data['customer_password'] = $request->password;
        $add_customer              = DB::table('tbl_customer')->insertGetId($data);

        Session::put('customer_id', $add_customer);
        Session::put('customer_name', $request->name);
        return redirect('/home/account/login')->with('success', 'Đăng ký tài khoản thành công!');
    }

    public function login_customer(Request $request)
    {
        $email    = $request->email;
        $password = $request->password;
        $login    = DB::table('tbl_customer')->where('customer_email', $email)->where('customer_password', $password)->first();
        if ($login) {
            Session::put('customer_phone', $login->customer_phone);
            Session::put('customer_id', $login->customer_id);
            Session::put('customer_email', $login->customer_email);
            Session::put('customer_name', $login->customer_name);
            Session::put('messages', 'Đăng nhập tài khoản thành công');
            return Redirect::to('/home')->with('success', 'Đăng nhập tài khoản thành công!');
        } else {
            return Redirect::to('/home/account/login')->with('error', 'Đăng nhập tài khoản không thành công!');
        }
    }
    public function logout()
    {
        Session::flush();
        return Redirect::to('/home');
    }

    public function info()
    {

        $customer_id     = Session::get('customer_id');
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();
        $customer        = Customer::where('customer_id', $customer_id)->first();
        return view('pages.customer.profile', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
            'customer'    => $customer,
        ]);
    }

    public function save_info(Request $request)
    {
        $email    = Session::get('customer_email');
        $customer = Customer::where('customer_email', $email)->first();

        $customer->customer_name     = $request->input('customer_name');
        $customer->customer_phone    = $request->input('customer_phone');
        $customer->customer_birthday = $request->input('customer_birthday');
        $customer->customer_sex      = $request->input('customer_sex');
        $customer->address           = $request->input('address');
        $customer->city              = $request->input('city');
        $customer->district          = $request->input('district');
        $customer->ward              = $request->input('ward');
        $customer->save();

        Session::put('message', 'Cập nhật thông tin thành công !');
        return Redirect::to('/home/account/info/profile');
    }

    public function change_password()
    {
        $customer_id     = Session::get('customer_id');
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();
        $customer        = Customer::where('customer_id', $customer_id)->first();
        return view('pages.customer.change_password', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
            'customer'    => $customer,
        ]);
    }

    public function save_change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $customer_id      = Session::get('customer_id');
        $customer         = Customer::where('customer_id', $customer_id)->first();
        $current_password = $request->current_password;
        $new_password     = $request->new_password;
        if ($current_password !== $customer->customer_password) {
            Session::put('message', 'Mật khẩu hiện tại không đúng');
            return Redirect::back();
        }
        if ($new_password === $customer->customer_password) {
            Session::put('message', 'Mật khẩu mới trùng với mật khẩu cũ');
            return Redirect::back();
        }
        $customer->customer_password = $new_password;
        $customer->save();
        Session::put('success', 'Thay đổi mật khẩu thành công');
        return Redirect::to('/home/account/info/change-password');
    }

    public function forget_password()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();
        return view('pages.login.forget_password', [
            'category'    => $cate_product,
            'brand'       => $brand_product,
            'subcategory' => $subcate_product,
        ]);
    }

    public function password_email(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        $user = Customer::where('customer_email', $email)->first();

        if (! $user) {
            return response()->json(['message' => 'Email không tồn tại.'], 404);
        }

        $token = Str::random(8);

        DB::table('tbl_customer')
            ->where('customer_email', $email)
            ->update([
                'customer_password' => $token,
                'updated_at'        => now(),
            ]);

        $resetLink      = url("/home/account/login");
        $messageContent = <<<EOT
            Bạn đã yêu cầu đặt lại mật khẩu.

            Mật khẩu tạm thời của bạn là: {$token}

            Vui lòng đăng nhập tại: {$resetLink}

            Sau khi đăng nhập, bạn nên đổi mật khẩu ngay trong phần hồ sơ cá nhân.
            EOT;

        Mail::raw($messageContent, function ($message) use ($email) {
            $message->to($email)
                ->subject('Mật khẩu tạm thời - Đăng nhập');
        });
        return redirect('/home/account/forget-password')->with('status', 'Đã gửi mật khẩu tạm thời qua email.');
    }

    public function my_order()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $customer_id = Session::get('customer_id');
        $orders      = Order::where('customer_id', $customer_id)
            ->with('orderDetails')
            ->orderBy('tbl_order.created_at', 'desc')
            ->paginate(2);

        $pendingOrders = Order::where('order_status', 'Đang chờ xử lý')
            ->with('orderDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $confirmedOrders = Order::where('order_status', 'Đã xác nhận')
            ->where('customer_id', $customer_id)
            ->with('orderDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $shippingOrders = Order::where('order_status', 'Đang giao hàng')
            ->where('customer_id', $customer_id)
            ->with('orderDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(2);
        $deliveredOrders = Order::where('order_status', 'Đã giao hàng')
            ->where('customer_id', $customer_id)
            ->with('orderDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $cancelledOrders = Order::where('order_status', 'Đã huỷ')
            ->where('customer_id', $customer_id)
            ->with('orderDetails')
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        $returnOrders = ReturnModel::whereHas('order', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
        })
            ->with(['order.orderDetails', 'return_item'])
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        return view('pages.customer.my_order')
            ->with('orders', $orders)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('pendingOrders', $pendingOrders)
            ->with('confirmedOrders', $confirmedOrders)
            ->with('shippingOrders', $shippingOrders)
            ->with('deliveredOrders', $deliveredOrders)
            ->with('cancelledOrders', $cancelledOrders)
            ->with('returnOrders', $returnOrders);
    }

    public function cancel_order($order)
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $order = Order::where('order_code', $order)
            ->with('orderDetails')->first();
        return view('pages.customer.cancel_order')
            ->with('order', $order)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }

    public function cancel_submit($order)
    {
        $order = Order::where('order_code', $order)->update(['order_status' => 'Đã hủy']);

        return redirect('/home/account/info/my-order')->with('success', 'Đơn hàng đã được hủy.');
    }

    public function showReturnForm($code)
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $order = Order::where('order_code', $code)->with('orderDetails')->firstOrFail();
        return view('pages.customer.return_order')
            ->with('order', $order)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }

    public function showReturnItems($code)
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $order = Order::where('order_code', $code)->with('orderDetails')->firstOrFail();

        $return = ReturnModel::join('tbl_order', 'tbl_order.order_id', '=', 'tbl_returns.order_id')->where('tbl_order.order_code', $code)->first();

        $return_code = $return->return_id;
        $return_item = ReturnItem::join('tbl_product_variants', 'tbl_product_variants.variants_id', '=', 'tbl_return_items.product_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->where('return_id', $return_code)
            ->select(
                'tbl_return_items.*',
                'tbl_product.product_image',
                'tbl_product.product_name'

            )
            ->get();
        return view('pages.customer.return_items')
            ->with('order', $order)
            ->with('return', $return)
            ->with('return_item', $return_item)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }

    public function confirm($code)
    {
        DB::table('tbl_order')->where('order_code', $code)->update(['order_status' => 'Đã giao hàng']);
        return redirect('/home/account/info/my-order')->with('success', 'Đơn hàng đã giao thành công.');
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

    public function showOrderDetails($code)
    {
        $category    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand       = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcategory = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $order_details = DB::table('tbl_order_details')
            ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->join('tbl_product_variants', 'tbl_order_details.product_id', '=', 'tbl_product_variants.variants_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->where('tbl_order.order_code', $code)
            ->select('tbl_order_details.*', 'tbl_order.coupon_id', 'tbl_product.slug_product', 'tbl_product.product_image')
            ->get();

        $order_info = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('tbl_order.order_code', $code)
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
        return view('pages.customer.order_details', compact(
            'order_details',
            'order_info',
            'wardName',
            'districtName',
            'cityName',
            'category',
            'brand',
            'subcategory'
        ));
    }

    public function submitReturn(Request $request, $order_code)
    {
        $request->validate([
            'reason'   => 'required|string|min:5',
            'products' => 'required|array',
        ]);

        $order = Order::where('order_code', $order_code)->firstOrFail();

        $code                = Str::random(11);
        $return              = new ReturnModel();
        $return->order_id    = $order->order_id;
        $return->return_date = now();
        $return->quantity    = 0;
        $return->reason      = $request->reason;
        $return->return_code = $code;
        $return->status      = 'pending';
        $return->save();

        $totalQuantity = 0;

        foreach ($request->products as $product_id => $product_data) {

            if (! isset($product_data['selected']) || $product_data['selected'] != 1) {
                continue;
            }

            $quantity = (int) ($product_data['quantity'] ?? 0);
            if ($quantity <= 0) {
                continue;
            }

            $condition = $product_data['condition'] ?? null;

            ReturnItem::create([
                'return_id'  => $return->return_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'condition'  => $condition,
            ]);

            $totalQuantity += $quantity;
        }

        if ($totalQuantity == 0) {
            $return->delete();

            return redirect()->back()->withErrors(['products' => 'Bạn phải chọn ít nhất một sản phẩm để trả.']);
        }

        $return->quantity = $totalQuantity;
        $return->save();

        DB::table('tbl_order')->where('order_id', $order->order_id)->update(['order_status' => 'Hoàn trả']);
        return redirect('/home/account/info/my-order')->with('success', 'Yêu cầu trả hàng đã được gửi.');
    }

    public function help()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        return view('pages.customer.help')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }

    public function requestDeleteAccount()
    {
        $customer_id = Session::get('customer_id');
        $customer    = Customer::find($customer_id)->first();
        if (! $customer) {
            return Redirect()->back()->with('error', 'Khách hàng không tồn tại');
        }
        $customer->delete_request = true;

        return Redirect('/home/account/info/help')->with('success', 'Yêu cầu xóa tài khoản thành công');
    }

    public function favorite_product()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $customer_id = Session::get('customer_id');
        if (! $customer_id) {
            return redirect()->back()->with('error', 'Yêu cầu đăng nhập tài khoản');
        }
        $favorite = FavoriteProduct::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_favorite_products.product_id')
            ->where('tbl_favorite_products.customer_id', $customer_id)
            ->paginate(10);

        return view('pages.customer.favorite_products')
            ->with('favorite', $favorite)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }
    public function filter(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $customer_id = Session::get('customer_id');
        if (! $customer_id) {
            abort(404);
        }
        $product = FavoriteProduct::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_favorite_products.product_id')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->where('tbl_favorite_products.customer_id', $customer_id)
            ->select('tbl_product.*');
        switch ($request->productFilter) {
            case 'best_seller':
                $product->orderBy('total_sold', 'desc')->get();
                break;

            case 'price_asc':
                $product->orderBy('product_price', 'asc')->get();
                break;

            case 'price_desc':
                $product->orderBy('product_price', 'desc')->get();
                break;

            case 'atoz':
                $product->orderBy('product_name', 'asc')->get();
                break;

            case 'ztoa':
                $product->orderBy('product_name', 'desc')->get();
                break;

            default:
                $product->orderBy('stock', 'desc')->get();
                break;
        }

        $all = $product->get()->unique('product_name')->values();

        $page                = $request->input('page', 1);
        $perPage             = 4;
        $offset              = ($page - 1) * $perPage;
        $itemsForCurrentPage = $all->slice($offset, $perPage);

        $favorite = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('pages.customer.favorite_products')->with('category', $cate_product)->with('brand', $brand_product)->with('subcategory', $subcate_product)->with('favorite', $favorite);
    }

    public function unloved($id)
    {
        $customer = Session::get('customer_id');
        FavoriteProduct::where('customer_id', $customer)
            ->where('product_id', $id)
            ->delete();

        return Redirect()->back()->with('message', 'Bỏ yêu thích sản phẩm thành công');
    }

    public function coupons()
    {
        $cate_product    = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $customer = Session::get('customer_id');
        $usage = UsageModel::where('customer_id', $customer)
            ->pluck('coupon_id')
            ->toArray();

        $order = Order::where('customer_id', $customer)->orderBy('created_at', 'desc')->get();
        $coupons = Coupon::join('tbl_coupon_usage', 'tbl_coupon_usage.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->whereNotIn('tbl_coupon_usage.coupon_id', $usage)
            ->orderBy('tbl_discount_coupon.created_at', 'desc')->paginate(8);

        return view('pages.customer.coupon')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('order', $order)
            ->with('coupons', $coupons);
    }




    //Admin

    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }

    public function list()
    {
        $this->AuthLogin();
        $customer = Customer::all();

        $cities    = json_decode(file_get_contents(public_path('data/tinh_tp.json')), true);
        $districts = json_decode(file_get_contents(public_path('data/quan_huyen.json')), true);
        $wards     = json_decode(file_get_contents(public_path('data/xa_phuong.json')), true);

        foreach ($customer as $customers) {
            $customers->ward    = $this->findNameByCode($wards, $customers->ward);
            $customers->district = $this->findNameByCode($districts, $customers->district);
            $customers->city    = $this->findNameByCode($cities, $customers->city);
        }
        return view('admin.customer.list')
            ->with('customer', $customer);
    }
    public function delete($customer_id)
    {
        $this->AuthLogin();
        DB::table('tbl_customer')
            ->join('tbl_order', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->where('customer_id', $customer_id)
            ->whereIn('tbl_order.ỏder_status', 'Đang chờ xử lý')
            ->delete();
        Session::put('message', 'Xóa khách hàng thành công');

        return Redirect::to('/admin/customers');
    }
    public function view($customer_id)
    {
        $this->AuthLogin();
        $order = Order::join('tbl_payment', 'tbl_payment.payment_id', '=', 'tbl_order.payment_id')
            ->where('customer_id', $customer_id)
            ->select('tbl_order.*', 'tbl_payment.payment_status')
            ->get();
        return view('admin.customer.view')
            ->with('orders', $order);
    }

    public function return_details($code)
    {
        $this->AuthLogin();

        $order_details = DB::table('tbl_order_details')
            ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->join('tbl_product_variants', 'tbl_order_details.product_id', '=', 'tbl_product_variants.variants_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->where('tbl_order.order_code', $code)
            ->select('tbl_order_details.*', 'tbl_order.coupon_id', 'tbl_product.slug_product', 'tbl_product.product_image')
            ->get();

        $order_info = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('tbl_order.order_code', $code)
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
            ->where('tbl_order.order_code', $code)
            ->first();

        $return_item = ReturnItem::join('tbl_product_variants', 'tbl_product_variants.variants_id', 'tbl_return_items.product_id')
            ->join('tbl_returns', 'tbl_returns.return_id', '=', 'tbl_return_items.return_id')
            ->join('tbl_order', 'tbl_order.order_id', '=', 'tbl_returns.order_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->join('tbl_size_product', 'tbl_size_product.size_id', '=', 'tbl_product_variants.size_id')
            ->where('tbl_order.order_code', $code)
            ->select(
                'tbl_return_items.*',
                'tbl_product.product_name',
                'tbl_product.product_price',
                'tbl_product.product_image',
                'tbl_size_product.size_name',
                'tbl_color_product.color_name',
            )
            ->get();
        return view('admin.customer.returns', compact(
            'order_details',
            'order_info',
            'returns',
            'return_item',
            'wardName',
            'districtName',
            'cityName'
        ));
    }

    public function order_details($code)
    {
        $this->AuthLogin();

        $order_details = DB::table('tbl_order_details')
            ->join('tbl_order', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->join('tbl_product_variants', 'tbl_order_details.product_id', '=', 'tbl_product_variants.variants_id')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->where('tbl_order.order_code', $code)
            ->select('tbl_order_details.*', 'tbl_order.coupon_id', 'tbl_product.slug_product', 'tbl_product.product_image')
            ->get();

        $order_info = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->leftJoin('tbl_discount_coupon', 'tbl_order.coupon_id', '=', 'tbl_discount_coupon.coupon_id')
            ->where('tbl_order.order_code', $code)
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
        return view('admin.customer.details', compact(
            'order_details',
            'order_info',
            'wardName',
            'districtName',
            'cityName'
        ));
    }
}
