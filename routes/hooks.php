<?php

Route::group(['prefix' => 'webhook'], function () {
    Route::post('/uninstall', 'WebhookController@uninstall')->name('webhook-uninstall-app');

    Route::group(['prefix' => 'products'], function () {
        Route::post('/create', 'WebhookController@productsCreate')->name('webhook-products-create');
        Route::post('/update', 'WebhookController@productsUpdate')->name('webhook-products-update');
        Route::post('/delete', 'WebhookController@productsDelete')->name('webhook-products-delete');
    });
});