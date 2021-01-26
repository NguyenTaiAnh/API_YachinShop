<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//WEB API
$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\AuthController@show');
        $api->post('login', 'App\\Api\\V1\\Controllers\\Web\\AuthController@login');
        $api->post('logout', 'App\\Api\\V1\\Controllers\\Web\\AuthController@logout');
        $api->get('check', 'App\\Api\\V1\\Controllers\\Web\\AuthController@check');
        $api->get('me', 'App\\Api\\V1\\Controllers\\Web\\AuthController@me');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\AuthController@delete');
        $api->post('resetpassword', 'App\\Api\\V1\\Controllers\\Web\\AuthController@reset');
        $api->post('info', 'App\\Api\\V1\\Controllers\\Web\\AuthController@info');
        $api->post('image/{id}', 'App\\Api\\V1\\Controllers\\Web\\AuthController@image');
        $api->get('shownotifi', 'App\\Api\\V1\\Controllers\\Web\\AuthController@shownotifi');
        $api->post('editnotifi/{id}', 'App\\Api\\V1\\Controllers\\Web\\AuthController@editnotifi');
        $api->get('new', 'App\\Api\\V1\\Controllers\\Web\\AuthController@new');
        $api->get('confirm', 'App\\Api\\V1\\Controllers\\Web\\AuthController@confirm');
        $api->post('createdevice', 'App\\Api\\V1\\Controllers\\Web\\AuthController@createDevice');
    });

    // ========================================Customer =======================================================
    $api->group(['prefix' => 'customer'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\CustomerController@listCustomer');
    });


    //---------------------------------------- BANNER ---------------------------------------------------------
    $api->group(['prefix' => 'banner'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\BannerController@list');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\BannerController@create');
        $api->get('{id}', 'App\\Api\\V1\\Controllers\\Web\\BannerController@editstatus');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\BannerController@delete');
        $api->get('detail/{id}','App\\Api\\V1\\Controllers\\Web\\BannerController@detail');
        $api->post('edit/{id}','App\\Api\\V1\\Controllers\\Web\\BannerController@edit');
    });

    //---------------------------------------- CATEGORY ---------------------------------------------------------
    $api->group(['prefix' => 'category'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\CategoryController@list');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\CategoryController@detail');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\CategoryController@create');
        $api->post('{id}', 'App\\Api\\V1\\Controllers\\Web\\CategoryController@edit');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\CategoryController@delete');
    });

    //---------------------------------------- DISCOUNT ---------------------------------------------------------
    $api->group(['prefix' => 'discount'], function (Router $api) {
        $api->get('check/{id}', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@check');
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@list');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@detail');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@create');
        $api->put('{id}', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@edit');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\DiscountController@delete');
    });

    //---------------------------------------- PAYTYPE ---------------------------------------------------------
    $api->group(['prefix' => 'paytype'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@list');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@detail');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@create');
        $api->put('{id}', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@edit');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@delete');
    });

    //---------------------------------------- ORDER ---------------------------------------------------------
    $api->group(['prefix' => 'order'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\OrderController@list');
        $api->post('compute', 'App\\Api\\V1\\Controllers\\Web\\OrderController@compute');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\OrderController@create');
        $api->put('{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderController@edit');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderController@delete');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderController@detail');
        $api->post('status/{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderController@editStatus');
        $api->get('payment/{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderController@statusPayment');
        $api->post('send', 'App\\Api\\V1\\Controllers\\Web\\SendMailController@sendmail');
        $api->post('qrcode', 'App\\Api\\V1\\Controllers\\Web\\OrderController@qrcode');
        $api->post('checkqr', 'App\\Api\\V1\\Controllers\\Web\\OrderController@checkQR');
    });

    //---------------------------------------- PRODUCT ---------------------------------------------------------
    $api->group(['prefix' => 'product'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\ProductController@show');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\ProductController@create');
        $api->post('upload/image/{productid}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@uploadImage');
        $api->post('{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@edit');
        $api->post('edit_image/{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@edit_image');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@detail');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@delete');
        $api->get('attribute/{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@attributes');
        $api->get('attribute/{id}/{size}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@size');
        $api->post('price/{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@price');
        $api->delete('image/{id}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@delete_image');
        $api->get('totalproduct', 'App\\Api\\V1\\Controllers\\Web\\ProductController@totalproduct');
        $api->post('upload/image/{productid}', 'App\\Api\\V1\\Controllers\\Web\\ProductController@uploadImage');
    });

    //---------------------------------------- ORDER_STATUS ---------------------------------------------------------
    $api->group(['prefix' => 'orderstatus'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\OrderstatusController@show');
        $api->post('create', 'App\\Api\\V1\\Controllers\\Web\\OrderstatusController@create');
        $api->post('edit/{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderstatusController@edit');
        $api->post('delete/{id}', 'App\\Api\\V1\\Controllers\\Web\\OrderstatusController@delete');
    });

    //---------------------------------------- SHIPPING ---------------------------------------------------------
    $api->group(['prefix' => 'shipping'], function (Router $api) {
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\ShippingController@get');
        $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\Web\\ShippingController@detail');
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\ShippingController@create');
        $api->put('{id}', 'App\\Api\\V1\\Controllers\\Web\\ShippingController@edit');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\ShippingController@delete');
    });

// ---------------------------------------------------------------------------------------------------------------------
    $api->group(['prefix' => 'location'], function (Router $api) {
        $api->get('province', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getProvince');
        $api->get('district/{provinceID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getDistrict');
        $api->get('ward/{districtID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getWard');
        $api->get('village/{wardID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getVillage');
    });
// ---------------------------------------------------------------------------------------------------------------------

    $api->group(['prefix' => 'slide'], function (Router $api) {
        $api->post('', 'App\\Api\\V1\\Controllers\\Web\\SettingController@create');
        $api->delete('{id}', 'App\\Api\\V1\\Controllers\\Web\\SettingController@delete');
        $api->get('', 'App\\Api\\V1\\Controllers\\Web\\SettingController@list');
    });
    $api->group(['prefix' => 'dashboard'], function (Router $api) {
        $api->get('order_day', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@order_day');
        $api->get('order_week', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@order_week');
        $api->get('order_month', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@order_month');
        $api->get('order_year', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@order_year');
        $api->get('cancel_day', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@cancel_day');
        $api->get('cancel_week', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@cancel_week');
        $api->get('cancel_month', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@cancel_month');
        $api->get('cancel_year', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@cancel_year');
        $api->get('total_day', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_day');
        $api->get('total_week', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_week');
        $api->get('total_month', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_month');
        $api->get('total_year', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_year');
        $api->get('total_order', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_order');
        $api->get('total_new', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@total_new');
        $api->get('total_customer', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@customer');
        $api->post('edit_status/{id}', 'App\\Api\\V1\\Controllers\\Web\\DashboardController@edit_status');
    });

    //---------------------------------------- CATEGORY ---------------------------------------------------------
    $api->group(['prefix' => 'setting'], function (Router $api) {
        $api->get('store', 'App\\Api\\V1\\Controllers\\Web\\SettingController@detailStore');
        $api->post('updatestore', 'App\\Api\\V1\\Controllers\\Web\\SettingController@updateStore');
    });

//----------------------------------------------------------------------------------------------------------------------

    //APP API
    $api->group(['prefix' => 'app'], function (Router $api) {
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->get('check', 'App\\Api\\V1\\Controllers\\App\\AuthController@check');
            $api->get('me', 'App\\Api\\V1\\Controllers\\App\\AuthController@detail');
            $api->post('loginemail', 'App\\Api\\V1\\Controllers\\App\\AuthController@loginEmail');
            $api->post('loginphone', 'App\\Api\\V1\\Controllers\\App\\AuthController@loginPhone');
            $api->post('signup', 'App\\Api\\V1\\Controllers\\App\\AuthController@register');
            $api->post('logout', 'App\\Api\\V1\\Controllers\\App\\AuthController@logout');
            $api->get('refresh', 'App\\Api\\V1\\Controllers\\App\\AuthController@refresh');
            $api->post('info', 'App\\Api\\V1\\Controllers\\App\\AuthController@info');
            $api->post('image/{id}', 'App\\Api\\V1\\Controllers\\App\\AuthController@image');
            $api->post('check_mail', 'App\\Api\\V1\\Controllers\\App\\AuthController@check_mail');
            $api->post('checkfacbook', 'App\\Api\\V1\\Controllers\\App\\AuthController@check_facebook');
            $api->post('resetpassword', 'App\\Api\\V1\\Controllers\\App\\AuthController@editpassword');
            $api->post('createdevice', 'App\\Api\\V1\\Controllers\\App\\AuthController@createDevice');
        });
        $api->group(['prefix' => 'category'], function (Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\App\\CategoryController@list');
            $api->get('new', 'App\\Api\\V1\\Controllers\\App\\CategoryController@new');
        });
        $api->group(['prefix' => 'product'], function (Router $api) {
            $api->get('new', 'App\\Api\\V1\\Controllers\\App\\ProductController@new');
            $api->get('category/{id}', 'App\\Api\\V1\\Controllers\\App\\ProductController@category');
            $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\App\\ProductController@detail');
            $api->get('sale', 'App\\Api\\V1\\Controllers\\App\\ProductController@sale');
            $api->get('all', 'App\\Api\\V1\\Controllers\\App\\ProductController@all');
            $api->get('increase', 'App\\Api\\V1\\Controllers\\App\\ProductController@increase');//tăng dần
            $api->post('search', 'App\\Api\\V1\\Controllers\\App\\ProductController@search');
            $api->get('new_old', 'App\\Api\\V1\\Controllers\\App\\ProductController@new_old');
            $api->post('color/{id}', 'App\\Api\\V1\\Controllers\\App\\ProductController@color');
            $api->post('size/{id}', 'App\\Api\\V1\\Controllers\\App\\ProductController@size');
            $api->post('attprice/{id}', 'App\\Api\\V1\\Controllers\\App\\ProductController@priceAtt');
            $api->get('reduction', 'App\\Api\\V1\\Controllers\\App\\ProductController@reduction');//giảm dần
            $api->post('filter', 'App\\Api\\V1\\Controllers\\App\\ProductController@filter');//filter
            $api->get('shipping', 'App\\Api\\V1\\Controllers\\App\\ProductController@getShiping');//status shippng
            $api->get('shipping/{id}',
                'App\\Api\\V1\\Controllers\\App\\ProductController@getShipingDetail');//status shippng
            $api->post('wish',
                'App\\Api\\V1\\Controllers\\App\\ProductController@createwish')->middleware('customer');//them wishlist
            $api->delete('wish/{id}',
                'App\\Api\\V1\\Controllers\\App\\ProductController@deletewish')->middleware('customer');//xoa wishlist
            $api->get('wish',
                'App\\Api\\V1\\Controllers\\App\\ProductController@getwish')->middleware('customer');//danh sach wishlist
        });
        $api->group(['prefix' => 'slide'], function (Router $api) {
            $api->post('', 'App\\Api\\V1\\Controllers\\App\\SettingController@create');

            $api->get('', 'App\\Api\\V1\\Controllers\\App\\SettingController@list');
        });
        $api->group(['prefix' => 'address'], function (Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\App\\AddressController@list');
            $api->post('', 'App\\Api\\V1\\Controllers\\App\\AddressController@create');
            $api->put('{id}', 'App\\Api\\V1\\Controllers\\App\\AddressController@edit');
            $api->delete('{id}', 'App\\Api\\V1\\Controllers\\App\\AddressController@delete');
            $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\App\\AddressController@detail');
            $api->get('sort', 'App\\Api\\V1\\Controllers\\App\\AddressController@sort');

        });
        $api->group(['prefix' => 'order'], function (Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\App\\OrderController@list');
            $api->post('', 'App\\Api\\V1\\Controllers\\App\\OrderController@create');
            $api->put('{id}', 'App\\Api\\V1\\Controllers\\App\\OrderController@edit');
            $api->delete('{id}', 'App\\Api\\V1\\Controllers\\App\\OrderController@delete');
            $api->get('detail/{id}', 'App\\Api\\V1\\Controllers\\App\\OrderController@detail');
            $api->get('send/{id}', 'App\\Api\\V1\\Controllers\\App\\SendMailController@sendmail');
            $api->post('check', 'App\\Api\\V1\\Controllers\\App\\OrderController@check');//check code
            $api->get('pay', 'App\\Api\\V1\\Controllers\\Web\\PaytypesController@list');
            $api->post('checkqr', 'App\\Api\\V1\\Controllers\\App\\OrderController@checkQR');
            $api->post('vnpay', 'App\\Api\\V1\\Controllers\\App\\VnPayController@create');
            // edit status payment
            $api->post('statuspayment/{id}', 'App\\Api\\V1\\Controllers\\App\\OrderController@statusPayment');
            // list paytype
            $api->get('listpaytype', 'App\\Api\\V1\\Controllers\\App\\OrderController@listPaytype');
        });


        $api->group(['prefix' => 'location'], function (Router $api) {
            $api->get('province', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getProvince');
            $api->get('district/{provinceID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getDistrict');
            $api->get('ward/{districtID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getWard');
            $api->get('village/{wardID}', 'App\\Api\\V1\\Controllers\\Web\\LocationController@getVillage');
        });

        //---------------------------------------- BANNER ---------------------------------------------------------
        $api->group(['prefix' => 'banner'], function (Router $api) {
            $api->get('', 'App\\Api\\V1\\Controllers\\App\\BannerController@list');
            $api->post('', 'App\\Api\\V1\\Controllers\\App\\BannerController@create');
            $api->delete('{id}', 'App\\Api\\V1\\Controllers\\App\\BannerController@delete');
        });

    });
});


