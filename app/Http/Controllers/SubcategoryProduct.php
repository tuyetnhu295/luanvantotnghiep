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

class SubcategoryProduct extends Controller
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
    public function add_subcategory_product()
    {
        $this->AuthLogin();
        $parentcate_pro = Category::all();
        return view('admin.subcategory_product.add_subcategory_product')->with('parentcategory', $parentcate_pro);
    }
    public function all_subcategory_product()
    {
        $this->AuthLogin();
        $parentcate_pro = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $all_subcategory_product = DB::table('tbl_subcategory_product')->get();
        $manager_subcategory_product = view('admin.subcategory_product.all_subcategory_product')->with('all_subcategory_product', $all_subcategory_product)->with('parentcate_pro', $parentcate_pro);
        return view('admin_layout')->with('admin.subcategory_product.all_subcategory_product', $manager_subcategory_product);
    }
    public function save_subcategory_product(Request $request)
    {
        $this->AuthLogin();

        $data = array();
        $data['subcategory_product_name'] = $request->subcategory_product_name;
        $data['subcategory_product_desc'] = $request->subcategory_product_desc;
        $data['subcategory_product_status'] = $request->subcategory_product_status;
        $data['parent_category_product_id'] = $request->parent_category_product_id;
        $data['slug_subcategory_product'] = $request->slug_subcategory_product;
        $data['created_at'] = now();

        $get_image = $request->file('banner');

        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . time() . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/banner/subcategory', $new_image);
            $data['banner'] = $new_image;
        } else {
            $data['banner'] = '';
        }

        DB::table('tbl_subcategory_product')->insert($data);

        Session::put('message', 'Thêm loại sản phẩm thành công !');
        return Redirect::to('/admin/add-subcategory-product');
    }

    public function unactive_subcategory_product($subcategory_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->update(['subcategory_product_status' => 1]);
        Session::put('message', 'Không kích hoạt danh mục con sản phẩm thành công !');
        return Redirect::to('/admin/all-subcategory-product');
    }
    public function active_subcategory_product($subcategory_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->update(['subcategory_product_status' => 0]);
        Session::put('message', 'Kích hoạt danh mục con sản phẩm thành công !');
        return Redirect::to('/admin/all-subcategory-product');
    }
    public function edit_subcategory_product($subcategory_product_id)
    {
        $this->AuthLogin();
        $parentcate_pro = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $edit_subcategory_product = DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->get();
        $manager_subcategory_product = view('admin.subcategory_product.edit_subcategory_product')->with('edit_subcategory_product', $edit_subcategory_product)->with('parentcategory', $parentcate_pro);
        return view('admin_layout')->with('admin.subcategory_product.edit_subcategory_product', $manager_subcategory_product);
    }
    public function update_subcategory_product(Request $request, $subcategory_product_id)
    {
        $this->AuthLogin();

        $subcategory = DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->first();

        $data = [];
        $data['subcategory_product_name'] = $request->subcategory_product_name;
        $data['subcategory_product_desc'] = $request->subcategory_product_desc;
        $data['parent_category_product_id'] = $request->parent_category_product_id;
        $data['slug_subcategory_product'] = $request->slug_subcategory_product;
        $data['updated_at'] = now();

        $image_new = $request->file('banner'); // đồng bộ với lúc thêm

        if ($image_new) {
            if ($subcategory->banner) {
                $old_image_path = 'uploads/banner/subcategory/' . $subcategory->banner;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $get_name_image = $image_new->getClientOriginalName();
            $name_image = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image = $name_image . time() . '.' . $image_new->getClientOriginalExtension();
            $image_new->move('uploads/banner/subcategory', $new_image);
            $data['banner'] = $new_image;
        }

        DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->update($data);

        Session::put('message', 'Cập nhật danh mục con sản phẩm thành công !');
        return Redirect::to('/admin/all-subcategory-product');
    }
    public function delete_subcategory_product($subcategory_product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_subcategory_product')->where('subcategory_product_id', $subcategory_product_id)->delete();
        Session::put('message', 'Xóa danh mục con sản phẩm thành công !');
        return Redirect::to('/admin/all-subcategory-product');
    }

    //Page
    public function subcategory_product($slug, Request $request)
    {

        $cate_product = DB::table('tbl_category_product')->where('category_product_status', 0)->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', 0)->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', 0)->orderBy('subcategory_product_id', 'desc')->get();

        $subcategory_info = DB::table('tbl_subcategory_product')
            ->where('subcategory_product_status', 0)
            ->where('slug_subcategory_product', $slug)->first();

        $subcategory_id = $subcategory_info->subcategory_product_id;

        $all_product = DB::table('tbl_product')
            ->where('subcategory_product_id', $subcategory_id)
            ->orderBy('product_id', 'desc')
            ->paginate(4);

        return view('pages.subcategory.show_subcategory')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('subcategory_id', $subcategory_id)
            ->with('subcategory_info', $subcategory_info);
    }

    public function filter($slug, Request $request)
    {
        $cate_product = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $subcategory_info = DB::table('tbl_subcategory_product')
            ->where('subcategory_product_status', 0)
            ->where('slug_subcategory_product', $slug)->first();

        $subcategory_id = $subcategory_info->subcategory_product_id;

        $product = DB::table('tbl_product')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->where('subcategory_product_id', $subcategory_id);

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
        return view('pages.subcategory.show_subcategory')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product)
            ->with('subcategory_id', $subcategory_id)
            ->with('subcategory_info', $subcategory_info);
    }
}
