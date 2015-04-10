<?php
Route::group(array('before' => 'subdomain'), function() {

	Route::get('provinsi/{id}','Yusidabcs\Checkout\CheckoutController@getProvinsi');
	Route::get('kabupaten/{id}','Yusidabcs\Checkout\CheckoutController@getKabupaten');
	Route::get('searchkotabyname/{id}','Yusidabcs\Checkout\CheckoutController@getKabupatenByName');


	Route::get('checkout','Yusidabcs\Checkout\CheckoutController@index');
	Route::get('checkout/addekspedisi/{id}','Yusidabcs\Checkout\CheckoutController@addekspedisi');
	Route::get('checkout/update-ekspedisi/{id}','Yusidabcs\Checkout\CheckoutController@updateEkspedisi');
	Route::get('checkout/checkekspedisi/{id}','Yusidabcs\Checkout\CheckoutController@checkEkspedisi');

	Route::post('pengiriman', 'Yusidabcs\Checkout\CheckoutController@pengiriman');
	Route::get('pengiriman', 'Yusidabcs\Checkout\CheckoutController@pengiriman');
	Route::post('pembayaran', 'Yusidabcs\Checkout\CheckoutController@pembayaran');
	Route::get('pembayaran', 'Yusidabcs\Checkout\CheckoutController@pembayaran');
	Route::post('konfirmasi', 'Yusidabcs\Checkout\CheckoutController@konfirmasi');
	Route::get('konfirmasi', 'Yusidabcs\Checkout\CheckoutController@konfirmasi');
	Route::post('finish', 'Yusidabcs\Checkout\CheckoutController@finish');
	Route::get('finish', 'Yusidabcs\Checkout\CheckoutController@finish');
	Route::resource('konfirmasiorder', 'KonfirmasiOrderController');

	Route::get('carikota/{any}',function($any){
		$city = Ongkir::getCity($any,'JSON');	
		return Response::json($city);
	});
	Route::get('allcity',function(){
		$city = Ongkir::getCity('','JSON');	
		return Response::json($city);
	});
});

Route::group(array('prefix'=>'doku' ),function(){
	Route::resource('verify','Yusidabcs\Checkout\DokuVerifyController', array('only'=>array('store')));
	Route::resource('notify','Yusidabcs\Checkout\DokuNotifyController', array('only'=>array('store')));
	Route::resource('redirect','Yusidabcs\Checkout\DokuRedirectController', array('only'=>array('store')));
	Route::resource('cancel','Yusidabcs\Checkout\DokuCancelController', array('only'=>array('store')));

});
