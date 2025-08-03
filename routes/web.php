<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandProduct;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryProduct;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImages;
use App\Http\Controllers\SubcategoryProduct;
use Illuminate\Support\Facades\Route;

// fontend
Route::prefix('home')->group(function () {
    Route::match(['get', 'post'], '/', [HomeController::class, 'index']);
    Route::post('/search', [HomeController::class, 'search']);
    Route::post('/products/favorite/{product}', [HomeController::class, 'favorite']);

    // danh muc san pham trang chu
    Route::get('/pages/all-product', [HomeController::class, 'all_product']);
    Route::get('/pages/all-product/filter', [HomeController::class, 'filter']);
    Route::get('/pages/all-product/load-more-products', [HomeController::class, 'loadMoreProducts']);

    Route::get('/pages/best-selling', [HomeController::class, 'best_selling']);
    Route::get('/pages/best-selling/filter', [HomeController::class, 'best_selling_filter']);

    Route::get('/pages/new-products', [HomeController::class, 'new_product']);
    Route::get('/pages/new-products/filter', [HomeController::class, 'new_product_filter']);

    Route::get('/pages/category/category-product/{slug}', [CategoryProduct::class, 'category_product']);
    Route::get('/pages/category/category-product/{slug}/filter', [CategoryProduct::class, 'filter']);

    Route::get('/pages/brand/brand-product/{slug}', [BrandProduct::class, 'brand_product']);
    Route::get('/pages/brand/brand-product/{slug}/filter', [BrandProduct::class, 'filter']);

    Route::get('/pages/subcategory/subcategory-product/{slug}', [SubcategoryProduct::class, 'subcategory_product']);
    Route::get('/pages/subcategory/subcategory-product/{slug}/filter', [SubcategoryProduct::class, 'filter']);

    Route::get('/pages/product/detail-product/{slug}', [ProductController::class, 'detail_product']);

    //Gio hang
    Route::post('/pages/cart/save-cart', [CartController::class, 'save_cart']);
    Route::get('/pages/cart/cart', [CartController::class, 'show_cart']);
    Route::get('/pages/cart/delete-cart/{rowId}', [CartController::class, 'delete_cart']);
    Route::post('/pages/cart/update-cart', [CartController::class, 'update_cart']);

    Route::post('/pages/cart/deleteAll', [CartController::class, 'deleteAll']);

    //Login
    Route::get('/account/login', [CheckoutController::class, 'login_checkout']);
    Route::get('/account/register', [CustomerController::class, 'register']);
    Route::post('/account/add-customer', [CustomerController::class, 'add_customer']);
    Route::post('/account/login-customer', [CustomerController::class, 'login_customer']);
    Route::get('/logout', [CustomerController::class, 'logout']);
    Route::get('/account/forget-password', [CustomerController::class, 'forget_password']);
    Route::post('/account/password-email', [CustomerController::class, 'password_email']);

    //Checkout
    Route::get('/checkouts', [CheckoutController::class, 'checkout']);
    Route::post('/save-checkout-customer', [CheckoutController::class, 'save_checkout_customer']);
    Route::post('/save-note-coupon', [CheckoutController::class, 'save_note_coupon']);
    Route::get('/payment', [CheckoutController::class, 'payment']);

    Route::post('/place-order', [CheckoutController::class, 'place_order']);

    Route::get('/place-order/vnpay', [CheckoutController::class, 'showPaymentPage']);
    Route::post('/place-order/vnpay/create', [CheckoutController::class, 'vnpay_payment'])->name('vnpay.create');
    Route::get('/place-order/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');

    Route::post('/checkouts/apply-coupon', [CheckoutController::class, 'apply_coupon']);
    Route::post('/place-order/delete-coupon', [CheckoutController::class, 'delete_coupon']);

    //Address
    Route::get('/checkouts/dia-chi/tinh', [AddressController::class, 'getTinhTp']);
    Route::get('/checkouts/dia-chi/quan/{tinh_code}', [AddressController::class, 'getQuanHuyen']);
    Route::get('/checkouts/dia-chi/xa/{quan_code}', [AddressController::class, 'getXaPhuong']);

    Route::get('/dia-chi/tinh', [AddressController::class, 'TinhTp']);
    Route::get('/dia-chi/quan/{tinh_code}', [AddressController::class, 'QuanHuyen']);
    Route::get('/dia-chi/xa/{quan_code}', [AddressController::class, 'XaPhuong']);

    Route::get('/cart/dia-chi/tinh', [AddressController::class, 'cart_getTinhTp']);
    Route::get('/cart/dia-chi/quan/{tinh_code}', [AddressController::class, 'cart_getQuanHuyen']);

    //Customer
    Route::get('/account/info/profile', [CustomerController::class, 'info']);
    Route::get('/account/info/change-password', [CustomerController::class, 'change_password']);
    Route::get('/account/info/my-order', [CustomerController::class, 'my_order']);
    Route::get('/account/info/coupons', [CustomerController::class, 'coupons']);
    Route::get('/account/info/favorite-product', [CustomerController::class, 'favorite_product']);
    Route::post('/account/info/save-change-password', [CustomerController::class, 'save_change_password']);
    Route::post('/account/info/save-info', [CustomerController::class, 'save_info']);
    Route::get('/account/info/my-order/cancel-order/{order}', [CustomerController::class, 'cancel_order']);
    Route::post('/account/info/my-order/cancel-order/submit/{order}', [CustomerController::class, 'cancel_submit']);

    Route::get('/account/info/my-order/confirm/{order}', [CustomerController::class, 'confirm']);

    Route::get('/account/info/my-order/return/{code}', [CustomerController::class, 'showReturnForm']);
    Route::post('/account/info/my-order/submit-return/{code}', [CustomerController::class, 'submitReturn']);

    Route::get('/account/info/my-order/return-items/{code}', [CustomerController::class, 'showReturnItems']);
    Route::get('/account/info/my-order/order-details/{code}', [CustomerController::class, 'showOrderDetails']);

    Route::get('/account/info/help', [CustomerController::class, 'help']);
    Route::post('/account/info/help/delete-request', [CustomerController::class, 'requestDeleteAccount']);

    Route::get('/account/info/favorite-product/filter', [CustomerController::class, 'filter']);

    Route::get('/unloved-product/{id}', [CustomerController::class, 'unloved']);
    //Send mail
    Route::get('/send-mail', [HomeController::class, 'sendmail']);

    //Comments
    Route::post('/comments', [CommentController::class, 'comment']);

    //Favorite

    Route::post('/favorite/{product}', [FavoriteController::class, 'favorite']);
});

// backend

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('login');
    Route::get('/register', [AdminController::class, 'register']);
    Route::get('/forget-password', [AdminController::class, 'forget_password']);
    Route::post('/forget-password/password-email', [AdminController::class, 'forget_password_email']);
    Route::post('/register/add-employee', [AdminController::class, 'add']);
    Route::post('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/info', [AdminController::class, 'info']);
    Route::post('/edit-info/{id}', [AdminController::class, 'save']);
});

//cho phép toàn bộ roles truy cập
Route::prefix('admin')->middleware(['role:superadmin,manager,staff,shipper'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
    Route::get('/logout', [AdminController::class, 'logout']);
});

Route::prefix('admin')->middleware(['role:superadmin,manager,staff'])->group(function () {
    Route::get('/favorite-products', [FavoriteController::class, 'list']);
    Route::get('/favorite-products/product-details/{id}', [FavoriteController::class, 'product_details']);
});

//Cho phép role manage quản lý
Route::prefix('admin')->middleware(['role:manager,superadmin'])->group(function () {
    //ParentCategory Product
    Route::get('/add-category-product', [CategoryProduct::class, 'add_category_product']);
    Route::get('/all-category-product', [CategoryProduct::class, 'all_category_product']);
    Route::post('/save-category-product', [CategoryProduct::class, 'save_category_product']);
    Route::post('/update-category-product/{category_product_id}', [CategoryProduct::class, 'update_category_product']);
    Route::get('/all-category-product/edit-category-product/{category_product_id}', [CategoryProduct::class, 'edit_category_product']);
    Route::get('/all-category-product/delete-category-product/{category_product_id}', [CategoryProduct::class, 'delete_category_product']);
    Route::get('/all-category-product/unactive-category-product/{category_product_id}', [CategoryProduct::class, 'unactive_category_product']);
    Route::get('/all-category-product/active-category-product/{category_product_id}', [CategoryProduct::class, 'active_category_product']);

    //SubCategory Product
    Route::get('/add-subcategory-product', [SubcategoryProduct::class, 'add_subcategory_product']);
    Route::get('/all-subcategory-product', [SubcategoryProduct::class, 'all_subcategory_product']);
    Route::post('/save-subcategory-product', [SubcategoryProduct::class, 'save_subcategory_product']);
    Route::post('/update-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'update_subcategory_product']);
    Route::get('/all-subcategory-product/edit-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'edit_subcategory_product']);
    Route::get('/all-subcategory-product/delete-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'delete_subcategory_product']);
    Route::get('/all-subcategory-product/unactive-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'unactive_subcategory_product']);
    Route::get('/all-subcategory-product/active-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'active_subcategory_product']);

    //Brand Product
    Route::get('/add-brand-product', [BrandProduct::class, 'add_brand_product']);
    Route::get('/all-brand-product', [BrandProduct::class, 'all_brand_product']);
    Route::post('/save-brand-product', [BrandProduct::class, 'save_brand_product']);
    Route::post('/update-brand-product/{brand_product_id}', [BrandProduct::class, 'update_brand_product']);
    Route::get('/all-brand-product/edit-brand-product/{brand_product_id}', [BrandProduct::class, 'edit_brand_product']);
    Route::get('/all-brand-product/delete-brand-product/{brand_product_id}', [BrandProduct::class, 'delete_brand_product']);
    Route::get('/all-brand-product/unactive-brand-product/{brand_product_id}', [BrandProduct::class, 'unactive_brand_product']);
    Route::get('/all-brand-product/active-brand-product/{brand_product_id}', [BrandProduct::class, 'active_brand_product']);

    //Product
    Route::get('/add-product', [ProductController::class, 'add_product']);
    Route::get('/all-product', [ProductController::class, 'all_product']);
    Route::post('/save-product', [ProductController::class, 'save_product']);
    Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);
    Route::get('/all-product/edit-product/{product_id}', [ProductController::class, 'edit_product']);
    Route::get('/all-product/delete-product/{product_id}', [ProductController::class, 'delete_product']);
    Route::get('/all-product/unactive-product/{product_id}', [ProductController::class, 'unactive_product']);
    Route::get('/all-product/active-product/{product_id}', [ProductController::class, 'active_product']);
    Route::get('/get-subcategories/{id}', [ProductController::class, 'getSubcategories']);

    // Images Product

    Route::get('/add-product-images', [ProductImages::class, 'add']);
    Route::get('/all-product-images', [ProductImages::class, 'all']);
    Route::post('/save-images', [ProductImages::class, 'save']);
    Route::post('/update/{id}', [ProductImages::class, 'update']);
    Route::get('/all-product-images/edit/{id}', [ProductImages::class, 'edit']);
    Route::get('/all-product-images/delete/{id}', [ProductImages::class, 'delete']);
    Route::get('/all-product-images/unactive/{id}', [ProductImages::class, 'unactive']);
    Route::get('/all-product-images/active/{id}', [ProductImages::class, 'active']);
    Route::get('/get-product-colors/{product_id}', [ProductImages::class, 'getColors']);

    //Size
    Route::get('/product/size/add-product-size', [ProductController::class, 'add_size']);
    Route::get('/product/size/all-product-size', [ProductController::class, 'all_size']);
    Route::post('/product/size/save-size', [ProductController::class, 'save_size']);
    Route::post('/product/size/update-size/{id_size}', [ProductController::class, 'update_size']);
    Route::get('/product/size/all-product-size/edit-size/{id_size}', [ProductController::class, 'edit_size']);
    Route::get('/product/size/all-product-size/delete-size/{id_size}', [ProductController::class, 'delete_size']);
    Route::get('/product/size/all-product-size/unactive-size/{id_size}', [ProductController::class, 'unactive_size']);
    Route::get('/product/size/all-product-size/active-size/{id_size}', [ProductController::class, 'active_size']);

    //Color
    Route::get('/product/color/add-product-color', [ProductController::class, 'add_color']);
    Route::get('/product/color/all-product-color', [ProductController::class, 'all_color']);
    Route::post('/product/color/save-color', [ProductController::class, 'save_color']);
    Route::post('/product/color/update-color/{id_color}', [ProductController::class, 'update_color']);
    Route::get('/product/color/all-product-color/edit-color/{id_color}', [ProductController::class, 'edit_color']);
    Route::get('/product/color/all-product-color/delete-color/{id_color}', [ProductController::class, 'delete_color']);
    Route::get('/product/color/all-product-color/unactive-color/{id_color}', [ProductController::class, 'unactive_color']);
    Route::get('/product/color/all-product-color/active-color/{id_color}', [ProductController::class, 'active_color']);

    //Variants
    Route::get('/product/product-variants/add-product-variant', [ProductController::class, 'add_variant']);
    Route::get('/product/product-variants/all-product-variant', [ProductController::class, 'all_variant']);
    Route::post('/product/product-variants/save-variant', [ProductController::class, 'save_variant']);
    Route::post('/product/product-variants/update-variant/{id_variant}', [ProductController::class, 'update_variant']);
    Route::get('/product/product-variants/all-product-variant/edit-variant/{id_variant}', [ProductController::class, 'edit_variant']);
    Route::get('/product/product-variants/all-product-variant/delete-variant/{id_variant}', [ProductController::class, 'delete_variant']);
    Route::get('/product/product-variants/all-product-variant/unactive-variant/{id_variant}', [ProductController::class, 'unactive_variant']);
    Route::get('/product/product-variants/all-product-variant/active-variant/{id_variant}', [ProductController::class, 'active_variant']);

    //Order
    Route::get('/order/manage-order', [CheckoutController::class, 'manage_order']);
    Route::get('/order/manage-order/view-order/{order_id}', [CheckoutController::class, 'view_order']);

    Route::get('/order/manage-order-returns', [OrderController::class, 'manage_order_returns']);
    Route::get('/order/manage-order-returns/view-order-returns/{return_code}', [OrderController::class, 'view_order_returns']);
    Route::get('/order/manage-order-returns/edit-order-returns/{return_code}', [OrderController::class, 'edit_order_returns']);
    Route::post('/order/manage-order-returns/update-order-returns/{return_code}', [OrderController::class, 'update_order_returns']);

    Route::get('/print-order/{checkout_code}', [OrderController::class, 'print_order']);

    Route::get('/order/manage-order/edit-order/{order_id}', [CheckoutController::class, 'edit_order']);
    Route::post('/order/manage-order/update-order/{order_id}', [CheckoutController::class, 'update_order']);

    //Coupon
    Route::get('/add-coupon', [CouponController::class, 'add_coupon']);
    Route::post('/coupon/save-coupon', [CouponController::class, 'save_coupon']);

    Route::get('/all-coupon', [CouponController::class, 'all_coupon']);
    Route::get('/coupon/delete-coupon/{coupon_id}', [CouponController::class, 'delete_coupon']);
});

//Cho phép role staff quản lý
Route::prefix('admin')->middleware(['role:staff,superadmin'])->group(function () {

    //Order
    Route::get('/order/manage-order', [CheckoutController::class, 'manage_order']);
    Route::get('/order/manage-order/view-order/{order_id}', [CheckoutController::class, 'view_order']);
    Route::get('/order/manage-order-returns', [OrderController::class, 'manage_order_returns']);
    Route::get('/order/manage-order-returns/view-order-returns/{return_code}', [OrderController::class, 'view_order_returns']);
    // Route::get('/order/manage-order-returns/edit-order-returns/{return_code}', [OrderController::class, 'edit_order_returns']);
    // Route::post('/order/manage-order-returns/update-order-returns/{return_code}', [OrderController::class, 'update_order_returns']);
    Route::get('/print-order/{checkout_code}', [OrderController::class, 'print_order']);
    // Route::get('/order/manage-order/edit-order/{order_id}', [CheckoutController::class, 'edit_order']);
    // Route::post('/order/manage-order/update-order/{order_id}', [CheckoutController::class, 'update_order']);

    //Comments

    Route::get('/comments', [CommentController::class, 'list_comment']);
    Route::get('/comments/update/{comment_id}', [CommentController::class, 'update']);
    Route::post('/comments/update-comment/{comment_id}', [CommentController::class, 'update_comment']);
});
//Cho phép role shipper quản lý
Route::prefix('admin')->middleware(['role:shipper,superadmin'])->group(function () {

    //Shipper
    Route::get('/delivery/orders', [OrderController::class, 'order_shipper']);
    Route::get('/order/manage-order/view-order/{order_id}', [CheckoutController::class, 'view_order']);
    Route::get('/delivery/orders/edit-order/{order_id}', [OrderController::class, 'edit_order']);
    Route::post('/delivery/orders/update-order/{order_id}', [OrderController::class, 'update_order']);
});

//Cho phép role superadmin quản lý
Route::prefix('admin')->middleware(['role:superadmin'])->group(function () {

    //Admin
    Route::get('/staffs/create', [AdminController::class, 'create']);
    Route::get('/staffs', [AdminController::class, 'staffs']);
    Route::post('/staffs/save-admin', [AdminController::class, 'save_staff']);
    Route::get('/staffs/edit/{admin}', [AdminController::class, 'edit']);
    Route::post('/staffs/update/{admin}', [AdminController::class, 'update']);
    Route::get('/staffs/delete/{admin}', [AdminController::class, 'delete']);
    Route::get('/staffs/lock/{admin}', [AdminController::class, 'lock']);
    Route::get('/staffs/unlock/{admin}', [AdminController::class, 'unlock']);
    Route::post('/staffs/assign-role/{admin}', [AdminController::class, 'assign_role']);

    //Customer
    Route::get('/customers', [CustomerController::class, 'list']);
    Route::get('/customers/view/{customer}', [CustomerController::class, 'view']);
    Route::get('/customers/delete/{customer_id}', [CustomerController::class, 'delete']);

    Route::get('/orders-return/detail/{order_code}', [CustomerController::class, 'return_details']);
    Route::get('/orders/detail/{order_code}', [CustomerController::class, 'order_details']);
});

// Route::prefix('admin')->group(function () {
//     Route::get('/', [AdminController::class, 'index']);
//     Route::get('/dashboard', [AdminController::class, 'show_dashboard']);
//     Route::get('/logout', [AdminController::class, 'logout']);
//     Route::post('/dashboard', [AdminController::class, 'dashboard']);

//     //ParentCategory Product
//     Route::get('/add-category-product', [CategoryProduct::class, 'add_category_product']);
//     Route::get('/all-category-product', [CategoryProduct::class, 'all_category_product']);
//     Route::post('/save-category-product', [CategoryProduct::class, 'save_category_product']);
//     Route::post('/update-category-product/{category_product_id}', [CategoryProduct::class, 'update_category_product']);
//     Route::get('/all-category-product/edit-category-product/{category_product_id}', [CategoryProduct::class, 'edit_category_product']);
//     Route::get('/all-category-product/delete-category-product/{category_product_id}', [CategoryProduct::class, 'delete_category_product']);
//     Route::get('/all-category-product/unactive-category-product/{category_product_id}', [CategoryProduct::class, 'unactive_category_product']);
//     Route::get('/all-category-product/active-category-product/{category_product_id}', [CategoryProduct::class, 'active_category_product']);

//     //SubCategory Product
//     Route::get('/add-subcategory-product', [SubcategoryProduct::class, 'add_subcategory_product']);
//     Route::get('/all-subcategory-product', [SubcategoryProduct::class, 'all_subcategory_product']);
//     Route::post('/save-subcategory-product', [SubcategoryProduct::class, 'save_subcategory_product']);
//     Route::post('/update-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'update_subcategory_product']);
//     Route::get('/all-subcategory-product/edit-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'edit_subcategory_product']);
//     Route::get('/all-subcategory-product/delete-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'delete_subcategory_product']);
//     Route::get('/all-subcategory-product/unactive-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'unactive_subcategory_product']);
//     Route::get('/all-subcategory-product/active-subcategory-product/{subcategory_product_id}', [SubcategoryProduct::class, 'active_subcategory_product']);

//     //Brand Product
//     Route::get('/add-brand-product', [BrandProduct::class, 'add_brand_product']);
//     Route::get('/all-brand-product', [BrandProduct::class, 'all_brand_product']);
//     Route::post('/save-brand-product', [BrandProduct::class, 'save_brand_product']);
//     Route::post('/update-brand-product/{brand_product_id}', [BrandProduct::class, 'update_brand_product']);
//     Route::get('/all-brand-product/edit-brand-product/{brand_product_id}', [BrandProduct::class, 'edit_brand_product']);
//     Route::get('/all-brand-product/delete-brand-product/{brand_product_id}', [BrandProduct::class, 'delete_brand_product']);
//     Route::get('/all-brand-product/unactive-brand-product/{brand_product_id}', [BrandProduct::class, 'unactive_brand_product']);
//     Route::get('/all-brand-product/active-brand-product/{brand_product_id}', [BrandProduct::class, 'active_brand_product']);

//     //Product
//     Route::get('/add-product', [ProductController::class, 'add_product']);
//     Route::get('/all-product', [ProductController::class, 'all_product']);
//     Route::post('/save-product', [ProductController::class, 'save_product']);
//     Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);
//     Route::get('/all-product/edit-product/{product_id}', [ProductController::class, 'edit_product']);
//     Route::get('/all-product/delete-product/{product_id}', [ProductController::class, 'delete_product']);
//     Route::get('/all-product/unactive-product/{product_id}', [ProductController::class, 'unactive_product']);
//     Route::get('/all-product/active-product/{product_id}', [ProductController::class, 'active_product']);

//     Route::get('/get-subcategories/{id}', [ProductController::class, 'getSubcategories']);

//     // Images Product

//     Route::get('/add-product-images', [ProductImages::class, 'add']);
//     Route::get('/all-product-images', [ProductImages::class, 'all']);
//     Route::post('/save-images', [ProductImages::class, 'save']);
//     Route::post('/update/{id}', [ProductImages::class, 'update']);
//     Route::get('/all-product-images/edit/{id}', [ProductImages::class, 'edit']);
//     Route::get('/all-product-images/delete/{id}', [ProductImages::class, 'delete']);
//     Route::get('/all-product-images/unactive/{id}', [ProductImages::class, 'unactive']);
//     Route::get('/all-product-images/active/{id}', [ProductImages::class, 'active']);

//     Route::get('/get-product-colors/{product_id}', [ProductImages::class, 'getColors']);

//     //Size
//     Route::get('/product/size/add-product-size', [ProductController::class, 'add_size']);
//     Route::get('/product/size/all-product-size', [ProductController::class, 'all_size']);
//     Route::post('/product/size/save-size', [ProductController::class, 'save_size']);
//     Route::post('/product/size/update-size/{id_size}', [ProductController::class, 'update_size']);
//     Route::get('/product/size/all-product-size/edit-size/{id_size}', [ProductController::class, 'edit_size']);
//     Route::get('/product/size/all-product-size/delete-size/{id_size}', [ProductController::class, 'delete_size']);
//     Route::get('/product/size/all-product-size/unactive-size/{id_size}', [ProductController::class, 'unactive_size']);
//     Route::get('/product/size/all-product-size/active-size/{id_size}', [ProductController::class, 'active_size']);

//     //Color
//     Route::get('/product/color/add-product-color', [ProductController::class, 'add_color']);
//     Route::get('/product/color/all-product-color', [ProductController::class, 'all_color']);
//     Route::post('/product/color/save-color', [ProductController::class, 'save_color']);
//     Route::post('/product/color/update-color/{id_color}', [ProductController::class, 'update_color']);
//     Route::get('/product/color/all-product-color/edit-color/{id_color}', [ProductController::class, 'edit_color']);
//     Route::get('/product/color/all-product-color/delete-color/{id_color}', [ProductController::class, 'delete_color']);
//     Route::get('/product/color/all-product-color/unactive-color/{id_color}', [ProductController::class, 'unactive_color']);
//     Route::get('/product/color/all-product-color/active-color/{id_color}', [ProductController::class, 'active_color']);

//     //Variants
//     Route::get('/product/product-variants/add-product-variant', [ProductController::class, 'add_variant']);
//     Route::get('/product/product-variants/all-product-variant', [ProductController::class, 'all_variant']);
//     Route::post('/product/product-variants/save-variant', [ProductController::class, 'save_variant']);
//     Route::post('/product/product-variants/update-variant/{id_variant}', [ProductController::class, 'update_variant']);
//     Route::get('/product/product-variants/all-product-variant/edit-variant/{id_variant}', [ProductController::class, 'edit_variant']);
//     Route::get('/product/product-variants/all-product-variant/delete-variant/{id_variant}', [ProductController::class, 'delete_variant']);
//     Route::get('/product/product-variants/all-product-variant/unactive-variant/{id_variant}', [ProductController::class, 'unactive_variant']);
//     Route::get('/product/product-variants/all-product-variant/active-variant/{id_variant}', [ProductController::class, 'active_variant']);

//     //Order
//     Route::get('/order/manage-order', [CheckoutController::class, 'manage_order']);
//     Route::get('/order/manage-order/view-order/{order_id}', [CheckoutController::class, 'view_order']);

//     Route::get('/order/manage-order-returns', [OrderController::class, 'manage_order_returns']);
//     Route::get('/order/manage-order-returns/view-order-returns/{return_code}', [OrderController::class, 'view_order_returns']);
//     Route::get('/order/manage-order-returns/edit-order-returns/{return_code}', [OrderController::class, 'edit_order_returns']);
//     Route::post('/order/manage-order-returns/update-order-returns/{return_code}', [OrderController::class, 'update_order_returns']);

//     Route::get('/print-order/{checkout_code}', [OrderController::class, 'print_order']);

//     Route::get('/order/manage-order/edit-order/{order_id}', [CheckoutController::class, 'edit_order']);
//     Route::post('/order/manage-order/update-order/{order_id}', [CheckoutController::class, 'update_order']);

//     //Coupon
//     Route::get('/add-coupon', [CouponController::class, 'add_coupon']);
//     Route::post('/coupon/save-coupon', [CouponController::class, 'save_coupon']);

//     Route::get('/all-coupon', [CouponController::class, 'all_coupon']);
//     Route::get('/coupon/delete-coupon/{coupon_id}', [CouponController::class, 'delete_coupon']);

//     //Admin
//     Route::get('/staffs/create', [AdminController::class, 'create']);
//     Route::get('/staffs', [AdminController::class, 'staffs']);
//     Route::post('/staffs/save-admin', [AdminController::class, 'save_staff']);
//     Route::get('/staffs/edit/{admin}', [AdminController::class, 'edit']);
//     Route::post('/staffs/update/{admin}', [AdminController::class, 'update']);
//     Route::get('/staffs/delete/{admin}', [AdminController::class, 'delete']);
//     Route::get('/staffs/lock/{admin}', [AdminController::class, 'lock']);
//     Route::get('/staffs/unlock/{admin}', [AdminController::class, 'unlock']);

//     //Customer
//     Route::get('/customers', [CustomerController::class, 'list']);
//     Route::get('/customers/view/{customer}', [CustomerController::class, 'view']);
//     Route::get('/customers/delete/{customer_id}', [CustomerController::class, 'delete']);

//     Route::get('/orders-return/detail/{order_code}', [CustomerController::class, 'return_details']);
//     Route::get('/orders/detail/{order_code}', [CustomerController::class, 'order_details']);

//     //Comments

//     Route::get('/comments', [CommentController::class, 'list_comment']);
//     Route::get('/comments/update/{comment_id}', [CommentController::class, 'update']);
//     Route::post('/comments/update-comment/{comment_id}', [CommentController::class, 'update_comment']);
// });
