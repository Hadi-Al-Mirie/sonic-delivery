<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;


/*                                        AUTH                                           */
Route::post('login', LoginController::class)->name('login');
Route::post('verify', [VerificationController::class, 'verify'])->name('verify');
Route::middleware(['throttle:auth'])->group(function () {
    Route::post('register', RegisterController::class)->name('register');
    Route::post('resend', [VerificationController::class, 'resendCode'])->name('resendCode');
});


Route::middleware('auth:sanctum')->group(function () {

    /*                                        STORES                                          */
    Route::get('/stores/{id}/products', [StoreController::class, 'getProducts']);
    Route::get('/stores/all', [StoreController::class, 'getAllStores']);


    /*                                        PRODUCTS                                        */
    Route::get('/products/all', [ProductController::class, 'getAllProducts']);
    Route::get('/products/{id}/details', [ProductController::class, 'getDetails']);


    /*                                        FAVORITES                                       */
    Route::get('/products/{id}/add-to-favorite', [FavoriteController::class, 'addToFavorite']);
    Route::get('/products/{id}/delete-from-favorite', [FavoriteController::class, 'deleteFromFavorite']);
    Route::get('/favorites/get-all', [FavoriteController::class, 'getFavorites']);


    /*                                        CARTS                                           */
    Route::get('/products/{id}/add-to-cart', [CartController::class, 'addToCart']);
    Route::get('/products/{id}/delete-from-cart', [CartController::class, 'deleteFromCart']);
    Route::get('carts/get-all', action: [CartController::class, 'getCarts']);


    /*                                        ORDERS                                           */
    Route::post('/orders/add-order', [OrderController::class, 'addOrder']);
    Route::get('/orders/{id}/cancel-order', [OrderController::class, 'cancelOrder']);
    Route::get('/orders/get-all', [OrderController::class, 'getOrders']);
    Route::get('/orders/{id}/get-items', [OrderController::class, 'getItems']);

    /*                                        USER                                            */
    Route::post('/users/profile/update', [UserController::class, 'updateProfile']);


    /*                                        SEARCH                                          */
    Route::post('/search', SearchController::class);


    Route::post('/logout', LogoutController::class);

});
/***************************************************************************************/
