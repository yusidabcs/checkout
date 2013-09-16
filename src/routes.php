<?php
Route::group(array('before' => 'subdomain'), function() {
	Route::get('checkout','Yusidabcs\Checkout\CheckoutController@index');
	Route::post('pengiriman', 'Yusidabcs\Checkout\CheckoutController@pengiriman');
	Route::get('pengiriman', 'Yusidabcs\Checkout\CheckoutController@pengiriman');
	Route::post('pembayaran', 'Yusidabcs\Checkout\CheckoutController@pembayaran');
	Route::get('pembayaran', 'Yusidabcs\Checkout\CheckoutController@pembayaran');
	Route::post('konfirmasi', 'Yusidabcs\Checkout\CheckoutController@konfirmasi');
	Route::get('konfirmasi', 'Yusidabcs\Checkout\CheckoutController@konfirmasi');
	Route::post('finish', 'Yusidabcs\Checkout\CheckoutController@finish');
	Route::resource('konfirmasiorder', 'KonfirmasiOrderController');
});

