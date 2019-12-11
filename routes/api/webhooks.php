<?php

Route::group(['namespace' => 'Webhooks', 'prefix' => 'api/webhooks', 'as' => 'webhooks.'], function () {
    //
    Route::post('xero', 'XeroWebhookController@handle')->middleware('verify_xero');
});
