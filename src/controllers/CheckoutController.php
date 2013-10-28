<?php namespace Yusidabcs\Checkout;
require app_path().'/GoPayPal.class.php';
/**
 * Libraries we can use.
 */
use Produk;
use Opsisku;
use Kategori;
use Pengaturan;
use Negara;
use Provinsi;
use Kabupaten;
use Sentry;
use Shpcart;
use OnlineAkun;
use BankDefault;
use Bank;
use Diskon;
use Pelanggan;
use Pajak;
use Order;
use DetailOrder;
use Templateemail;
use URL;
use Request;
use Mail;
use ShopCartController;
use GoPayPal;
use GoPayPalCartItem;
use Currencies;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * The cart main page.
 */
class CheckoutController  extends \Yusidabcs\Checkout\BaseController
{
    /**
     * Flag for whether the controller is RESTful.
     *
     * @access   public
     * @var      boolean
     */
    public $restful = true;
    
    public function index()
    {   
        Session::forget('pengiriman');
        if(URL::previous()!=URL::to('pengiriman') && URL::previous()!=URL::to('pembayaran') && URL::previous()!=URL::to('konfirmasi')){
            Session::forget('besarPotongan');
            Session::forget('diskonId');
            Session::forget('tipe'); 
            Session::forget('tujuan');
            Session::forget('ekspedisiId');
            Session::forget('ongkosKirim');
        }
        $kode = rand(100,200);
        $pengaturan = $this->setting;
        if($pengaturan->statusEkspedisi!=1){
            if($pengaturan->statusEkspedisi==2)
                Session::set('ekspedisiId',"Free Shipping");
            if($pengaturan->statusEkspedisi==3)
                Session::set('ekspedisiId',"Pengiriman Menyusul");
            Session::set('ongkosKirim',0);
        }
        //$eks = New ShopCartController;
        //$data = $eks->checkekspedisi('surabaya');
        $selected = Session::get('ekspedisiId').';'.Session::get('ongkosKirim');

        if(Session::has('ekspedisiId')){
            $status =1;
            $ekspedisi = array('tujuan'=>Session::get('tujuan'),'ekspedisi'=>Session::get('ekspedisiId'),'tarif'=>Session::get('ongkosKirim'));
        }else{
            $status =0;
            $ekspedisi=null;
        }
        if(Session::has('diskonId')){            
            $diskon = array('diskonId' => Diskon::find(Session::get('diskonId')), 'besarPotongan'=>Session::get('besarPotongan'));
        }else{
            $diskon=null;
        }
        Session::put('kodeunik',$kode);
        $this->layout->content = View::make('checkout::step1')->with('cart' ,Shpcart::cart())
            ->with('provinsi' ,Provinsi::where('negaraId','=',$this->setting->negara)->get())
            ->with('kodeunik',$kode)
            ->with('pengaturan' ,$pengaturan)
            ->with('statusEkspedisi',$status)
            ->with('ekspedisi',$ekspedisi)
            ->with('diskon',$diskon)
            ->with('kontak', $this->setting);
        $this->layout->seo = View::make('checkout::seostuff')
        ->with('title',"Checkout - Rincian Belanja - ".$this->setting->nama)
        ->with('description',$this->setting->deskripsi)
        ->with('keywords',$this->setting->keyword);
    }
     function pengiriman(){
        //check session cart dan ekspedisi dan diskon                
        if(Shpcart::cart()->total_items()==0 || !(Session::has('ekspedisiId'))) {
            return Redirect::to('checkout');
        }
       //check ekspedisi
        if($this->setting->statusEkspedisi==1){
            if(!Session::has('ekspedisiId')){
                 return Redirect::to('checkout');
            }
        }
        if(Session::has('message')){            
            echo "<div class='".Session::get('message')."' id='message' style='display:none'>
                <p>".Session::get('text')."</p>
            </div>";
        }       

        $this->layout->content = View::make('checkout::step2')->with('cart' ,Shpcart::cart())
            ->with('provinsi' ,Provinsi::where('negaraId','=',$this->setting->negara)->get())
            ->with('user',(Sentry::check() ? (Session::has('pengiriman') ? null:Sentry::getUser()):null))
            ->with('negara', Negara::lists('nama','id'))
            ->with('provinsi',Provinsi::lists('nama','id'))
            ->with('kota', Kabupaten::lists('nama','id'))
            ->with('usertemp',(Session::has('pengiriman')?Session::get('pengiriman'):null))
            ->with('kontak', $this->setting);
        $this->layout->seo = View::make('checkout::seostuff')
        ->with('title',"Checkout - Data Pembeli dan Pengiriman - ".$this->setting->nama)
        ->with('description',$this->setting->deskripsi)
        ->with('keywords',$this->setting->keyword);
    }
    function pembayaran(){
        if ( ! Sentry::check()){
            
            $user = Pelanggan::where('email','=',Input::get('email'))->whereIn('tipe', array(1,2))->where('akunId','=',$this->akunId)->get();
            if($user->count()>0){
                return Redirect::to('pengiriman')->withInput()->with('message','error')->with('text','Alamat email sudah digunakan. Coba yang lain atau silakan login.');
            }            
        }
        if(Request::server('REQUEST_METHOD')=='POST'){
            Session::put('pengiriman', Input::all());               
        }        
        $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();      
        $this->layout->content = View::make('checkout::step3')->with('cart' ,Shpcart::cart())
            ->with('banks',BankDefault::all())
            ->with('user',(Sentry::check() ? Sentry::getUser():''))
            ->with('banktrans' ,Bank::where('akunId','=',$this->akunId)->get())
            ->with('paypal' , $akun[0])
            ->with('creditcard', $akun[1])
            ->with('pembayaran',Session::has('pembayaran')? Session::get('pembayaran'):null)
            ->with('kontak', $this->setting);
        $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Metode Pembayaran - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
    }
    function konfirmasi(){
        if(Request::server('REQUEST_METHOD')=='POST'){
            Session::put('pembayaran',Input::all());
            $pembayaran = Input::all();
        }
        if(Session::has('pembayaran')){
            $pembayaran =Session::get('pembayaran');
        } 
        $datapengirim = Session::get('pengiriman');
        $datapengirim['negara'] = Negara::find($datapengirim['negara'])->nama;
        $datapengirim['provinsi'] = Provinsi::find($datapengirim['provinsi'])->nama;
        $datapengirim['kota'] = Kabupaten::find($datapengirim['kota'])->nama;
        if($datapengirim['statuspenerima']==1){
            $datapengirim['negarapenerima'] = Negara::find($datapengirim['negarapenerima'])->nama;
            $datapengirim['provinsipenerima'] = Provinsi::find($datapengirim['provinsipenerima'])->nama;
            $datapengirim['kotapenerima'] = Kabupaten::find($datapengirim['kotapenerima'])->nama;
        }
        $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();
        $potongan = 0;
        
        if(!is_null(Session::get('diskonId'))){
            if(Session::get('tipe')==1){
                $potongan = Session::get('besarPotongan');                
            }else{
                $potongan = (Shpcart::cart()->total()*Session::get('besarPotongan')/100);
            }
        }
        $total = (Shpcart::cart()->total() + Session::get('ongkosKirim')- $potongan);        
        $total = $total + (Pajak::where('akunId','=',$this->akunId)->first()->status==0 ? 0 : $total * Pajak::where('akunId','=',$this->akunId)->first()->pajak / 100) + Session::get('kodeunik');        
        
        $this->layout->content = View::make('checkout::step4')->with('cart' ,Shpcart::cart())
            ->with('datapengirim',$datapengirim)
            ->with('dataekspedisi',Session::get('ekspedisiId'))
            ->with('datapembayaran',$pembayaran)
            ->with('kodekupon' ,Session::has('diskonId') ? Diskon::find(Session::get('diskonId'))->kode : '')
            ->with('kodeunik', Session::get('kodeunik'))
            ->with('diskon', $potongan)
            ->with('total', $total)
            ->with('kontak', $this->setting);
        $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Ringkasan Order - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
    }
    public function finish(){
        if(Shpcart::cart()->total()==0){
            return Redirect::to('checkout');
        }
        $pengaturan = $this->setting;            
        //Generate kd Order
        $awal = date('ymd');
        $next_id ='';
        if(!is_null(Order::orderBy('created_at', 'desc')->first())){            
            $model = Order::orderBy('created_at', 'desc')->first();
            $next_id = $model->kodeOrder;      
        }
            
        if($next_id!=''){
            $next_id = substr($next_id,6,10);
            $next_id ++;
        }else{
            $next_id= 1;
        }
        $nol = (str_repeat('0',(4-strlen($next_id))));
        $kdOrder = $awal.$nol.$next_id;

        $datapengirim = Session::get('pengiriman');
        $pembayaran = Session::get('pembayaran');
        //cek guest atau pelanggan
        if ( ! Sentry::check()){
            //guest            
            $datapengirim['kotanama'] = Kabupaten::find($datapengirim['kota'])->nama;

            //$user = new Pelanggan;
            $data = array(
                'nama' => $datapengirim['nama'],
                'email'    => $datapengirim['email'],
                'password' => 'guest',
                'kodepos' => $datapengirim['kodepos'],
                'perusahaan' => '',
                'telp' => $datapengirim['telp'],
                'alamat' => $datapengirim['alamat'],
                'negara' => $datapengirim['negara'],
                'provinsi' => $datapengirim['provinsi'],
                'kota' => $datapengirim['kota'],
                'tglLahir' => '',
                'catatan' => '',
                'tags' => '',
                'tipe' => 0,
                'tanggalMasuk' => date("Y-m-d"),
                'activated' => 1,
                'akunId' => $this->akunId
            );

            $user = Sentry::getUserProvider()->create($data);
            $userGroup = Sentry::getGroupProvider()->findById(3);
             // Assign the group to the user
            $user->addGroup($userGroup);            
            $pelangganId = $user->id;
            //return $pelangganId;
        }else{
            //pelanggan
            $pelangganId = Sentry::getUser()->id;
        }
        
        //ekspedisi 
        $ekspedisi =Session::get('ekspedisiId');
        $jenispengiriman = Session::get('ekspedisiId');
        $ongkosKirim = Session::get('ongkosKirim');
        //cek diskon
        if(Session::has('diskonId')){
            $diskonId = Session::get('diskonId');   
            $diskon = Diskon::find($diskonId);
            $diskon->klaim = $diskon->klaim +1;
            $diskon->save();
        }else{
            $diskonId='';
        }

        //get total order
        $potongan = 0;
        
        if(!is_null(Session::get('diskonId'))){
            if(Session::get('tipe')==1){
                $potongan = Session::get('besarPotongan');                
            }else{
                $potongan = (Shpcart::cart()->total()*Session::get('besarPotongan')/100);
            }
        }
        $total = (Shpcart::cart()->total() + Session::get('ongkosKirim')- $potongan) + Session::get('kodeunik');
        $total = $total + (Pajak::where('akunId','=',$this->akunId)->first()->status==0 ? 0 : $total * Pajak::where('akunId','=',$this->akunId)->pajak / 100);

        //save order
        $order = new Order;
        $order->kodeOrder = $kdOrder;
        $order->tanggalOrder = date('Y-m-d H:m:s');
        $order->pelangganId = $pelangganId;
        $order->total= $total;
        $order->status= 0;
        $order->jenisPengiriman = $jenispengiriman;
        $order->ongkoskirim = Session::get('ongkosKirim');
        if($datapengirim['statuspenerima']==0){
            $order->nama = $datapengirim['nama'];
            $order->telp = $datapengirim['telp'];
            $order->alamat = $datapengirim['alamat'];
            $order->kota = Kabupaten::find($datapengirim['kota'])->nama;    
        }
        else{
            $order->nama = Input::get('namapenerima');
            $order->telp = Input::get('telppenerima');
            $order->alamat = Input::get('alamatpenerima');
            $order->kota = Kabupaten::find($datapengirim['kotapenerima'])->nama;
        }
        $order->pesan = $datapengirim['pesan'];
        $order->noResi = '';
        $order->ekspedisiId = '';
        if(Session::get('pembayaran')['tipepembayaran']=='bank'){
            $pembayaran =1;
        }else if(Session::get('pembayaran')['tipepembayaran']=='paypal'){
            $pembayaran =2;           
        }else if(Session::get('pembayaran')['tipepembayaran']=='creditcard'){
            $pembayaran =3;
        }            
        
        $order->jenisPembayaran = $pembayaran;
        $order->diskonId = Session::has('diskonId') ? Session::get('diskonId') : '';
        $order->akunId = $this->akunId;
        $order->save();
        if($order){
            //tambah det order
            $cart_contents = Shpcart::cart()->contents();
            foreach ($cart_contents as $key => $value) {
                $detorder = new DetailOrder;
                $detorder->orderId=$order->id;
                $detorder->opsiSkuId= is_null($value['opsiskuId']) ? '':$value['opsiskuId'];
                $detorder->produkId = $value['produkId'];
                $detorder->qty = $value['qty'];
                $detorder->hargaSatuan = $value['price'];
                $detorder->created_at = date('Y-m-d H:m:s');
                $detorder->updated_at = date('Y-m-d H:m:s');
                $detorder->save();
            }
            //kirim email konfirmasi ke email user
             $cart ='<table cellpadding="5"><tr>
                            <td>No</td>
                            <td>Nama Produk</td>
                            <td>Varian</td>
                            <td>Qty/Harga</td>        
                            <td>Subtotal</td>
                        </tr>';            
            $cart = $cart.View::make('admin.order.listcart')->with('cart_contents', Shpcart::cart()->contents())->With('berat','0');
            $cart = $cart."<tr>     
                            <td colspan=4>
                                <h3 class='pull-right'>Total Orderan</h3>
                            </td>
                            <td colspan=2><h4>".jadiRupiah($order->total)."</h4></td>
                        </tr></table>";
            $bank = View::make('admin.pengaturan.bank')->with('banks', BankDefault::all()) ->with('banktrans', Bank::all());
            Shpcart::cart()->destroy();
            //kirim email order ke pelanggan
            $template = Templateemail::find(1);
            $data = array(
                'pelanggan'=> $order->nama,
                'toko' => $this->setting->nama,
                'kodeorder' => $order->kodeOrder,
                'tanggal' => $order->tanggalOrder,
                'cart' => $cart,
                'rekeningbank' =>$bank,
                'ekspedisi' =>$order->jenisPengiriman,
                'totalbelanja' =>$order->total
                );
            $email = bind_to_template($data,$template->isi);            
            $subject = bind_to_template($data,$template->judul);  
            Mail::later(3,'emails.email',array('data'=>$email), function($message) use ($subject,$datapengirim)
            {   
                $message->to($datapengirim['email'], $datapengirim['nama'])->subject($subject);
            });
            //kirik email konfirmasi ke email toko
            $subject2 = 'Pemberitahuan Order -- '.bind_to_template($data,$template->judul);  
            Mail::later(5,'emails.email',array('data'=>$email), function($message) use ($subject2,$pengaturan)
            {   
                $message->to($pengaturan->emailAdmin, $pengaturan->nama)->subject($subject2);
            });


            $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();
            $paypalbutton = "";
            if($order->jenisPembayaran==2){
                //buat button paypal.
                $paypal = new GoPayPal(THIRD_PARTY_CART);
                $paypal->sandbox = true;
                $paypal->openInNewWindow = true;
                $paypal->set($akun[0]->tipe, $akun[0]->acount);
                $paypal->set('currency_code', 'USD');
                $paypal->set('country', 'US');
                $paypal->set('return', URL::to('konfirmasiorder/'.$order->id));
                $paypal->set('cancel_return', URL::to('konfirmasiorder/'.$order->id));
                $paypal->set('notify_url', URL::to('konfirmasiorder/'.$order->id)); # rm must be 2, need to be hosted online
                $paypal->set('rm', 2); # return by POST
                $paypal->set('no_note', 0);
                $paypal->set('custom', md5(time()));
                $paypal->set('cbt', 'Return to our site to validate your payment!'); # caption override for "Return to Merchant" button                
                $paypal->set('handling_cart', 1); # this overide the individual items' handling "handling_x"
                $paypal->set('tax_cart', $akun[0]->fee);  
                $item = new GoPayPalCartItem();
                $item->set('item_name', 'Payment for order : #'.$order->kodeOrder);
                $item->set('item_number', '1');
                $total = $order->total;
                if($this->setting->mataUang == 1){
                    $total =round($order->total / Currencies::where('akunId','=',$this->akunId)->first()->rate); 
                }
                $item->set('amount', $total);
                $item->set('quantity', 1);
                $item->set('shipping', 0.1);
                $item->set('handling', 1); # this is overriden by "handling_cart"
                $paypal->addItem($item);
                # If you set your custom button here, PayPal Pay Now button will be displayed.
                $paypal->setButton('<button type="submit">Bayar Dengan Paypal - The safer, easier way to pay online!</button>');
                $paypalbutton=$paypal->html();      
            }
                        
            Shpcart::cart()->destroy();
            Session::forget('diskonId');
            Session::forget('besarPotongan');
            Session::forget('tipe');
            Session::forget('pengiriman');
            Session::forget('pembayaran');
            Session::forget('ekspedisiId');
            Session::forget('ongkosKirim');
            Session::forget('kodeunik');            
            
            $this->layout->content = View::make('checkout::step5')->with('datapengirim' ,$datapengirim)
            ->with('datapembayaran', $pembayaran)
            ->with('order', $order)
            ->with('banks' ,BankDefault::all())
            ->with('banktrans', Bank::where('akunId','=',$this->akunId))
            ->with('paypal',  $akun[0])
            ->with('creditcard' , $akun[1])
            ->with('pengaturan', $this->setting)
            ->with('paypalbutton', $paypalbutton)
            ->with('kontak', $this->setting);

            $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Finish - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
        }

    }

}