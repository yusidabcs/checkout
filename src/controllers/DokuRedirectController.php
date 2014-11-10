<?php namespace Yusidabcs\Checkout;

class DokuRedirectController extends \Illuminate\Routing\Controllers\Controller {
	
	public function store(){
		// 2.1 Retrieve Parameters to variables
		$transidmerchant = \Input::get('TRANSIDMERCHANT');
		$statuscode = \Input::get('STATUSCODE');
		$transdate = \Input::get('TRANSDATE');
		$ptype = \Input::get('PTYPE');
		$totalamount = \Input::get('AMOUNT');
		$result = strtoupper(\Input::get('RESULT'));
		$xtrainfo = strtoupper(\Input::get('EXTRAINFO'));
		$status = false;
		if ($_SERVER['REMOTE_ADDR'] !='103.10.128.11') {
			// 2.2 Cross check with MYSHORTCART Database Table
			// $true = 1 : transaction found, 0 : transaction not found
			$order = \Order::where('kodeOrder',$transidmerchant)->first();
			if($order){
				if($result=='SUCCESS'){
					//update order ke pembayaran diterima
					$order->status = 2;
					$order->save();

					$akun = \Akun::find($order->akunId);
					return \Redirect::to('http://'.$akun->alamatWeb.'.'.\Config::get('app.domain').'/konfirmasiorder/'.$order->id)
						->with('message','Success Update Order');
				}
			}
		}
	}
}