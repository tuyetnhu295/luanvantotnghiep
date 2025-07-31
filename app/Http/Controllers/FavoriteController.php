<?php
namespace App\Http\Controllers;

use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{

    public function favorite($product)
    {
        $customer_id = Session::get('customer_id');

        if (! $customer_id) {
            return Redirect('/home/account/login')->with('error', 'Yêu cầu đăng nhập tài khoản');
        }

        $product_id = Product::find($product);
        if (! $product_id) {
            abort(404);
        }

        $tontai = DB::table('tbl_favorite_products')
            ->where('customer_id', $customer_id)
            ->where('product_id', $product_id->product_id)
            ->first();

        if ($tontai) {
            DB::table('tbl_favorite_products')->where('favorite_product_id', $tontai->favorite_product_id)->delete();
            return Redirect()->back()->with('error', 'Đã bỏ yêu thích');
        }

        $data = [];
        $data['customer_id'] = $customer_id;
        $data['product_id']  = $product_id->product_id;
        DB::table('tbl_favorite_products')->insert($data);
        return Redirect()->back()->with('message', 'Yêu thích thành công');
    }

    public function list()
    {
        $favorite = FavoriteProduct::join('tbl_product', 'tbl_product.product_id', '=', 'tbl_favorite_products.product_id')
            ->select('tbl_product.product_name', 'tbl_product.product_price', 'tbl_product.product_image', 'tbl_favorite_products.product_id', DB::raw('count(*) as total'))
            ->groupBy('tbl_favorite_products.product_id', 'tbl_product.product_name', 'tbl_product.product_price', 'tbl_product.product_image')
            ->get();

        return view('admin.favorite_product.list')
            ->with('favorite', $favorite);
    }
    public function product_details($id)
    {
        $product = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->first();
        return view('admin.favorite_product.product_details')
            ->with('product', $product);
    }
}
