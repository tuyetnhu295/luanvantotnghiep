<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CouponController extends Controller
{
    //
    public function add_coupon()
    {
        return view('admin.coupon.add');
    }

    public function save_coupon(Request $request)
    {
        $data = $request->all();

        $coupon = new Coupon();

        $coupon->coupon_code = $data['coupon_code'];
        $coupon->description = $data['description'];
        $coupon->discount_type = $data['discount_type'];
        $coupon->discount_value = $data['discount_value'];
        $coupon->min_order_value = $data['min_order_value'];
        $coupon->customer_type = $data['customer_type'];
        $coupon->start_date = $data['start_date'];
        $coupon->end_date = $data['end_date'];
        $coupon->usage_limit = $data['usage_limit'];
        $coupon->used_count = 0;
        $coupon->status = $data['status'];

        $coupon->save();

        Session::put('message', 'Thêm mã giảm giá thành công!');
        return Redirect::to('/admin/add-coupon');
    }

    public function all_coupon()
    {
        $coupon = Coupon::all();

        foreach ($coupon as $val) {
            if ($val->end_date < now() && $val->end_date) {
                $val->status = 'expired';
                $val->save();
            }
        }
        return view('admin.coupon.all')
            ->with('coupon', $coupon);
    }

    public function delete_coupon($coupon_id)
    {
        $coupon = Coupon::find($coupon_id);
        if ($coupon) {
            $coupon->delete();
            Session::put('message', 'Xóa mã giảm giá thành công!');
        } else {
            Session::put('message', 'Không tìm thấy mã giảm giá!');
        }
        return Redirect::to('/admin/all-coupon');
    }
}
