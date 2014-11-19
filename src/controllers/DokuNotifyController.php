<?php namespace Yusidabcs\Checkout;

class DokuNotifyController extends \Illuminate\Routing\Controllers\Controller {
	
	public function store(){
		// 2.1 Retrieve Parameters to variables
		$transidmerchant = \Input::get('TRANSIDMERCHANT');
		$totalamount = \Input::get('AMOUNT');
		$result = strtoupper(\Input::get('RESULT'));
		$status = false;
		if ($_SERVER['REMOTE_ADDR'] =='103.10.128.11') {
			// 2.2 Cross check with MYSHORTCART Database Table
			// $true = 1 : transaction found, 0 : transaction not found
			$order = \Order::where('kodeOrder',$transidmerchant)->first();

			if($order){
				if($result=='SUCCESS'){
					//update order ke pembayaran diterima
					$order->status = 2;
					$order->save();
					$status = true;
                    //send email to member and toko
                    //kirim email konfirmasi pembayaran
                    $setting = \Pengaturan::where('akunId','=',$order->akunId)->first();
                    $data = array(
                        'pelanggan'=> $order->nama,
                        'pelangganalamat'=> $order->alamat,
                        'pelangganphone'=> $order->telp,
                        'toko' => $setting->nama,
                        'kodeorder' => $order->kodeOrder,
                        'tanggal' => $order->tanggalOrder,
                        'namaPengirim' => $order->konfirmasi==null ? '-':$order->konfirmasi->nama,
                        'noRekening' => $order->konfirmasi==null ? '-':$order->konfirmasi->noRekPengirim,
                        'rekeningTujuan' =>$order->konfirmasi==null ? '-' : $order->konfirmasi->bank->atasNama.'<br>'.$order->konfirmasi->bank->noRekening.' - '.$order->konfirmasi->bank->bankdefault->nama,
                        'jumlah' =>$order->konfirmasi==null ? '-':($order->konfirmasi->jumlah),
                        'cart' => \View::make('admin.order.detailorder')->with('order',$order),
                        'namaEkspedisi' => $order->jenisPengiriman,
                        'noResi' => $order->noResi,
                        'tujuanPengiriman' => $order->alamat.' - '.$order->kota,
                        'linkRegistrasi' => \URL::to('member/create')
                    );
                    $order->fromEmail = $setting->email;
                    $order->fromtoko = $setting->nama;
                    $qtyProduk = $order->detailorder;
                    $template = \Templateemail::where('akunId','=',$order->akunId)->where('no','=',6)->first();
                    $email = bind_to_template($data,$template->isi);
                    $subject = bind_to_template($data,$template->judul);
                    $a = \Mail::send('emails.email',array('data'=>$email, 'nama'=>'','web'=>'','email'=>'','telp'=>'','jmlProduk'=>'','metatag'=>0), function($message) use ($subject,$order)
                    {
                        $message->from($order->fromEmail, $order->fromtoko);
                        $message->to($order->pelanggan->email, $order->pelanggan->nama)->cc($order->fromEmail)->subject($subject);
                    });
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