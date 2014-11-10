<?php namespace Yusidabcs\Checkout;

class DokuVerifyController extends \Illuminate\Routing\Controllers\Controller {
	
	public function store(){
		// 2.1 Retrieve Parameters to variables
		$transidmerchant = \Input::get('TRANSIDMERCHANT');
		$totalamount = \Input::get('AMOUNT');
		$storeid = \Input::get('STOREID');
		$status = false;
		if ($_SERVER['REMOTE_ADDR'] !='103.10.128.11') {
			// 2.2 Cross check with MYSHORTCART Database Table
			// $true = 1 : transaction found, 0 : transaction not found
			$order = \Order::where('kodeOrder',$transidmerchant)->first();
			if($order){
				//check amount is valid + admin ofcourse!
				$doku_akun = \DokuAccount::where('akunId',$order->akunId)->first();
				$total_order = $order->total + $doku_akun->adminFee;

				if($totalamount==$total_order){
					$status = true;
				}else{
					$status = false;
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