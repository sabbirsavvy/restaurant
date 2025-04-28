<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// ✅ User Dashboard
Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// routes/web.php

Route::get('/dashboard/reservations', [ReservationController::class, 'userReservations'])->name('users.reservations');
Route::get('/users/orders', [OrderController::class, 'userOrders'])->name('users.orders');
Route::get('/user/orders/{order}', [OrderController::class, 'viewUserOrder'])->name('user.order.view');
Route::get('/user/orders/{orderId}', [OrderController::class, 'userViewDetails'])->name('users.viewdetails');
Route::get('/user/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('users.invoice');



Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
Route::post('/admin/settings', [AdminController::class, 'saveSettings'])->name('admin.settings.save');
Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
Route::post('/admin/settings/save', [AdminController::class, 'saveSettings'])->name('admin.settings.save');



// ✅ Admin Routes (Protected by 'admin' middleware)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // ✅ Menu Management
    Route::get('/menu', [AdminController::class, 'manageMenu'])->name('admin.menu'); 
    Route::get('/menu/create', [AdminController::class, 'createMenuItem'])->name('admin.menu.create');
    Route::post('/menu/store', [AdminController::class, 'storeMenuItem'])->name('admin.menu.store');
    Route::get('/menu/{id}/edit', [AdminController::class, 'editMenuItem'])->name('admin.menu.edit');
    Route::post('/menu/{id}/update', [AdminController::class, 'updateMenuItem'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [AdminController::class, 'destroyMenuItem'])->name('admin.menu.destroy');

    // ✅ Reservations Management
    Route::get('/reservations', [AdminController::class, 'viewReservations'])->name('admin.reservations');
    Route::get('/reservations/accept/{id}', [AdminController::class, 'acceptReservation'])->name('admin.reservations.accept');
    Route::get('/reservations/decline/{id}', [AdminController::class, 'declineReservation'])->name('admin.reservations.decline');

    // ✅ Orders Management
    Route::get('/orders', [AdminController::class, 'viewOrders'])->name('admin.orders');
    Route::get('/orders/accept/{id}', [AdminController::class, 'acceptOrder'])->name('admin.orders.accept');
    Route::get('/admin/order/{order}', [App\Http\Controllers\AdminController::class, 'viewOrderDetails'])->name('admin.order.details');

    Route::get('/admin/orders/decline/{id}', [AdminController::class, 'declineOrder'])->name('admin.orders.decline');

    // ✅ History Pages
    Route::get('/order-history', [AdminController::class, 'orderHistory'])->name('admin.order.history');
    Route::get('/reservation-history', [AdminController::class, 'reservationHistory'])->name('admin.reservation.history');
});

// ✅ Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'checkout'])->middleware('auth')->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.process');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
Route::get('/order/processing/{orderId}', [CheckoutController::class, 'processing'])->name('order.processing');


// ✅ Order Online & Cart Routes
Route::get('/order-online', [OrderController::class, 'index'])->name('order.index');
Route::post('/order-online/add/{id}', [OrderController::class, 'addToCart'])->name('order.add');
Route::post('/order/add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.add');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/cart', [OrderController::class, 'showCart'])->name('cart.show');
Route::get('/cart/remove/{id}', [OrderController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update/{id}', [OrderController::class, 'update'])->name('cart.update');
Route::get('/cart/clear', [OrderController::class, 'clearCart'])->name('cart.clear');
Route::get('/checkout/details', [OrderController::class, 'details'])->name('checkout.details');
Route::post('/checkout/process', [OrderController::class, 'process'])->name('checkout.process');
Route::get('/checkout/process/stripe', [CheckoutController::class, 'checkout'])->name('checkout.process.stripe');

// ✅ Reservation (Book Table) Routes
Route::get('/book-table', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/book-table', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/waiting', function () {
    return view('reservations.waiting');
})->name('reservations.waiting');
Route::get('/reservations/{reservation}/status', [ReservationController::class, 'status']);
Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);


// ✅ Menu Page
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// ✅ Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');

// ✅ Profile Routes (User Account Management)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
});

// ✅ Order Processing (With Real-time Status Check)
Route::get('/order/processing/{order}', [OrderController::class, 'processing'])
    ->middleware('auth')
    ->name('order.processing');

Route::get('/order/status/{order}', [OrderController::class, 'status'])
    ->middleware('auth')
    ->name('order.status');

    Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
        Route::get('/orders', [UserOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [UserOrderController::class, 'show'])->name('orders.show');
    });

    Route::get('/user/orders', [OrderController::class, 'userOrders'])->name('user.orders');
    Route::get('/order/processing/{orderId}', [OrderController::class, 'processing'])->name('order.processing');
    Route::get('/user/reservations', [ReservationController::class, 'userReservations'])->name('user.reservations');


    

// ✅ Auth Routes
require __DIR__.'/auth.php';
