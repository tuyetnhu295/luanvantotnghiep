<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

// session_start();

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();
        $customer        = Customer::all();
        $coupon          = Coupon::all();

        $customer_id = Session::get('customer_id');
        $order       = null;
        if ($customer_id) {
            $order = DB::table('tbl_order')
                ->where('customer_id', $customer_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Lấy sản phẩm
        $all_product = DB::table('tbl_product')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->where('product_status', 0)
            ->orderBy('tbl_product.product_id', 'desc')
            ->limit(18)
            ->get();

        foreach ($all_product as $product) {
            $product->variants = DB::table('tbl_product_variants')
                ->join('tbl_size_product', 'tbl_size_product.size_id', '=', 'tbl_product_variants.size_id')
                ->join('tbl_color_product', 'tbl_color_product.color_id', '=', 'tbl_product_variants.color_id')
                ->where('tbl_product_variants.product_id', $product->product_id)
                ->select('tbl_product_variants.*', 'tbl_size_product.size_name', 'tbl_color_product.color_name')
                ->get();
        }

        $products = Product::orderBy('total_sold', 'desc')->limit(6)->get();

        $now          = Carbon::now();
        $products_new = DB::table('tbl_product')
            ->where('product_status', '0')
            ->whereMonth('created_at', '6')
            ->whereYear('created_at', $now->year)
            ->orderBy('created_at', 'desc')->limit(6)->get();

        return view('pages.home')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('customer', $customer)
            ->with('coupon', $coupon)
            ->with('order', $order)
            ->with('all_product', $all_product)
            ->with('products', $products)
            ->with('products_new', $products_new);
    }

    public function all_product()
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $all_product = DB::table('tbl_product')->where('product_status', '0')->orderBy('product_id', 'desc')->paginate(4);
        return view('pages.all_product')->with('category', $cate_product)->with('brand', $brand_product)->with('subcategory', $subcate_product)->with('all_product', $all_product);
    }

    public function filter(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $product = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
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

        $all_product = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('pages.all_product')->with('category', $cate_product)->with('brand', $brand_product)->with('subcategory', $subcate_product)->with('all_product', $all_product);
    }

    public function best_selling()
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $all_product = DB::table('tbl_product')
            ->where('product_status', '0')
            ->orderBy('total_sold', 'desc')->paginate(4);

        return view('pages.best_selling')
            ->with('all_product', $all_product)
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product);
    }

    public function best_selling_filter(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $product = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->orderBy('total_sold', 'desc');
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
        $all                 = $product->get()->unique('product_name');
        $page                = $request->input('page', 1);
        $perPage             = 4;
        $offset              = ($page - 1) * $perPage;
        $itemsForCurrentPage = $all->slice($offset, $perPage);

        $all_product = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('pages.best_selling')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product);
    }
    public function search(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $keyword = $request->keyword;

        $search_product = DB::table('tbl_product')->where('product_name', 'like', '%' . $keyword . '%')->get();

        return view('pages.product.search')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('product', $search_product);
    }

    public function sendmail()
    {
        $to_name  = "Tuyet Nhu";
        $to_email = "naruto25864@gmail.com";

        $data = ["name" => "Mail từ tài khoản khách hàng", "body" => "Mail gửi về vấn đề hàng hóa"];

        Mail::send('pages.login.send_mail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email)->subject('Test thu gui mail google');
            $message->from($to_email, $to_name);
        });
    }

    public function new_product()
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();

        $now         = Carbon::now();
        $all_product = DB::table('tbl_product')
            ->where('product_status', '0')
            ->whereMonth('created_at', '6')
            ->whereYear('created_at', $now->year)
            ->orderBy('created_at', 'desc')->paginate(4);

        return view('pages.new_product')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product);
    }
    public function new_product_filter(Request $request)
    {
        $cate_product    = DB::table('tbl_category_product')->where('category_product_status', '0')->orderBy('category_product_id', 'desc')->get();
        $brand_product   = DB::table('tbl_brand_product')->where('brand_product_status', '0')->orderBy('brand_product_id', 'desc')->get();
        $subcate_product = DB::table('tbl_subcategory_product')->where('subcategory_product_status', '0')->orderBy('subcategory_product_id', 'desc')->get();
        $now             = Carbon::now();
        $product         = DB::table('tbl_product')
            ->join('tbl_brand_product', 'tbl_brand_product.brand_product_id', '=', 'tbl_product.brand_product_id')
            ->join('tbl_subcategory_product', 'tbl_subcategory_product.subcategory_product_id', '=', 'tbl_product.subcategory_product_id')
            ->leftJoin('tbl_product_variants', 'tbl_product_variants.product_id', '=', 'tbl_product.product_id')
            ->where('product_status', '0')
            ->whereMonth('created_at', '6')
            ->whereYear('created_at', $now->year)
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
        $all                 = $product->get()->unique('product_name')->values();
        $page                = $request->input('page', 1);
        $perPage             = 4;
        $offset              = ($page - 1) * $perPage;
        $itemsForCurrentPage = $all->slice($offset, $perPage);

        $all_product = new LengthAwarePaginator(
            $itemsForCurrentPage,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('pages.best_selling')
            ->with('category', $cate_product)
            ->with('brand', $brand_product)
            ->with('subcategory', $subcate_product)
            ->with('all_product', $all_product);
    }
    public function favorite($product)
    {

        $customer_id = Session::get('customer_id');

        if (! $customer_id) {
            return Redirect()->back()->with('error', 'Yêu cầu đăng nhập tài khoản');
        }

        $products = Product::where('slug_product', $product)->first();
        if (! $products) {
            return Redirect()->back()->with('error', 'Sản phẩm không tồn tại');
        }

        $tontai = DB::table('tbl_favorite_products')
            ->where('customer_id', $customer_id)
            ->where('product_id', $products->product_id)
            ->first();

        if ($tontai) {
            DB::table('tbl_favorite_products')->where('favorite_product_id', $tontai->favorite_product_id)->delete();
            return Redirect()->back()->with('error', 'Đã bỏ yêu thích');
        }

        $data                = [];
        $data['customer_id'] = $customer_id;
        $data['product_id']  = $products->product_id;
        DB::table('tbl_favorite_products')->insert($data);

        return Redirect()->back()->with('success', 'Yêu thích thành công');
    }
}
