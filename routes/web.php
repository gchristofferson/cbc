<?php
//Auth::loginUsingId(1); // work
//Auth::loginUsingId(2); // home
//Auth::logout();
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * GET /<directory_name_equals_object> (index) //to show all the objects
 * GET /<directory_name_equals_object>/create (create) //to show form for creating new object
 * GET /<directory_name_equals_object>/<object_id> (show) //show object detail
 * POST /<directory_name_equals_object> (store) //to store new in database
 * GET /<directory_name_equals_object>/<object_id>/edit (edit) //to edit an object
 * PATCH /<directory_name_equals_object>/<object_id> (update) //to update an object
 * DELETE /<directory_name_equals_object>/<object_id> (destroy) //to delete an object
 */

// Home
Route::get('/', 'ContentsController@dashboard')->name('dashboard')->middleware('auth');

// Users (agents)
Route::resource('users', 'UserController')->middleware('auth');
Route::get('/rejected/users', 'UserController@indexRejected')->middleware('auth');
Route::get('/new/users', 'UserController@indexNew')->middleware('auth');

// Inquiries
Route::resource('inquiries', 'InquiryController')->middleware('auth');
//Route::get('/inquiries/received', 'ReceivedController@index');

// Received
Route::resource('received', 'ReceivedController')->middleware('auth');

// Sent
Route::resource('sent', 'SentController')->middleware('auth');

// Saved
Route::resource('saved', 'SavedController')->middleware('auth');

// States
Route::resource('states', 'StateController')->middleware('auth');

// Cities
Route::resource('cities', 'CityController')->middleware('auth');

// Notifications
Route::resource('notifications', 'NotificationController')->middleware('auth');
Route::post('/notifications/create', 'NotificationController@create')->middleware('auth');

// Subscriptions
Route::resource('subscriptions', 'Subscriptions')->middleware('auth');
Route::post('/subscriptions/create', 'Subscriptions@subscribe')->middleware('auth');

// Documents
Route::resource('documents', 'DocumentController')->middleware('auth');

// Documents
Route::resource('discounts', 'DiscountController')->middleware('auth');

// Contents

Route::get('/dashboard', 'ContentsController@dashboard')->name('dashboard')->middleware('auth');
Route::get('/update-profile', 'ContentsController@updateProfile')->name('update-profile')->middleware('auth');
Route::post('/update-profile', 'ContentsController@updateProfile')->middleware('auth');


// Cart
Route::post('paypal', 'PaymentController@payWithpaypal')->middleware('auth');
Route::get('status', 'PaymentController@getPaymentStatus')->middleware('auth');


Auth::routes();
//Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/storage/attachments/{userid}/{filename}', function ($userid, $filename) {
    $path = storage_path() . '/app/public/attachments/' . $userid . '/' . $filename;

    if (!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');


