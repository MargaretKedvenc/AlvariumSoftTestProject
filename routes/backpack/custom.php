<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('aboutus', 'AboutUsCrudController');
    Route::crud('contacts', 'ContactsCrudController');
    Route::crud('news', 'NewsCrudController');
    Route::crud('post', 'PostCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('privacypolicy', 'PrivacyPolicyCrudController');
    Route::crud('publicoffer', 'PublicOfferCrudController');
    Route::crud('homeslider', 'HomeSliderCrudController');
    Route::crud('menu', 'MenuCrudController');
    Route::crud('menuitem', 'MenuItemCrudController');
    Route::crud('subscriber', 'SubscriberCrudController');
    Route::crud('subscription', 'SubscriptionCrudController');
    Route::crud('home', 'HomeCrudController');

    Route::crud('category', 'CategoryCrudController');
}); // this should be the absolute last line of this file