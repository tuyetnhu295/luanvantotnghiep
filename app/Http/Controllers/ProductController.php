<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\FavoriteProduct;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

// session_start();
class ProductController extends Controller
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
    public function add_product()
    {
        $this->AuthLogin();
        $cate_product  = Category::all();
        $brand_product = Brand::all();
        $subcategory   = Subcategory::all();
        return view('admin.product.add_product')
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product)
            ->with('subcategory', $subcategory);
    }

    public function all_product()
    {
        $this->AuthLogin();
        $cate_product    = Category::all();
        $brand_product   = Brand::all();
        $subcate_product = Subcategory::all();
        $all_product     = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->orderBy('product_id', 'desc')->get();

        return view('admin.product.all_product')
            ->with('all_product', $all_product)->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product)
            ->with('subcate_product', $subcate_product);
    }
    public function save_product(Request $request)
    {
        $this->AuthLogin();
        $code = Str::random(11);
        $data = [];
        $ten  = $request->product_name;

        if ($ten == null || trim($ten) === '') {
            return Redirect()->back()->with('error', 'Ten khong duoc de trong hoac la khoang trang');
        }
        if (! preg_match('/^[a-zA-Z0-9\s\-_À-ỹà-ỹ]+$/u', $ten)) {
            return Redirect()->back()->with('error', 'Tên không được chứa ký tự đặc biệt');
        }

        // if(preg_match('/^.+$/',$ten))
        // {
        //     return Redirect()->back()->with('error', 'Tên không được chứa ký tự .');
        // }

        // if(!preg_match('/^\d+(\.\d{1,2})?$/',$request->product_price))
        // {
        //     return Redirect()->back()->with('error', 'Yeu cau gia tien phai la so');
        // }
        $data['product_name']           = $request->product_name;
        $data['product_code']           = $code;
        $data['subcategory_product_id'] = $request->subcategory_product_id;
        $data['brand_product_id']       = $request->brand_product_id;
        $data['product_desc']           = $request->product_desc;
        $data['product_content']        = $request->product_content;
        $data['product_price']          = $request->product_price;
        $data['product_material']       = $request->product_material;
        $data['product_status']         = $request->product_status;
        $data['slug_product']           = $request->slug_product;
        $data['total_sold']             = 0;
        $data['created_at']             = now();

        $get_image = $request->file('product_image');
        if ($get_image) {
            $get_name_image = $get_image->getClientOriginalName();
            $name_image     = current(explode('.', $get_name_image));
            $new_image      = $name_image . time() . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('uploads/products', $new_image);
            $data['product_image'] = $new_image;
            DB::table('tbl_product')->insert($data);
            Session::put('message', 'Thêm sản phẩm thành công !');
            return Redirect::to('/admin/add-product');
        } else {
            $data['product_image'] = '';
        }
        DB::table('tbl_product')->insert($data);
        Session::put('message', 'Thêm sản phẩm thành công !');
        return Redirect::to('/admin/add-product');
    }
    public function unactive_product($product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 1]);
        Session::put('message', 'Không kích hoạt thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-product');
    }
    public function active_product($product_id)
    {
        $this->AuthLogin();
        DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status' => 0]);
        Session::put('message', 'Kích hoạt sản phẩm thành công !');
        return Redirect::to('/admin/all-product');
    }
    public function edit_product($product_id)
    {
        $this->AuthLogin();
        $cate_product    = Category::all();
        $brand_product   = Brand::all();
        $subcate_product = Subcategory::all();
        $edit_product    = Product::find($product_id);
        $manager_product = view('admin.product.edit_product')->with('edit_product', $edit_product)->with('cate_product', $cate_product)->with('brand_product', $brand_product)->with('subcate_product', $subcate_product);
        return view('admin_layout')->with('admin.product.edit_product', $manager_product);
    }
    public function update_product(Request $request, $product_id)
    {
        $this->AuthLogin();
        $product                        = DB::table('tbl_product')->where('product_id', $product_id)->first();
        $code                           = Str::random(11);
        $data                           = [];
        $data['product_name']           = $request->product_name;
        $data['product_code']           = $code;
        $data['subcategory_product_id'] = $request->subcategory_product_id;
        $data['brand_product_id']       = $request->brand_product_id;
        $data['product_desc']           = $request->product_desc;
        $data['product_content']        = $request->product_content;
        $data['product_price']          = $request->product_price;
        $data['product_material']       = $request->product_material;
        $data['slug_product']           = $request->slug_product;
        $data['total_sold']             = 0;
        $image_new                      = $request->file('product_image');
        if ($image_new) {
            if ($product->product_image) {
                $old_image_path = ('uploads/products' . $product->product_image);
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            $get_name_image = $image_new->getClientOriginalName();
            $name_image     = pathinfo($get_name_image, PATHINFO_FILENAME);
            $new_image      = $name_image . time() . '.' . $image_new->getClientOriginalExtension();
            $image_new->move('uploads/products', $new_image);
            $data['product_image'] = $new_image;
        }
        $data['updated_at'] = now();
        DB::table('tbl_product')->where('product_id', $product_id)->update($data);
        Session::put('message', 'Cập nhật thương hiệu sản phẩm thành công !');
        return Redirect::to('/admin/all-product');
    }
    public function delete_product($product_id)
    {
        $this->AuthLogin();

        $product = DB::table('tbl_product')->where('product_id', $product_id)->first();

        if ($product && $product->product_image) {
            $old_image_path = public_path('uploads/products/' . $product->product_image);
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
        DB::table('tbl_product')->where('product_id', $product_id)->delete();
        Session::put('message', 'Xóa sản phẩm thành công!');
        return Redirect::to('/admin/all-product');
    }

    // End Admin Pages
    public function detail_product($slug, Request $request)
    {
        $cate_product    = Category::all();
        $brand_product   = Brand::all();
        $subcate_product = Subcategory::all();

        // Lấy 1 sản phẩm duy nhất
        $product = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('slug_product', $slug)
            ->first();

        if (! $product) {
            dd('Không tìm thấy sản phẩm với slug:', $slug);
        }
        // Sản phẩm liên quan
        $brand_id       = $product->brand_product_id;
        $subcategory_id = $product->subcategory_product_id;

        $products = DB::table('tbl_product')->where('slug_product', $slug)
            ->first();
        $product_id = $products->product_id;

        $variant = Variant::join('tbl_size_product', 'tbl_size_product.size_id', '=', 'tbl_product_variants.size_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->select('tbl_product_variants.*', 'tbl_size_product.size_name', 'tbl_color_product.color_name')
            ->where('tbl_product_variants.product_id', $product_id)
            ->get();

        $product_relate = DB::table('tbl_product')
            ->where('subcategory_product_id', $subcategory_id)
            ->where('brand_product_id', $brand_id)
            ->whereNotIn('product_id', [$product_id])
            ->get();

        // Lấy toàn bộ hình ảnh của sản phẩm đó
        $images = DB::table('tbl_product_images')
            ->where('product_id', $product_id)->get();

        // Binh luan cua san pham

        $comment = DB::table('tbl_review')
            ->join('tbl_product_variants', 'tbl_product_variants.variants_id', '=', 'tbl_review.product_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_review.customer_id')
            ->where('tbl_product_variants.product_id', $product_id)
            ->where('tbl_review.status', 'approved')
            ->select(
                'tbl_review.review_text',
                'tbl_review.created_at',
                'tbl_customer.customer_name'
            )
            ->orderBy('tbl_review.created_at', 'desc')
            ->paginate(5);

        $total = DB::table('tbl_review')
            ->join('tbl_product_variants', 'tbl_product_variants.variants_id', '=', 'tbl_review.product_id')
            ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_review.customer_id')
            ->where('tbl_product_variants.product_id', $product_id)
            ->where('tbl_review.status', 'approved')
            ->count();

        //Favorite

        $favorite = FavoriteProduct::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_favorite_products.product_id')
            ->where('tbl_favorite_products.product_id', $product_id)
            ->count();

        return view('pages.product.detail_product', [
            'category'       => $cate_product,
            'brand'          => $brand_product,
            'subcategory'    => $subcate_product,
            'product'        => $product,
            'images'         => $images,
            'product_relate' => $product_relate,
            'variant'        => $variant,
            'comment'        => $comment,
            'total'          => $total,
            'favorite'       => $favorite,
        ]);
    }

    //Size
    public function add_size()
    {
        $this->AuthLogin();
        $cate_product  = Category::all();
        $brand_product = Brand::all();
        $subcategory   = Subcategory::all();
        return view('admin.product.size.add')
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product)
            ->with('subcategory', $subcategory);
    }
    public function all_size()
    {
        $this->AuthLogin();

        $all_size     = Size::orderByRaw("FIELD(size_name, 'S', 'M', 'L', 'XL', 'XXL')")->get();
        $manager_size = view('admin.product.size.all')->with('all_size', $all_size);
        return view('admin_layout')->with('admin.product.size.all_size', $manager_size);
    }
    public function save_size(Request $request)
    {
        $this->AuthLogin();
        $data              = $request->all();
        $size              = new Size();
        $size->size_name   = $data['size_name'];
        $size->size_status = $data['size_status'];
        $size->save();

        Session::put('message', 'Thêm kích cỡ sản phẩm thành công !');
        return Redirect::to('/admin/product/size/add-product-size');
    }

    public function unactive_size($id_size)
    {
        $this->AuthLogin();
        $size = Size::find($id_size);
        if ($size) {
            $size->update(['size_status' => 1]);
        }
        Session::put('message', 'Không kích hoạt kích thước sản phẩm thành công !');
        return Redirect::to('/admin/product/size/all-product-size');
    }
    public function active_size($id_size)
    {
        $this->AuthLogin();
        $size = Size::find($id_size);
        if ($size) {
            $size->update(['size_status' => 0]);
        }
        Session::put('message', 'Kích hoạt kích thước sản phẩm thành công !');
        return Redirect::to('/admin/product/size/all-product-size');
    }
    public function edit_size($id_size)
    {
        $this->AuthLogin();
        $edit_size    = Size::find($id_size);
        $manager_size = view('admin.product.size.edit')->with('edit_size', $edit_size);
        return view('admin_layout')->with('admin.product.size.edit_size', $manager_size);
    }
    public function update_size(Request $request, $id_size)
    {
        $this->AuthLogin();

        $data = $request->all();

        $size = Size::find($id_size);

        $size->size_name = $data['size_name'];
        $size->save();

        Session::put('message', 'Cập nhật kích thước sản phẩm thành công !');
        return Redirect::to('/admin/product/size/all-product-size');
    }
    public function delete_size($id_size)
    {
        $this->AuthLogin();
        $size_id = Size::find($id_size);
        $size_id->delete();
        Session::put('message', 'Xóa kích thước sản phẩm thành công !');
        return Redirect::to('/admin/product/size/all-product-size');
    }

    //Color
    public function add_color()
    {
        $this->AuthLogin();
        $cate_product  = Category::all();
        $brand_product = Brand::all();
        $subcategory   = Subcategory::all();
        return view('admin.product.color.add')
            ->with('cate_product', $cate_product)
            ->with('brand_product', $brand_product)
            ->with('subcategory', $subcategory);
    }
    public function all_color()
    {
        $this->AuthLogin();

        $all_color     = Color::all();
        $manager_color = view('admin.product.color.all')->with('all_color', $all_color);
        return view('admin_layout')->with('admin.product.size.all_size', $manager_color);
    }
    public function save_color(Request $request)
    {
        $this->AuthLogin();
        $data                = $request->all();
        $color               = new Color();
        $color->color_name   = $data['color_name'];
        $color->color_status = $data['color_status'];
        $color->save();

        Session::put('message', 'Thêm màu sản phẩm thành công !');
        return Redirect::to('/admin/product/color/add-product-color');
    }
    public function unactive_color($id_color)
    {
        $this->AuthLogin();
        $color = Color::find($id_color);
        if ($color) {
            $color->update(['color_status' => 1]);
        }
        Session::put('message', 'Không kích hoạt màu sản phẩm thành công !');
        return Redirect::to('/admin/product/color/all-product-color');
    }
    public function active_color($id_color)
    {
        $this->AuthLogin();
        $color = Color::find($id_color);
        if ($color) {
            $color->update(['color_status' => 0]);
        }
        Session::put('message', 'Kích hoạt màu sản phẩm thành công !');
        return Redirect::to('/admin/product/color/all-product-color');
    }
    public function edit_color($id_color)
    {
        $this->AuthLogin();
        $edit_color    = Color::find($id_color);
        $manager_color = view('admin.product.color.edit')->with('edit_color', $edit_color);
        return view('admin_layout')->with('admin.product.color.edit_color', $manager_color);
    }
    public function update_color(Request $request, $id_color)
    {
        $this->AuthLogin();

        $data = $request->all();

        $color = Color::find($id_color);

        $color->color_name = $data['color_name'];
        $color->save();

        Session::put('message', 'Cập nhật màu sản phẩm thành công !');
        return Redirect::to('/admin/product/color/all-product-color');
    }
    public function delete_color($id_color)
    {
        $this->AuthLogin();
        $color_id = Color::find($id_color);
        $color_id->delete();
        Session::put('message', 'Xóa màu sản phẩm thành công !');
        return Redirect::to('/admin/product/color/all-product-color');
    }

    //Variant
    public function add_variant()
    {
        $this->AuthLogin();
        $product = Product::all();
        $size    = Size::all();
        $color   = Color::all();
        return view('admin.product.product_variants.add')
            ->with('product', $product)
            ->with('size', $size)
            ->with('color', $color);
    }
    public function all_variant()
    {
        $this->AuthLogin();
        $product = Product::all();
        $size    = Size::all();
        $color   = Color::all();

        $all_variant     = Variant::all();
        $manager_variant = view('admin.product.product_variants.all')
            ->with('all_variant', $all_variant)
            ->with('product', $product)
            ->with('size', $size)
            ->with('color', $color);
        return view('admin_layout')->with('admin.product.product_variants.all_variant', $manager_variant);
    }
    public function save_variant(Request $request)
    {
        $this->AuthLogin();
        $data                = $request->all();
        $variant             = new Variant();
        $variant->product_id = $data['product'];
        $variant->size_id    = $data['size'];
        $variant->color_id   = $data['color'];
        $variant->stock      = $data['stock'];
        $variant->status     = $data['status'];
        $variant->save();

        Session::put('message', 'Thêm biến thể sản phẩm thành công !');
        return Redirect::to('/admin/product/product-variants/add-product-variant');
    }
    public function unactive_variant($id_variant)
    {
        $this->AuthLogin();
        $variant = Variant::find($id_variant);
        if ($variant) {
            $variant->update(['status' => 1]);
        }
        Session::put('message', 'Không kích hoạt biến thể sản phẩm thành công !');
        return Redirect::to('/admin/product/product-variants/all-product-variant');
    }
    public function active_variant($id_variant)
    {
        $this->AuthLogin();
        $variant = Variant::find($id_variant);
        if ($variant) {
            $variant->update(['status' => 0]);
        }
        Session::put('message', 'Kích hoạt biến thể sản phẩm thành công !');
        return Redirect::to('/admin/product/product-variants/all-product-variant');
    }
    public function edit_variant($id_variant)
    {
        $this->AuthLogin();
        $product         = Product::join('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')->get();
        $size            = Size::all();
        $color           = Color::all();
        $edit_variant    = Variant::find($id_variant);
        $manager_variant = view('admin.product.product_variants.edit')
            ->with('edit_variant', $edit_variant)
            ->with('product', $product)
            ->with('size', $size)
            ->with('color', $color);
        return view('admin_layout')->with('admin.product.product_variants.edit_variant', $manager_variant);
    }
    public function update_variant(Request $request, $id_variant)
    {
        $this->AuthLogin();

        $data    = $request->all();
        $variant = Variant::find($id_variant);

        $variant->product_id = $data['product'];
        $variant->size_id    = $data['size'];
        $variant->color_id   = $data['color'];
        $variant->stock      = $data['stock'];
        $variant->save();

        Session::put('message', 'Cập nhật biến thể sản phẩm thành công !');
        return Redirect::to('/admin/product/product-variants/all-product-variant');
    }
    public function delete_variant($id_variant)
    {
        $this->AuthLogin();
        $id_variant = Variant::find($id_variant);
        $id_variant->delete();
        Session::put('message', 'Xóa biến thể sản phẩm thành công !');
        return Redirect::to('/admin/product/product-variants/all-product-variant');
    }
}
