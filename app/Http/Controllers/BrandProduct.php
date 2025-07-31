<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

// session_start();
class BrandProduct extends Controller
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
    public function add_brand_product()
    {
        $this->AuthLogin();
        return view('admin.brand_product.add_brand_product');
    }
    public function all_brand_product()
    {
        $this->AuthLogin();

        $all_brand_product = Brand::orderBy('brand_product_id', 'DESC')->get();
        $manager_brand_product = view('admin.brand_product.all_brand_product')->with('all_brand_product', $all_brand_product);
        return view('admin_layout')->with('admin.brand_product.all_brand_product', $manager_brand_product);
    }
    public function save_brand_product(Request $request)
    {
        $this->AuthLogin();

        $data = $request->all();
        $brand = new Brand();

        $brand->brand_product_name = $data['brand_product_name'];
        $brand->brand_product_desc = $data['brand_product_desc'];
        $brand->brand_product_status = $data['brand_product_status'];
        $brand->slug_brand_product = $data['slug_brand_product'];

        $banner = $request->file('banner');
        if ($banner) {
            $name_banner = $banner->getClientOriginalName();
            $name_image = current(explode('.', $name_banner));
            $new_image = $name_image . time() . '.' . $banner->getClientOriginalExtension();
            $banner->move('uploads/banner/brand', $new_image);
            $brand->banner = $new_image;
        } else {
            $brand->banner = '';
        }

        $brand->save();

        Session::put('message', 'Thêm thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/add-brand-product');
    }

    public function unactive_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand_product')->where('brand_product_id', $brand_product_id)->update(['brand_product_status' => 1]);
        Session::put('message', 'Không kích hoạt thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-brand-product');
    }
    public function active_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand_product')->where('brand_product_id', $brand_product_id)->update(['brand_product_status' => 0]);
        Session::put('message', 'Kích hoạt thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-brand-product');
    }
    public function edit_brand_product($brand_product_id)
    {
        $this->AuthLogin();

        $edit_brand_product = Brand::find($brand_product_id);
        $manager_brand_product = view('admin.brand_product.edit_brand_product')->with('edit_brand_product', $edit_brand_product);
        return view('admin_layout')->with('admin.brand_product.edit_brand_product', $manager_brand_product);
    }
    public function update_brand_product(Request $request, $brand_product_id)
    {
        $this->AuthLogin();

        $data = $request->all();
        $brand = Brand::find($brand_product_id);

        $brand->brand_product_name = $data['brand_product_name'];
        $brand->brand_product_desc = $data['brand_product_desc'];
        $brand->slug_brand_product = $data['slug_brand_product'];
        $image_new = $request->file('banner');
        if ($image_new) {
            if ($brand->banner) {
                $old_image_path = ('uploads/banner/brand' . $brand->banner);
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $get_name_image = $image_new->getClientOriginalName();
            $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image = $name_image . time() . '.' . $image_new->getClientOriginalExtension();
            $image_new->move('uploads/banner/brand', $new_image);
            $brand->banner = $new_image;
        }
        $brand->save();

        Session::put('message', 'Cập nhật thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-brand-product');
    }
    public function delete_brand_product($brand_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_brand_product')->where('brand_product_id', $brand_product_id)->delete();
        Session::put('message', 'Xóa thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-brand-product');
    }

    //Page

    public function brand_product($slug, Request $request)
    {

        $cate_product = DB::table('tbl_category_product')->where('category_product_status', 0)->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', 0)->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', 0)->orderBy('subcategory_product_id', 'desc')->get();

        $brand_info = DB::table('tbl_brand_product')
            ->where('slug_brand_product', $slug)->first();
        $brand_id = $brand_info->brand_product_id;

        $all_product = DB::table('tbl_product')
            ->where('brand_product_id', $brand_id)
            ->orderBy('product_id', 'desc')
            ->paginate(4);
        return view('pages.brand.show_brand')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('brand_id', $brand_id)
            ->with('brand_info', $brand_info);
    }

    public function filter($slug, Request $request)
    {
        $cate_product = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $brand_info = DB::table('tbl_brand_product')
            ->where('slug_brand_product', $slug)->first();

        $brand_id = $brand_info->brand_product_id;

        $product = DB::table('tbl_product')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->where('brand_product_id', $brand_id);

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
        $all = $product->get()->unique('product_name');
        $page = $request->input('page', 1);
        $perPage = 4;
        $offset = ($page - 1) * $perPage;
        $itemsForCurrentPage = $all->slice($offset, $perPage);

        $all_product = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('pages.all_product')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('brand_id', $brand_id)
            ->with('brand_info', $brand_info);
    }
}
