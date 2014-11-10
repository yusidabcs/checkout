<?php namespace Yusidabcs\Checkout;

class DokuNotifyController extends \Illuminate\Routing\Controllers\Controller {
	
	public function store(){
		// 2.1 Retrieve Parameters to variables
		$transidmerchant = \Input::get('TRANSIDMERCHANT');
		$totalamount = \Input::get('AMOUNT');
		$result = strtoupper(\Input::get('RESULT'));
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
					$status = true;
				}
			}
		}
		if($status==true){
			echo 'Continue';
		}else{
			echo 'Stop';
		}
	}
}