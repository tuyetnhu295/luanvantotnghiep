<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

// session_start();

class CartController extends Controller
{
    //
    public function save_cart(Request $request)
    {

        $quantity = $request->quantityInput;
        $size = $request->selected_size;
        $color = $request->selected_color;
        $product_id = $request->product_id;

        $variant_id = DB::table('tbl_product_variants')
            ->join('tbl_size_product', 'tbl_size_product.size_id', '=', 'tbl_product_variants.size_id')
            ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
            ->where('tbl_color_product.color_name', $color)
            ->where('tbl_product_variants.product_id', $product_id)
            ->where('tbl_size_product.size_name', $size)->first();

        $product_info = Product::Leftjoin('tbl_product_variants', 'tbl_product.product_id', '=', 'tbl_product_variants.product_id')
            ->where('tbl_product.product_id', $product_id)
            ->where('tbl_product_variants.variants_id', $variant_id->variants_id)->first();

        if (!$variant_id) {
            return redirect()->back()->with('error', 'Không tìm thấy biến thể sản phẩm với màu và kích cỡ đã chọn.');
        }




        $data['id'] = $product_info->variants_id;

        $data['qty'] = $quantity;

        $a=Variant::where('stock','<',$quantity)->first();
        if($a)
        {
            Session::put('error','So luong ton nho hon so luong dat');
            return Redirect()->back();
        }
        $data['name'] = $product_info->product_name;
        $data['price'] = $product_info->product_price;
        $data['options'] = [
            'image' => $product_info->product_image,
            'size' => $size,
            'color' => $color,
            'product_code' => $product_info->product_code,
        ];
        Session::get('variants_id', $product_info->variants_id);
        Cart::add($data);
        if ($request->has('buy_now')) {
            return redirect('/home/checkouts');
        }
        return Redirect::to('/home/pages/cart/cart');
    }

    public function show_cart(Request $request)
    {
        $cate_product = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();

        $product_relate = DB::table('tbl_product')
            ->inRandomOrder()
            ->get();
        return view(
            'pages.cart.cart',
            [
                'category' => $cate_product,
                'brand' => $brand_product,
                'subcategory' => $subcate_product,
                'product_relate' => $product_relate
            ]
        );
    }
    public function delete_cart($rowId)
    {
        $cate_product = DB::table('tbl_category_product')->orderBy('category_product_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand_product')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->orderBy('subcategory_product_id', 'desc')->get();
        $product_relate = DB::table('tbl_product')
            ->inRandomOrder()
            ->get();
        Cart::update($rowId, 0);
        return view(
            'pages.cart.cart',
            [
                'category' => $cate_product,
                'brand' => $brand_product,
                'subcategory' => $subcate_product,
                'product_relate' => $product_relate
            ]
        );
    }
    public function update_cart(Request $request)
    {
        $rowId = $request->rowId;
        $quantity = $request->quantity;
        $action = $request->input('action');

        if ($action == 'increase') {
            $quantity++;
        } elseif ($action === 'decrease') {
            if ($quantity > 1) {
                $quantity--;
            } else {
                Cart::update($rowId, 0);
                return Redirect::to('/home/pages/cart/cart');
            }
        }

        Cart::update($rowId, $quantity);
        return Redirect::to('/home/pages/cart/cart');
    }

    public function deleteAll()
    {
        Cart::destroy();
        return redirect()->back()->with('success', 'Đã xoá toàn bộ giỏ hàng');
    }
}
