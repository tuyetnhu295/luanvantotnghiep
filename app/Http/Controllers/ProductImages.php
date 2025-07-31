<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// session_start();

class ProductImages extends Controller
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

    public function add()
    {
        $this->AuthLogin();
        $variant = Variant::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->select('tbl_product_variants.*', 'tbl_product.product_name', 'tbl_color_product.color_name')
            ->get();
        $color = Color::all();
        return view('admin.product_images.add')
            ->with('variant', $variant)
            ->with('color', $color);
    }

    public function all()
    {
        $this->AuthLogin();
        $images = DB::table('tbl_product_images')
            ->join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_images.product_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_images.color_id')
            ->orderBy('product_image_id', 'desc')->get();
        return view('admin.product_images.all')
            ->with('images', $images);
    }

    public function save(Request $request)
    {
        $this->AuthLogin();

        $data = array();

        $data['product_id'] = $request->product_id;
        $data['color_id'] = $request->color_id;
        $data['image_type'] = $request->image_type;
        $data['product_image_status'] = $request->product_image_status;
        $data['created_at'] = now();

        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . time() . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/products', $new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product_images')->insert($data);
            Session::put('message', 'Thêm hình ảnh sản phẩm thành công !');
            return Redirect::to('/admin/add-product-images');
        } else {
            $data['product_image'] = '';
        }
        DB::table('tbl_product_images')->insert($data);
        Session::put('message', 'Thêm hình ảnh sản phẩm thành công !');
        return Redirect::to('/admin/add-product-images');
    }

    public function update(Request $request, $id)
    {
        $this->AuthLogin();
        $images = DB::table('tbl_product_images')->where('product_image_id', $id)->first();
        $data['product_id'] = $request->product_id;
        $data['color_id'] = $request->color_id;
        $data['image_type'] = $request->image_type;
        $image_new = $request->file('product_image');
        if ($image_new) {
            if ($images->product_image) {
                $old_image_path = ('uploads/products' . $images->product_image);
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $get_name_image = $image_new->getClientOriginalName();
            $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image = $name_image . time() . '.' . $image_new->getClientOriginalExtension();
            $image_new->move('uploads/products', $new_image);
            $data['product_image'] = $new_image;
        }
        $data['updated_at'] = now();
        DB::table('tbl_product_images')->where('product_image_id', $id)->update($data);
        Session::put('message', 'Cập nhật hình ảnh sản phẩm thành công !');
        return Redirect::to('/admin/add-product-images');
    }

    public function edit($id)
    {
        $this->AuthLogin();
        $variant = Variant::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->get();
        $images = DB::table('tbl_product_images')->where('product_image_id', $id)->get();
        return view('admin.product_images.edit')
            ->with('images', $images)
            ->with('variant', $variant)
        ;
    }

    public function delete($id)
    {
        $this->AuthLogin();
        $images = DB::table('tbl_product_images')->where('product_image_id', $id)->first();

        if ($images && $images->product_image) {
            $old_image_path = public_path('uploads/products/' . $images->product_image);
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        DB::table('tbl_product_images')->where('product_image_id', $id)->delete();
        Session::put('message', 'Xóa hình ảnh sản phẩm thành công!');
        return Redirect::to('/admin/all-product-images');
    }

    public function unactive($id)
    {
        $this->AuthLogin();
        DB::table('tbl_product_images')->where('product_image_id', $id)->update(['product_image_status' => 1]);
        Session::put('message', 'Không kích hoạt hình ảnh sản phẩm thành công !');
        return Redirect::to('/admin/all-product-images');
    }

    public function active($id)
    {
        $this->AuthLogin();
        DB::table('tbl_product_images')->where('product_image_id', $id)->update(['product_image_status' => 0]);
        Session::put('message', 'Kích hoạt hình ảnh sản phẩm thành công !');
        return Redirect::to('/admin/all-product-images');
    }
    public function getColors($product_id)
    {
        $colors = DB::table('tbl_color_product')
            ->join('tbl_product_variants', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->where('tbl_product_variants.product_id', $product_id)
            ->select('tbl_color_product.color_id', 'tbl_color_product.color_name')
            ->get()
            ->unique('color_id');

        return response()->json($colors);
    }
}
