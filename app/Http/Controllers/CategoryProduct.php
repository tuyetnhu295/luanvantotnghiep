<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

// session_start();

class CategoryProduct extends Controller
{
    //
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function add_category_product()
    {
        $this->AuthLogin();
        return view('admin.category_product.add_category_product');
    }
    public function all_category_product()
    {
        $this->AuthLogin();
        // $all_category_product = DB::table('tbl_category_product')->get();
        $all_category_product = Category::orderBy('category_product_id', 'DESC')->get();
        $manager_category_product = view('admin.category_product.all_category_product')->with('all_category_product', $all_category_product);
        return view('admin_layout')->with('admin.category_product.all_category_product', $manager_category_product);
    }
    public function save_category_product(Request $request)
    {
        $this->AuthLogin();
        $data = $request->all();
        $category = new Category();
        $category->category_product_name = $data['category_product_name'];
        $category->category_product_desc = $data['category_product_desc'];
        $category->category_product_status = $data['category_product_status'];
        $category->slug_category_product = $data['slug_category_product'];

        $banner = $request->file('banner');
        if ($banner) {
            $name_banner = $banner->getClientOriginalName();
            $name_image = current(explode('.', $name_banner));
            $new_image = $name_image . time() . '.' . $banner->getClientOriginalExtension();
            $banner->move('uploads/banner/category', $new_image);
            $category->banner = $new_image;
        } else {
            $category->banner = '';
        }
        $category->save();

        Session::put('message', 'Thêm danh mục sản phẩm thành công !');
        return Redirect::to('/admin/add-category-product');
    }
    public function unactive_category_product($category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->update(['category_product_status' => 1]);
        }
        // DB::table('tbl_category_product')->where('category_product_id', $category_product_id)->update(['category_product_status' => 1]);
        Session::put('message', 'Không kích hoạt danh mục sản phẩm thành công !');
        return Redirect::to('/admin/all-category-product');
    }
    public function active_category_product($category_product_id)
    {
        $this->AuthLogin();
        $category = Category::find($category_product_id);
        if ($category) {
            $category->update(['category_product_status' => 0]);
        }
        // DB::table('tbl_category_product')->where('category_product_id', $category_product_id)->update(['category_product_status' => 0]);
        Session::put('message', 'Kích hoạt danh mục sản phẩm thành công !');
        return Redirect::to('/admin/all-category-product');
    }
    public function edit_category_product($category_product_id)
    {
        $this->AuthLogin();
        $edit_category_product = Category::find($category_product_id);
        $manager_category_product = view('admin.category_product.edit_category_product')->with('edit_category_product', $edit_category_product);
        return view('admin_layout')->with('admin.category_product.edit_category_product', $manager_category_product);
    }
    public function update_category_product(Request $request, $category_product_id)
    {
        $this->AuthLogin();

        $data = $request->all();

        $category = Category::find($category_product_id);

        $category->category_product_name = $data['category_product_name'];
        $category->category_product_desc = $data['category_product_desc'];
        $category->slug_category_product = $data['slug_category_product'];
        $image_new = $request->file('banner');
        if ($image_new) {
            if ($category->banner) {
                $old_image_path = ('uploads/banner/brand' . $category->banner);
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $get_name_image = $image_new->getClientOriginalName();
            $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image = $name_image . time() . '.' . $image_new->getClientOriginalExtension();
            $image_new->move('uploads/banner/category', $new_image);
            $category->banner = $new_image;
        }
        $category->save();

        Session::put('message', 'Cập nhật danh mục sản phẩm thành công !');
        return Redirect::to('/admin/all-category-product');
    }
    public function delete_category_product($category_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_product_id', $category_product_id)->delete();
        Session::put('message', 'Xóa danh mục sản phẩm thành công !');
        return Redirect::to('/admin/all-category-product');
    }

    // Page
    public function category_product($slug, Request $request)
    {


        // Lấy dữ liệu danh mục, brand, sub-category để hiển thị menu
        $cate_product = DB::table('tbl_category_product')->where('category_product_status', 0)->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', 0)->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', 0)->orderBy('subcategory_product_id', 'desc')->get();


        $category_info = DB::table('tbl_category_product')->where('slug_category_product', $slug)->first();

        $category_id = $category_info->category_product_id;

        $all_product = DB::table('tbl_product')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->where('product_status', 0)
            ->where('tbl_subcategory_product.parent_category_product_id', $category_id)
            ->orderBy('product_id', 'desc')
            ->paginate(4);

        return view('pages.category.show_category')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('category_id', $category_id)
            ->with('category_info', $category_info);
    }

    public function filter($slug, Request $request)
    {
        $cate_product = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $category_info = DB::table('tbl_category_product')->where('slug_category_product', $slug)->first();

        $category_id = $category_info->category_product_id;

        $product = DB::table('tbl_product')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->where('tbl_subcategory_product.parent_category_product_id', $category_id);

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
        return view('pages.category.show_category')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('category_id', $category_id)
            ->with('category_info', $category_info);
    }
}
