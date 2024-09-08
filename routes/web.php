<?php

    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\AdminController;
    use App\Http\Middleware\AuthAdmin;
    use App\Http\Controllers\ShopController;
    use App\Http\Controllers\CartController;
    use App\Http\Controllers\WishlistController;

    Auth::routes();

    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    /* shop */
    Route::get("/shop", [ShopController::class, "index"])->name("shop.index");
    Route::get("/shop/{slug}", [ShopController::class, "product_detail"])->name("shop.product.detail");

    /* cart */
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart", [CartController::class, "add_to_cart"])->name("cart.add");
    Route::put("/cart/increase-quantity/{rowId}", [CartController::class, "increase_cart_quantity"])->name("cart.qty.increase");
    Route::put("/cart/decrease-quantity/{rowId}", [CartController::class, "decrease_cart_quantity"])->name("cart.qty.decrease");
    Route::delete("/cart/remove/{rowId}", [CartController::class, "remove_item"])->name("cart.remove");
    Route::delete("/cart/clear", [CartController::class, "empty_cart"])->name("cart.empty");
    Route::post("/cart/apply-coupon", [CartController::class, "apply_coupon_code"])->name("cart.coupon.apply");
    Route::delete("/cart/remove-coupon", [CartController::class, "remove_coupon_code"])->name("cart.coupon.remove");

    /* wishlist */
    Route::get("/wishlist", [WishlistController::class, "index"])->name("wishlist.index");
    Route::post("/wishlist", [WishlistController::class, 'add_to_wishlist'])->name("wishlist.add");
    Route::delete("/wishlist/remove/{rowId}", [WishlistController::class, "remove_item"])->name("wishlist.remove");
    Route::delete("/wishlist/clear", [WishlistController::class, "empty_wishlist"])->name("wishlist.empty");
    Route::post("/wishlist/move-to-cart/{rowId}", [WishlistController::class, "move_to_cart"])->name("wishlist.move.to.cart");

    /* checkout */
    Route::get('/checkout',[CartController::class,'checkout'])->name('cart.checkout');
    Route::post('/place-order',[CartController::class,'place_order'])->name('cart.place.order');
    Route::get('/order-confirmation',[CartController::class,'confirmation'])->name('cart.confirmation');

    Route::middleware(['auth'])->group(function () {
        Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
        Route::get('/account-orders',[UserController::class,'account_orders'])->name('user.account.orders');
        Route::get('/account-order-detials/{order_id}',[UserController::class,'account_order_details'])->name('user.acccount.order.details');
        Route::put('/account-order/cancel-order',[UserController::class,'account_cancel_order'])->name('user.account_cancel_order');

    });

    Route::middleware(['auth', AuthAdmin::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        /* brand */
        Route::get('/admin/brand', [AdminController::class, 'brand'])->name('admin.brand');
        Route::get('/admin/brand/add', [AdminController::class, 'brand_add'])->name('admin.brand.add');
        Route::get('/admin/brand/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
        Route::put('/admin/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
        Route::post('admin/brand/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
        Route::delete('admin/brand/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

        /* category */
        Route::get('/admin/category', [AdminController::class, 'category'])->name('admin.category');
        Route::get('/admin/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
        Route::post('admin/category/store', [AdminController::class, 'category_store'])->name('admin.category.store');
        Route::get('/admin/category/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
        Route::put('/admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
        Route::delete('admin/category/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

        /* product */
        Route::get('/admin/product', [AdminController::class, 'product'])->name('admin.product');
        Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
        Route::post('admin/product/store', [AdminController::class, 'product_store'])->name('admin.product.store');
        Route::get('/admin/product/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
        Route::put('/admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
        Route::delete('admin/product/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');

        /* coupon */
        Route::get('/admin/coupon', [AdminController::class, 'coupons'])->name('admin.coupon');
        Route::get('/admin/coupon/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
        Route::post('admin/coupon/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
        Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
        Route::put('/admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
        Route::delete('admin/coupon/delete/{id}', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

        /* orders */
        Route::get('/admin/orders',[AdminController::class,'orders'])->name('admin.orders');
        Route::get('/admin/order/items/{order_id}',[AdminController::class,'order_items'])->name('admin.order.items');
        Route::put('/admin/order/update-status',[AdminController::class,'update_order_status'])->name('admin.order.status.update');


        /* slides */
        Route::get('/admin/slides',[AdminController::class,'slides'])->name('admin.slides');
        Route::get('/admin/slide/add', [AdminController::class, 'slide_add'])->name('admin.slide.add');
        Route::post('admin/slide/store', [AdminController::class, 'slide_store'])->name('admin.slide.store');
        Route::get('/admin/slide/edit/{id}', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
        Route::put('/admin/slide/update', [AdminController::class, 'slide_update'])->name('admin.slide.update');
        Route::delete('admin/slide/delete/{id}', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');
    });
