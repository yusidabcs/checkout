<?php namespace Yusidabcs\Checkout;
require app_path().'/GoPayPal.class.php';
/**
 * Libraries we can use.
 */
use Akun;
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
use Inquiry;
use DetailOrder;
use DetailInquiry;
use Templateemail;
use URL;
use Request;
use Mail;
use ShopCartController;
use GoPayPal;
use GoPayPalCartItem;
use Currencies;
use Historydiskon;
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
    private $kodeOrder;
    private $pelangganId;
    private $datapengirim;
    private $pembayaran;
    
    public function index()
    {   
        $pengaturan=$this->setting;      

        if ($pengaturan->checkoutType==1) 
        {
            Session::forget('pengiriman');

            if(URL::previous()!=URL::to('checkout') && URL::previous()!=URL::to('pengiriman') && URL::previous()!=URL::to('pembayaran') && URL::previous()!=URL::to('konfirmasi')){                
                Session::forget('besarPotongan');
                Session::forget('diskonId');
                Session::forget('tipe'); 
                Session::forget('tujuan');
                Session::forget('ekspedisiId');
                Session::forget('ongkosKirim');
            }
            $kode = rand(100,200);
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
                ->with('kontak', $this->setting)
                ->with('akun',Akun::find($this->akunId))
                ->with('pajak',Pajak::where('akunId','=',$this->akunId)->first());
            $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Rincian Belanja - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
        }
        elseif ($pengaturan->checkoutType==2) 
        {
            $this->layout->content = View::make('checkout::inquiry1')->with('cart' ,Shpcart::wishlist())
                ->with('provinsi' ,Provinsi::where('negaraId','=',$this->setting->negara)->get())
                ->with('pengaturan' ,$pengaturan)
                ->with('kontak', $this->setting)
                ->with('akun',Akun::find($this->akunId)
                );
            $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Rincian Belanja - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
        }
    }


    public function pengiriman()
    {
        //check session cart dan ekspedisi dan diskon                
        if ($this->setting->checkoutType==1) 
        {
            if(Shpcart::cart()->total_items()==0 || !(Session::has('ekspedisiId'))) 
            {
                return Redirect::to('checkout');
            }
        }
        elseif ($this->setting->checkoutType==2) 
        {
            if(Shpcart::wishlist()->total_items()==0) 
            {
                return Redirect::to('checkout');
            }
        }

       //check ekspedisi
        if ($this->setting->checkoutType==1) 
        {
            if($this->setting->statusEkspedisi==1){
                if(!Session::has('ekspedisiId')){
                     return Redirect::to('checkout');
                }
            }
        }
        
        if(Session::has('message')){            
            echo "<div class='".Session::get('message')."' id='message' style='display:none'>
                <p>".Session::get('text')."</p>
            </div>";
        }       

        if ($this->setting->checkoutType==1) 
        {
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
        elseif ($this->setting->checkoutType==2) 
        {
            $this->layout->content = View::make('checkout::inquiry2')->with('cart' ,Shpcart::wishlist())
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
    }
    public function pembayaran(){
        if ( ! Sentry::check()){
            
            $user = Pelanggan::where('email','=',Input::get('email'))->whereIn('tipe', array(1,2))->where('akunId','=',$this->akunId)->get();
            if($user->count()>0){
                return Redirect::to('pengiriman')->withInput()->with('message','error')->with('text','Alamat email sudah digunakan. Coba yang lain atau silakan login.');
            }            
        }
        if(Request::server('REQUEST_METHOD')=='POST'){
            Session::put('pengiriman', Input::all());               
        }        
        Session::forget('pembayaran');
        $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();      
        $doku_account = \DokuAccount::where('akunId',$this->akunId)->first();
        $this->layout->content = View::make('checkout::step3')->with('cart' ,Shpcart::cart())
            ->with('banks',BankDefault::all())
            ->with('user',(Sentry::check() ? Sentry::getUser():''))
            ->with('banktrans' ,Bank::where('akunId','=',$this->akunId)->where('status','=',1)->get())
            ->with('paypal' , $akun[0])
            ->with('doku_account',$doku_account)
            ->with('creditcard', $akun[1])
            ->with('pembayaran',Session::has('pembayaran')? Session::get('pembayaran'):null)
            ->with('kontak', $this->setting);
        $this->layout->seo = View::make('checkout::seostuff')
            ->with('title',"Checkout - Metode Pembayaran - ".$this->setting->nama)
            ->with('description',$this->setting->deskripsi)
            ->with('keywords',$this->setting->keyword);
    }

    public function konfirmasi()
    {
        if ($this->setting->checkoutType==1) 
        {
            if(Request::server('REQUEST_METHOD')=='POST'){
                Session::put('pembayaran',Input::all());
                $pembayaran = Input::all();
            }
            if(Session::has('pembayaran')){
                $pembayaran =Session::get('pembayaran');
            } 
        }
        if ($this->setting->checkoutType==2) 
        {
            if(Request::server('REQUEST_METHOD')=='POST'){
                Session::put('pengiriman', Input::all());               
            }  
        }
        
        $datapengirim = Session::get('pengiriman');
        $datapengirim['negara'] = Negara::find($datapengirim['negara'])->nama;
        $datapengirim['provinsi'] = Provinsi::find($datapengirim['provinsi'])->nama;
        $datapengirim['kota'] = Kabupaten::find($datapengirim['kota'])->nama;

        if ($this->setting->checkoutType==1) 
        {
            if($datapengirim['statuspenerima']==1)
            {
                $datapengirim['negarapenerima'] = Negara::find($datapengirim['negarapenerima'])->nama;
                $datapengirim['provinsipenerima'] = Provinsi::find($datapengirim['provinsipenerima'])->nama;
                $datapengirim['kotapenerima'] = Kabupaten::find($datapengirim['kotapenerima'])->nama;
            }    
        }

        $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();
        $potongan = 0;
        
        if ($this->setting->checkoutType==1) 
        {
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
                ->with('kontak', $this->setting)
                ->with('pajak',Pajak::where('akunId','=',$this->akunId)->first());
            $this->layout->seo = View::make('checkout::seostuff')
                ->with('title',"Checkout - Ringkasan Order - ".$this->setting->nama)
                ->with('description',$this->setting->deskripsi)
                ->with('keywords',$this->setting->keyword);
        }
        elseif ($this->setting->checkoutType==2) 
        {
            $this->layout->content = View::make('checkout::inquiry3')->with('cart' ,Shpcart::wishlist())
                ->with('datapengirim',$datapengirim)
                ->with('kontak', $this->setting
            );
            $this->layout->seo = View::make('checkout::seostuff')
                ->with('title',"Checkout - Ringkasan Order - ".$this->setting->nama)
                ->with('description',$this->setting->deskripsi)
                ->with('keywords',$this->setting->keyword);
        }
    }

    public function finish()
    {
        if ($this->setting->checkoutType==1) 
        {
            if(Shpcart::cart()->total()==0){
                return Redirect::to('checkout');
            }    
        }
        elseif ($this->setting->checkoutType==2) 
        {
            if(Shpcart::wishlist()->total_items()==0){
                return Redirect::to('checkout');
            }  
        }

        //Generate kd Order
        $this->kodeOrder = $this->generateKodeOrder();
        $this->datapengirim = Session::get('pengiriman');

        if ($this->setting->checkoutType==1) 
        {
            $this->pembayaran = Session::get('pembayaran');
        }
        //save pelanggan
        $this->pelangganId = $this->saveOrGetPelanggan($this->datapengirim);

        if ($this->setting->checkoutType==1) 
        {
            return $this->saveAsOrder();

        }
        elseif ($pengaturan->checkoutType=2) 
        {
            $inquiry = new Inquiry;
            $inquiry->kodeInquiry = $kdOrder;
            $inquiry->pelangganId = $pelangganId;
            $inquiry->total= 0;
            $inquiry->status= 0;
            $inquiry->nama = $datapengirim['nama'];
            $inquiry->telp = $datapengirim['telp'];
            $inquiry->alamat = $datapengirim['alamat'];
            $inquiry->kota = Kabupaten::find($datapengirim['kota'])->nama;    
            $inquiry->pesan = $datapengirim['pesan'];
            $inquiry->akunId = $this->akunId;
            $inquiry->save();

            if($inquiry)
            {
                //tambah det order
                $cart_contents = Shpcart::wishlist()->contents();
                foreach ($cart_contents as $key => $value) {
                    $detinquiry = new DetailInquiry;
                    $detinquiry->inquiryId=$inquiry->id;
                    $detinquiry->opsiSkuId= is_null($value['opsiskuId']) ? '':$value['opsiskuId'];
                    $detinquiry->produkId = $value['produkId'];
                    $detinquiry->qty = $value['qty'];
                    $detinquiry->created_at = date('Y-m-d H:m:s');
                    $detinquiry->updated_at = date('Y-m-d H:m:s');
                    $detinquiry->save();
                }
                //kirim email konfirmasi ke email user
                 $cart ='<table cellpadding="5"><tr>
                                <td>No</td>
                                <td>Nama Produk</td>
                                <td>Varian</td>
                                <td>Qty</td>
                            </tr>';            
                $cart = $cart.View::make('admin.inquiry.listcartinquiry')->with('cart_contents', Shpcart::wishlist()->contents())->With('berat','0');
                $cart = $cart."<tr>     
                            </tr></table>";
                Shpcart::wishlist()->destroy();
                //kirim email order ke pelanggan
                $template = "<p>
                                    Halo {{pelanggan}}</p>
                                <p>
                                    Terimakasih telah berbelanja di {{toko}}.</p>
                                <p>
                                    Detail inquiry anda ID: {{kodeorder}},<br />
                                    Tanggal: {{tanggal}}<br />
                                    Detail Inquiry : {{cart}}</p>
                                <p>
                                    Inquiry anda akan kami proses sesegera mungkin</p>
                                <p>
                                    Salam Hangat, {{toko}}</p>
                                "; 
                $data = array(
                    'pelanggan'=> $inquiry->nama,
                    'toko' => $this->setting->nama,
                    'kodeorder' => $inquiry->kodeInquiry,
                    'tanggal' => date("Y-m-d"),
                    'cart' => $cart
                    );
                $pengirim = $this->datapengirim;
                $pengaturan = $this->setting;
                $email = bind_to_template($data,$template);            
                $subject = bind_to_template($data,'Konfirmasi Inquiry');  
                Mail::later(3,'emails.email',array('data'=>$email), function($message) use ($subject,$datapengirim)
                {   
                    $message->to($datapengirim['email'], $datapengirim['nama'])->subject($subject);
                });
                //kirik email konfirmasi ke email toko
                $subject2 = 'Pemberitahuan Order -- '.bind_to_template($data,'Konfirmasi Inquiry');  
                Mail::later(5,'emails.email',array('data'=>$email), function($message) use ($subject2,$pengaturan)
                {   
                    $message->to($pengaturan->emailAdmin, $pengaturan->nama)->subject($subject2);
                });

                $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();
                            
                Shpcart::wishlist()->destroy();
                Session::forget('tipe');
                Session::forget('pengiriman');     
                
                $this->layout->content = View::make('checkout::inquiry4')->with('datapengirim' ,$datapengirim)
                ->with('inquiry', $inquiry)
                ->with('banktrans', Bank::where('akunId','=',$this->akunId)->where('status','=',1)->get())
                ->with('pengaturan', $this->setting)
                ->with('kontak', $this->setting);

                $this->layout->seo = View::make('checkout::seostuff')
                ->with('title',"Checkout - Finish - ".$this->setting->nama)
                ->with('description',$this->setting->deskripsi)
                ->with('keywords',$this->setting->keyword);
            }
        }
    }

    private function createDokuPayment($order){

        $doku_account = \DokuAccount::where('akunId',$this->akunId)->first();
        
        $basket = '';
        $basket .='Pembayaran Order #'.$order->kodeOrder.','.$order->total.',1,'.$order->total.';';
        /*$total_product = 0;
        foreach ($order->detailorder as $key => $value) {
            $basket .=$value->produk->nama.','.$value->hargaSatuan.','.$value->qty.','.($value->hargaSatuan*$value->qty).';';
            $total_product +=$value->hargaSatuan*$value->qty;
        }
        //check Administration fee,5000.00,1,5000.00
        
        $basket .='Kode Unik,'.($order->total - ($order->ongkosKirim+$total_product)).',1,'.($order->total - ($order->ongkosKirim+$total_product)).';';*/
        $basket .='Administration fee,'.$doku_account->adminFee.',1,'.$doku_account->adminFee.';';

        $total = number_format($order->total + 5000,2,'.','');

        $word =sha1 ($total.$doku_account->sharedKey.$order->kodeOrder);

        $form = '';
        $form .='<FORM NAME="order" METHOD="Post" ACTION="https://apps.myshortcart.com/payment/request-payment/" target="_blank" >';
        $form .='<input type=hidden name="BASKET" value="'.$basket.'">';
        $form .='<input type=hidden name="STOREID" value="'.$doku_account->storeId.'">';
        $form .='<input type=hidden name="TRANSIDMERCHANT" value="'.$order->kodeOrder.'">';
        $form .='<input type=hidden name="AMOUNT" value="'.$total.'">';
        $form .='<input type=hidden name="URL" value="'.url('/checkout/doku/payment').'">';
        $form .='<input type=hidden name="WORDS" value="'.$word.'">';
        $form .='<input type=hidden name="CNAME" value="'.$order->nama.'">';
        $form .='<input type=hidden name="CEMAIL" value="'.$order->pelanggan->email.'">';
        $form .='<input type=hidden name="CWPHONE" value="'.$order->telp.'">';
        $form .='<input type=hidden name="CHPHONE" value="'.$order->telp.'">';
        $form .='<input type=hidden name="CMPHONE" value="'.$order->telp.'">';
        $form .='<input type=hidden name="CCAPHONE" value="'.$order->telp.'">';
        $form .='<input type=hidden name="CADDRESS" value="'.$order->alamat.'">';
        $form .='<input type=hidden name="SADDRESS" value="'.$order->alamat.'">';
        $form .='<input type=hidden name="SZIPCODE" value="'.$order->pelanggan->kodepos.'">';
        $form .='<input type=hidden name="SCITY" value="'.$order->pelanggan->city->nama.'">';
        $form .='<input type=hidden name="SSTATE" value="'.$order->pelanggan->province->nama.'">';
        $form .='<input type=hidden name="SCOUNTRY" value="1">';
        $form .='<input type=hidden name="BIRTHDATE" value="'.$order->pelanggan->tglLahir.'">';
        $form .='<input type=SUBMIT name="SAVE" value="Bayar Dengan Doku MyshopCart">';
        $form .='</form>';
        return $form;
    }

    private function generateKodeOrder()
    {
        $pengaturan = $this->setting;        
        $awal = date('ymd');
        $next_id ='';   
        if ($pengaturan->checkoutType==1) 
        {
            $order = Order::orderBy('created_at', 'desc')->first();
            if(!is_null($order)){            
                $next_id = $order->kodeOrder;      
            }
        }    
        elseif ($pengaturan->checkoutType==2) 
        {
            $inquiry = Inquiry::orderBy('created_at', 'desc')->first();
            if(!is_null($inquiry)){            
                $next_id = $inquiry->kodeInquiry;      
            }   
        }
        
        if($next_id!=''){
            $next_id = substr($next_id,6,10);
            $next_id ++;
        }else{
            $next_id= 1;
        }
        $nol = (str_repeat('0',(4-strlen($next_id))));
        $kdOrder = $awal.$nol.$next_id;
        return $kdOrder;
    }

    private function saveOrGetPelanggan($datapengirim)
    {
        if ( ! Sentry::check()){
            //guest            
            $datapengirim['kotanama'] = Kabupaten::remember(60*24)->find($datapengirim['kota'])->nama;
            $pelangganId =  DB::table('pelanggan')->insertGetId(
                    array(
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
                )  
            );
        }else{
            $pelangganId = Sentry::getUser()->id;
        }
        return $pelangganId;
    }

    private function saveAsOrder(){
        $ekspedisi =Session::get('ekspedisiId');
        $jenispengiriman = Session::get('ekspedisiId');
        $ongkosKirim = Session::get('ongkosKirim');
        $potongan = 0;
            
        //get total order
        if(!is_null(Session::get('diskonId'))){
            if(Session::get('tipe')==1){
                $potongan = Session::get('besarPotongan');                
            }else{
                $potongan = (Shpcart::cart()->total()*Session::get('besarPotongan')/100);
            }
        }
        
        $total = (Shpcart::cart()->total() + Session::get('ongkosKirim')- $potongan) + Session::get('kodeunik');        
        $total = $total + (Pajak::where('akunId','=',$this->akunId)->first()->status==0 ? 0 : $total * Pajak::where('akunId','=',$this->akunId)->first()->pajak / 100);
        
        $order = new Order;
        $order->kodeOrder = $this->kodeOrder;
        $order->tanggalOrder = date('Y-m-d H:m:s');
        $order->pelangganId = $this->pelangganId;
        $order->total= $total;
        $order->status= 0;
        $order->jenisPengiriman = $jenispengiriman;
        $order->ongkoskirim = Session::get('ongkosKirim');
        if($this->datapengirim['statuspenerima']==0)
        {
            $order->nama = $this->datapengirim['nama'];
            $order->telp = $this->datapengirim['telp'];
            $order->alamat = $this->datapengirim['alamat'];
            $order->kota = Kabupaten::remember(60*24)->find($this->datapengirim['kota'])->nama;
        }
        else
        {
            $order->nama = $this->datapengirim['namapenerima'];
            $order->telp = $this->datapengirim['telppenerima'];
            $order->alamat = $this->datapengirim['alamatpenerima'];
            $order->kota = Kabupaten::remember(60*24)->find($this->datapengirim['kotapenerima'])->nama;
        }
        $order->pesan = $this->datapengirim['pesan'];
        $order->noResi = '';
        $order->ekspedisiId = '';
        if(Session::get('pembayaran')['tipepembayaran']=='bank'){
            $pembayaran =1;
        }else if(Session::get('pembayaran')['tipepembayaran']=='paypal'){
            $pembayaran =2;           
        }else if(Session::get('pembayaran')['tipepembayaran']=='creditcard'){
            $pembayaran =3;
        }
        else if(Session::get('pembayaran')['tipepembayaran']=='doku_payment'){
            $pembayaran =4;
        }            
        
        $order->jenisPembayaran = $pembayaran;
        $order->diskonId = Session::has('diskonId') ? Session::get('diskonId') : '';
        $order->akunId = $this->akunId;

        $order->save();

        if($order)
        {
            //cek diskon
            if(Session::has('diskonId'))
            {
                $diskonId = Session::get('diskonId');   
                $diskon = Diskon::find($diskonId);
                $diskon->klaim = $diskon->klaim +1;
                $diskon->save();
                if (Sentry::check()) 
                {
                     HistoryDiskon::insertDiscountHistory($diskonId, Sentry::getUser()->id, $order->id);
                }
            }
            else
            {
                $diskonId='';
            }
            
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

            //pembayaran = paypal
            $paypal_button = "";
            $doku_payment = "";
            $bank_default = null;
            $bank_active = null;
            $akun = null;
            if($order->jenisPembayaran==2){

                $paypal_button = $this->generatePaypalButton();   

            }else if($order->jenisPembayaran==4){
                
                $doku_payment = $this->createDokuPayment($order);

            }else{
                //kirim email konfirmasi ke email user
                $bank_default = \BankDefault::remember(60*24)->all();
                $bank_active = \Bank::where('akunId','=',$this->akunId)->where('status','=',1)->get();  
            }
            $cart_part = View::make('checkout::email.cart')
                            ->with('cart_contents', Shpcart::cart()->contents())
                            ->with('order',$order)
                            ->with('berat','0');

            $pembayaran_part = \View::make('checkout::email.pembayaran')
                                    ->with('banks', $bank_default)
                                    ->with('banktrans', $bank_active)
                                    ->with('order',$order)
                                    ->with('paypalbutton',$paypal_button)
                                    ->with('doku_payment',$doku_payment);
            
            $this->sendEmailOrder($order,$cart_part,$pembayaran_part);
            

            $akun = OnlineAkun::where('akunId','=',$this->akunId)->get();
               
            Shpcart::cart()->destroy();
            Session::forget('diskonId');
            Session::forget('besarPotongan');
            Session::forget('tipe');
            Session::forget('pengiriman');
            Session::forget('pembayaran');
            Session::forget('ekspedisiId');
            Session::forget('ongkosKirim');
            Session::forget('kodeunik');  

            $this->layout->content = View::make('checkout::step5')
                ->with('datapengirim' ,$this->datapengirim)
                ->with('datapembayaran', $pembayaran)
                ->with('order', $order)
                ->with('banks' ,$bank_default)
                ->with('banktrans', $bank_active)
                ->with('paypal',  $akun[0])
                ->with('creditcard' , $akun[1])
                ->with('pengaturan', $this->setting)
                ->with('paypalbutton', $paypal_button)
                ->with('doku_payment', $doku_payment)
                ->with('kontak', $this->setting);

            $this->layout->seo = View::make('checkout::seostuff')
                ->with('title',"Checkout - Finish - ".$this->setting->nama)
                ->with('description',$this->setting->deskripsi)
                ->with('keywords',$this->setting->keyword);
        }
    }

    private function generatePaypalButton($order){
        //buat button paypal.
        $paypal = new \GoPayPal(THIRD_PARTY_CART);
        $paypal->sandbox = false;
        $paypal->openInNewWindow = true;
        $paypal->set('business', $akun[0]->acount);
        $paypal->set('currency_code', 'USD');
        $paypal->set('country', 'US');
        $paypal->set('return', \URL::to('konfirmasiorder/'.$order->id));
        $paypal->set('cancel_return', \URL::to('konfirmasiorder/'.$order->id));
        $paypal->set('notify_url', \URL::to('konfirmasiorder/'.$order->id)); # rm must be 2, need to be hosted online
        $paypal->set('rm', 2); # return by POST
        $paypal->set('no_note', 0);
        $paypal->set('custom', md5(time()));
        $paypal->set('cbt', 'Return to our site to validate your payment!'); # caption override for "Return to Merchant" button                
        $paypal->set('handling_cart', 1); # this overide the individual items' handling "handling_x"
        $paypal->set('tax_cart', $akun[0]->fee);  
        $item = new \GoPayPalCartItem();
        $item->set('item_name', 'Payment for order : #'.$order->kodeOrder);
        $item->set('item_number', '1');
        $total = $order->total;
        if($this->setting->mataUang == 1){
            $total =round($order->total / \OnlineAkun::where('akunId','=',$this->akunId)->first()->rate); 
        }
        $item->set('amount', $total);
        $item->set('quantity', 1);
        $item->set('shipping', 0.1);
        $item->set('handling', 1); # this is overriden by "handling_cart"
        $paypal->addItem($item);
        # If you set your custom button here, PayPal Pay Now button will be displayed.
        $paypal->setButton('<button type="submit">Bayar Dengan Paypal - The safer, easier way to pay online!</button>');
        
        return $paypal->html();
    }

    private function sendEmailOrder($order,$cart_part,$pembayaran_part){
        $data = array(
            'pelanggan'=> $order->nama,
            'pelangganalamat'=> $order->alamat,
            'pelangganphone'=> $order->telp,
            'toko' => $this->setting->nama,
            'kodeorder' => $order->kodeOrder,
            'tanggal' => date("d F Y",strtotime($order->tanggalOrder)).' '.date("g:ha",strtotime($order->tanggalOrder)),
            'cart' => $cart_part,
            'ekspedisi' =>$order->jenisPengiriman,
            'totalbelanja' =>$order->total,
            'phone' => $this->setting->telepon,
            'handphone' => $this->setting->hp,
            'email' => $this->setting->email,
            'pembayaran' => $pembayaran_part
            );
        
        $template_email = \Templateemail::where('akunId','=',$this->akunId)->where('no','=',1)->first();
        $template = \View::make('checkout::email.main');
        
        $pengirim = $this->datapengirim;
        $pengaturan = $this->setting;
        $datapengirim['fromemail']= $this->setting->email;
        $datapengirim['fromtoko']= $this->setting->nama;

        $email = bind_to_template($data,$template);

        $subject = 'Pemberitahuan Order -- '.bind_to_template($data,$template_email->judul); 
        Mail::later(3,'emails.email',array('data'=>$email), function($message) use ($subject,$pengirim)
        {   
            $message->to($pengirim['email'], $pengirim['nama'])->subject($subject);
        });
        //kirik email konfirmasi ke email toko
        $subject2 = 'Pemberitahuan Order -- '.bind_to_template($data,$template_email->judul);  
        Mail::later(5,'emails.email',array('data'=>$email), function($message) use ($subject2,$pengaturan)
        {   
            $message->to($pengaturan->emailAdmin, $pengaturan->nama)->subject($subject2);
        });
    }
    public function getProvinsi($id)
    {
        $pro = \Negara::remember(5)->find($id)->provinsi;
        return \Response::json($pro);
    }
    public function getKabupaten($id)
    {
        $pro = \Provinsi::remember(5)->find($id)->kabupaten;
        return \Response::json($pro);
    }

    public function getKabupatenByName($name)
    {
        $pro = \Kabupaten::select(array(DB::raw('nama as label')))->where('nama','like','%'.$name.'%')->get();
        return $pro;
    }
}
