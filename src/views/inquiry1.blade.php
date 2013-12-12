@section('content')
@if(Session::has('login'))
<div id="message" class="success">
Selamat, anda berhasil login.
</div>
@endif
@if(Session::has('error'))
<div id="message" class="error">
    {{Session::get('error')}}
</div>
@endif
<div id="demos">
    <h2>Checkout - Rincian Belanja</h2>
    <br>
    <div id="psteps_horiz_layout" class="pf-form">
        <div class="row-fluid">
            <div class="span12">
                <div class="step-title btn btn-success"><span class="step-order">1.</span> <span class="step-name">Rincian Belanja</span></div>
                <div class="step-title btn disabled "><span class="step-order">2.</span> <span class="step-name hidden-phone">Data Pembeli</span></div>
                <div class="step-title btn disabled "><span class="step-order">4.</span> <span class="step-name hidden-phone">Ringkasan Order</span></div>
                <div class="step-title btn disabled "><span class="step-order">5.</span> <span class="step-name hidden-phone">Selesai</span></div>
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
                                <td><a onclick="deletecart({{ "'".$item['rowid']."'" }})" href="javascript:void(0);"><i class="halflings-icon trash halflings-icon"></i></a></td>                                     
                            </tr>
                            @endforeach                                                                                                                  
                        </tbody>
                    </table> 
                        <table>
                            <tr>
                                <td>
                                    
                                </td>
                                <td>
                                    
                                </td>
                                                         
                            </tr> 
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
