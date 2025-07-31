<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

// session_start();
class AdminController extends Controller
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
    public function index()
    {
        return view('admin_login');
    }
    public function register()
    {
        return view('admin_register');
    }
    public function add(Request $request)
    {
        $data  = $request->all();
        $pass  = 'NQfashion@' . strtoupper(Str::random(3));
        $email = $data['admin_email'];
        $admin = new Admin();

        $admin->admin_email    = $data['admin_email'];
        $admin->admin_phone    = $data['admin_phone'];
        $admin->admin_name     = $data['admin_name'];
        $admin->admin_password = $pass;
        $admin->admin_role     = 'staff';
        $admin->status         = 0;
        $admin->created_at     = now();
        $admin->save();

        $link           = url("/admin/login");
        $messageContent = <<<EOT
            Đây là thông tin tài khoản mật khẩu của bạn để truy cập trong hệ thống quản lý cửa hàng NQ fashion.
            Tài khoản:{$email}
            Mật khẩu: {$pass}
            Vui lòng đăng nhập mật khẩu tại: {$link}
            EOT;

        Mail::raw($messageContent, function ($message) use ($email) {
            $message->to($email)
                ->subject('Tài khoản hệ thống - Đăng nhập');
        });

        return Redirect('/admin/')->with('success', 'Đăng ký thành công');
    }
    public function forget_password()
    {
        return view('admin_forget_password');
    }
    public function forget_password_email(Request $request)
    {
        $email=$request->admin_email;

         $admin = Admin::where('admin_email', $email)->first();

        if(!$admin)
        {
            abort(404, 'Không tìm thấy tài khoản email này');
        }
        $pass  = 'NQfashion@' . strtoupper(Str::random(3));

        $admin=Admin::where('admin_email',$email)->update([
            'admin_password'=>$pass,
        ]);
        $link = url("/admin/login");
        $messageContent = <<<EOT
            Đây là mật khẩu mới của bạn để truy cập trong hệ thống quản lý cửa hàng NQ fashion.
            Tài khoản:{$email}
            Mật khẩu: {$pass}
            Vui lòng đăng nhập mật khẩu tại: {$link}
            EOT;

        Mail::raw($messageContent, function ($message) use ($email) {
            $message->to($email)
                ->subject('Tài khoản hệ thống - Đăng nhập');
        });

        return Redirect('/admin/')->with('success', 'Lấy lại mật khẩu thành công');

    }
    public function show_dashboard()
    {
        $this->AuthLogin();
        $customer = Customer::count();
        $order    = Order::count();
        $product  = Variant::sum('stock');
        $money    = Order::sum('order_total');

        // Đơn hàng
        $ordersByMonthYear = DB::table('tbl_order')
            ->selectRaw("DATE_FORMAT(created_at, '%m/%Y') as month_year, COUNT(*) as total")
            ->groupBy('month_year')
            ->orderByRaw("STR_TO_DATE(month_year, '%m/%Y')")
            ->pluck('total', 'month_year');

        // Doanh thu
        $revenueByMonthYear = DB::table('tbl_order')
            ->selectRaw("DATE_FORMAT(created_at, '%m/%Y') as month_year, SUM(order_total) as revenue")
            ->groupBy('month_year')
            ->orderByRaw("STR_TO_DATE(month_year, '%m/%Y')")
            ->pluck('revenue', 'month_year');

        return view('admin.dashboard')
            ->with('customer', $customer)
            ->with('order', $order)
            ->with('product', $product)
            ->with('money', $money)
            ->with('orderLabels', $ordersByMonthYear->keys())
            ->with('orderData', $ordersByMonthYear->values())
            ->with('revenueData', $revenueByMonthYear->values());
    }
    public function dashboard(Request $request)
    {
        $this->AuthLogin();
        $admin_email    = $request->admin_email;
        $admin_password = $request->admin_password;
        $result = DB::table('tbl_admin')
            ->where('admin_email', $admin_email)
            ->where('admin_password', $admin_password)
            ->where('status', 0)->first();
        if ($result) {
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            Session::put('admin_role', $result->admin_role);
            Session::put('messages', 'Đăng nhập tài khoản thành công');
            return Redirect('/admin/dashboard');
        } else {
            Session::put('message', 'Sai tài khoản hoặc mật khẩu. Vui lòng thử lại.');
            return Redirect('/admin');
        }

    }
    public function logout()
    {
        $this->AuthLogin();
        Session::flush();              // Xoá toàn bộ session
        return Redirect::to('/admin'); // Trả về trang đăng nhập
    }

    public function create()
    {
        $this->AuthLogin();
        return view('admin.employee.add');
    }
    public function staffs()
    {
        $this->AuthLogin();

        $admin = db::table('tbl_admin')->orderBy('admin_id', 'DESC')->get();
        return view('admin.employee.list')->with('admin', $admin);
    }
    public function save_staff(Request $request)
    {

        $data = $request->all();

        $admin = new Admin();

        $email                 = $data['admin_email'];
        $pass                  = 'NQfashion@' . strtoupper(Str::random(3));
        $admin->admin_name     = $data['admin_name'];
        $admin->admin_email    = $data['admin_email'];
        $admin->admin_phone    = $data['admin_phone'];
        $admin->admin_password = $pass;
        $admin->admin_role     = $data['admin_role'];
        $admin->status         = 0;
        $admin->created_at     = now();
        $admin->save();

        $link           = url("/admin/login");
        $messageContent = <<<EOT
            Đây là thông tin tài khoản mật khẩu của bạn để truy cập trong hệ thống quản lý cửa hàng NQ fashion.
            Tài khoản:{$email}
            Mật khẩu: {$pass}
            Vui lòng đăng nhập mật khẩu tại: {$link}
            EOT;

        Mail::raw($messageContent, function ($message) use ($email) {
            $message->to($email)
                ->subject('Tài khoản hệ thống - Đăng nhập');
        });
        return Redirect('/admin/staffs')->with('message', 'Thêm nhân viên thành công');
    }

    public function delete($admin)
    {
        $this->AuthLogin();
        DB::table('tbl_admin')->where('admin_id', $admin)->delete();
        return Redirect('/admin/staffs')->with('message', 'Xóa nhân viên thành công');
    }

    public function edit($admin)
    {
        $this->AuthLogin();
        $admin = Admin::find($admin)->first();
        return view('admin.employee.edit')->with('admin', $admin);
    }

    public function update(Request $request, $admin)
    {
        $this->AuthLogin();

        DB::table('tbl_admin')->where('admin_id', $admin)->update([
            'admin_name'  => $request->admin_name,
            'admin_phone' => $request->admin_phone,
            'admin_email' => $request->admin_email,
            'updated_at'  => now(),
        ]);

        return Redirect('/admin/staffs')->with('message', 'Cập nhật thông tin thành công');
    }
    public function lock($admin)
    {
        $this->AuthLogin();
        DB::table('tbl_admin')->where('admin_id', $admin)->update(['status' => 1]);
        Session::put('message', 'Khóa tài khoản thành công !');
        return Redirect::to('/admin/staffs');
    }
    public function unlock($admin)
    {
        $this->AuthLogin();
        DB::table('tbl_admin')->where('admin_id', $admin)->update(['status' => 0]);
        Session::put('message', 'Mở tài khoản thành công !');
        return Redirect::to('/admin/staffs');
    }

    public function assign_role(Request $request, $admin)
    {
        $this->AuthLogin();

        DB::table('tbl_admin')->where('admin_id', $admin)->update([
            'admin_role' => $request->selected_role,
        ]);

        return Redirect('/admin/staffs/')->with('message', 'Trao quyền thành công');
    }

    public function info()
    {
        return view('admin.info');
    }
}
