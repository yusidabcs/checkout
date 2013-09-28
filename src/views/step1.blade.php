@section('content')
<div id="demos">
    <h2>Checkout - Rincian Belanja</h2>
    <br>
    <div id="psteps_horiz_layout" class="pf-form">
        <div class="row-fluid">
            <div class="span12">
                <div class="step-title btn btn-success"><span class="step-order">1.</span> <span class="step-name">Rincian Belanja</span></div>
                <div class="step-title btn disabled "><span class="step-order">2.</span> <span class="step-name">Data Pembeli Dan Pengiriman</span></div>
                <div class="step-title btn disabled "><span class="step-order">3.</span> <span class="step-name">Metode Pembayaran</span></div>
                <div class="step-title btn disabled "><span class="step-order">4.</span> <span class="step-name">Ringkasan Order</span></div>
                <div class="step-title btn disabled "><span class="step-order">5.</span> <span class="step-name">Selesai</span></div>
            </div>
        </div>
        <div class="row-fluid box">
            <div class="span12 box-content">
                @if($cart->contents())
                {{Form::open(array('url'=>'pengiriman', 'method' => 'post','name'=>'checkout'))}}
                <div class="step-content">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th class="hidden-phone">Gambar</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th> 
                                <th width="10"></th>                                          
                            </tr>
                        </thead>   
                        <tbody>
                            @foreach ($cart->contents() as $key => $item) 
                            <tr id="cart{{$item['rowid']}}">
                                <td class="hidden-phone"><div class="cart-img pull-left">{{HTML::image(getPrefixDomain().'/produk/thumb/'.$item['image'],'',array('width'=>'75px','height'=>'75px'))}}</div></td>
                                <td class="center">
                                    <a href="#"><h5>{{$item['name']}}</h5></a>
                                    <!-- Check if this cart item has options. -->
                                    @if ($cart->has_options($item['rowid']))
                                    <small>
                                        <ul class="unstyled">
                                            @foreach ($cart->item_options($item['rowid']) as $option_name => $option_value)
                                            <li>- <small>{{ $option_name }}: {{ $option_value }}</small></li>
                                            @endforeach
                                        </ul>
                                    </small>
                                    @endif
                                </td>
                                <td><input style="width: 60px;" type="number" class="span3 cartqty" placeholder="Qty" value="{{$item['qty']}}" name='{{ $item['rowid'] }}' min="1"></td>
                                <td>{{ jadiRupiah($item['price'])}}</strong></td> 
                                <td>
                                    <span class="{{ $item['rowid'] }}"><strong>{{ jadiRupiah($item['price'] * $item['qty'])}}</strong></span>
                                </td>
                                <td><a onclick="deletecart({{ "'".$item['rowid']."'" }})" href="javascript:void(0);"><i class="halflings-icon trash halflings-icon"></i></a></td>                                     
                            </tr>
                            @endforeach                                                            
                            <tr>
                                <td colspan="2" ></td>
                                <td class="hidden-phone" colspan="1" ></td>
                                <td class="center">
                                    Subtotal
                                </td> 
                                <td colspan="2"><span class="price" id='subtotalcart'>{{jadiRupiah(Shpcart::cart()->total())}}</span></td>                             
                            </tr> 
                            <tr>
                                <td colspan="4">
                                    <div class="well">
                                        <input type="hidden" id="statusPengiriman" value="{{$pengaturan->statusEkspedisi}}">
                                        <input type="hidden" id="statusEkspedisi" value="{{$statusEkspedisi}}">
                                        <input type="hidden" id="ekspedisilist" value="{{$ekspedisi!=null? $ekspedisi['ekspedisi'].';'.$ekspedisi['tarif']:''}}">
                                        @if($pengaturan->statusEkspedisi==1)
                                        <h4>Biaya Pegiriman</h4>
                                        <small>Masukkan kota tujuan anda untuk menghitung biaya pengiriman</small><br><br>
                                        <div class="form-horizontal">
                                            <input style="width: 50%;" type="text" class="input" id='tujuan' placeholder="Kota tujuan pengiriman..." value="{{$ekspedisi!=null?$ekspedisi['tujuan']:''}}">
                                            <button type="button" class="btn" id='ekspedisibtn'>Cari</button>
                                        </div>
                                        <br>
                                        <div id='ekspedisiplace'>
                                            {{$ekspedisi!=null? "- ".$ekspedisi['ekspedisi']."<br><br>":''}}
                                        </div>
                                        <small style="font-style: italic;">(*) Bila kota anda tidak ditemukan atau tidak ada dalam daftar, pilihlah kota yang terdekat</small>
                                        @endif                                          

                                    </div>
                                </td>
                                <td colspan="2"><span id='ekspedisitext'>{{$pengaturan->statusEkspedisi==2 ?'<strong>Free Shipping</strong>' : ($pengaturan->statusEkspedisi==3?'Pengiriman Menyusul':jadiRupiah($ekspedisi!=null?$ekspedisi['tarif']:0))}}</span></td>                             
                            </tr> 
                            <tr>
                                <td colspan="4">
                                    <div class="well">
                                        <h4>Kode Diskon</h4>
                                        <small>Gunakan kode kupon pada kolom dibawah jika ada</small><br><br>
                                        <div class="form-horizontal">
                                            <input style="width: 50%;" type="text" class="input" placeholder="Kode kupon..." name='kodeplace' id='kuponplace' value="{{$diskon!=null? $diskon['diskonId']->kode:''}}" {{$diskon!=null? 'disabled':''}}>
                                            <button type="submit" class="btn" id='kuponbtn'>{{$diskon!=null? 'Cancel':'Pakai Kupon'}}</button>
                                            {{$diskon!=null? '<input type="hidden" id="diskonstatus" value="1">':''}}
                                            
                                        </div>

                                    </div>
                                </td>
                                <td colspan="2"><span id='kupontext'>{{$diskon!=null?jadiRupiah($diskon['besarPotongan']):jadiRupiah(0)}}</span></td>                             
                            </tr> 
                            <tr>
                                <td colspan="2" ></td>
                                <td class="hidden-phone" colspan="1" ></td>
                                <td class="center">
                                    Pajak
                                </td> 
                                <td colspan="2"><span id='pajaktext'>{{Pajak::all()->first()->status==0? '<em>pajak non-aktif</em>' : Pajak::all()->first()->pajak.'%'}}</td>                            
                            </tr> 
                            <tr>
                                <td colspan="2" ></td>
                                <td class="hidden-phone" colspan="1" ></td>
                                <td class="center">
                                    Kode Unik
                                </td> 
                                <td colspan="2"><span id='kodeuniktext'>{{jadiRupiah($kodeunik)}}</td>                            
                            </tr> 
                            <tr class="success">
                                <td colspan="2" ></td>
                                <td class="hidden-phone" colspan="1" ></td>
                                <td class="center">
                                    <h3>Total</h3>
                                </td> 
                                <td colspan="2"><h3><span id='totalcart'>
                                    {{jadiRupiah(Shpcart::cart()->total())}}</span></h3></td>                            
                                </tr>                              
                            </tbody>
                        </table>
                        {{Form::close()}}
                        @if ( !Sentry::check())
                        <div class="row-fluid">
                            <div class="span6 well">
                                <h4>Anggota</h4>
                                <small>Silahkan login untuk mempercepat transaksi</small><hr>
                                <form class="form" action="{{URL::to('member/login')}}" method="post">
                                    <div class="control-group">
                                    <div class="controls">
                                      <input type="email" name="email" id="inputEmail" placeholder="Email" required>
                                    </div>
                                    </div>
                                    <div class="control-group">
                                    <div class="controls">
                                      <input type="password" name="password" id="inputPassword" placeholder="Password" required>
                                    </div>
                                    </div>
                                    <div class="control-group">
                                    <div class="controls">
                                      <button type="submit" class="btn theme">Login</button>
                                      <a href="{{URL::to('forgetpassword')}}" class="btn btn-link">Forget password?</a>
                                    </div>
                                    </div>
                                </form>
                            </div>
                            <div class="span6 well" style="height:287px">
                                <h4>Pelanggan baru</h4>
                                <small>Anda tidak perlu menjadi member untuk berbelanja. Silakan klik tombol "Lanjut ke data pengiriman" untuk melanjutkan. Untuk mempercepat proses belanja dimasa mendatang plus mendapatkan sejumlah tawaran menarik lainnya, anda dapat mendaftar menjadi member dihalaman pendafaran/registrasi.</small><br><br>
                                <a href="{{URL::to('produk')}}" class="btn btn-warning hidden-phone">Lihat Produk Lainnya</a>
                                <a href="{{URL::to('produk')}}" class="btn btn-warning hidden-desktop" style="float: left;">Back</a>
                                <button type="submit" class="btn btn-info next-button">Lanjut sebagai Guest</button>
                                <div class="clear"></div>
                            </div>
                        </div>
                        @else
                        <div class="row-fluid">
                            <div class="span12">
                                <input type="button" class="btn btn-info pull-right" value="Lanjutkan" id="form1">
                            </div>
                        </div>
                        @endif

                    </div>
                    @else
                    <div class="alert alert-warning"><center>Keranjang Belanja Masih Kosong.</center></div>
                    @endif

                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
