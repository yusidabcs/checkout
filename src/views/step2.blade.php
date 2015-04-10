@section('content')
<div id="demos">
    <h2>Checkout - Data Pembeli dan Pengiriman</h2>
    <br>
    <div id="psteps_horiz_layout" class="pf-form">
        <div class="row-fluid">
            <div class="span12">
                <a href="{{URL::to('checkout')}}" data-pjax><div class="step-title btn span3"><span class="step-order">1.</span> <span class="step-name hidden-phone">Rincian Belanja</span></div></a>
                <div class="step-title btn btn-success span3"><span class="step-order">2.</span> <span class="step-name">Data Pembeli</span></div>
                <div class="step-title btn disabled span3"><span class="step-order">3.</span> <span class="step-name hidden-phone">Metode Pembayaran</span></div>
                <div class="step-title btn disabled span3"><span class="step-order">4.</span> <span class="step-name hidden-phone">Ringkasan Order</span></div>
            </div>
        </div>
        <div class="row-fluid box">
            <div class="span12 box-content">
                <div class="step-content">
                    <form action="{{URL::to('pembayaran')}}" name='pengiriman' method='post'>
                    <div class="row-fluid">
                        <div class="span6 well">
                            <div class="control-group">
                                <label class="control-label" for="inputEmail" > Nama</label>
                                <div class="controls">
                                  <input class="span10" type="text" name='nama' value='{{$user ? $user->nama : (Input::old("nama")? Input::old("nama") :($usertemp!=null?$usertemp["nama"]:''))}}' required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Email</label>
                                <div class="controls">
                                  <input type="text" class="span10" id="email" name='email' value='{{$user ? $user->email :(Input::old("email")? Input::old("email") :($usertemp!=null?$usertemp["email"]:''))}}' required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Alamat</label>
                                <div class="controls">
                                  <textarea class="span10" name='alamat' required>{{$user ? $user->alamat :(Input::old("alamat")? Input::old("alamat") :($usertemp!=null?$usertemp["alamat"]:''))}}</textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Negara</label>
                                <div class="controls" >

                                    <select id="negara" name="negara" data-select="chosen" style="width:50%">
                                        <option value=""> Pilih negara </option>
                                        @foreach($negara as $item)
                                        <option value="{{$item->id}}" {{ array_key_exists('negara',$ekspedisi) ? ($ekspedisi['negara'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Provinsi</label>
                                <div class="controls" id="provinsiPlace">
                                    
                                    <select id="provinsi" name="provinsi" data-select="chosen" style="width:50%">
                                        <option value=""> Pilih provinsi </option>
                                        @foreach($provinsi as $item)
                                        <option value="{{$item->id}}" {{ array_key_exists('provinsi',$ekspedisi) ? ($ekspedisi['provinsi'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Kota</label>
                                <div class="controls" id="kotaPlace">
                                    <select id="kota" name="kota" data-select="chosen" style="width:50%" data-status="{{$pengaturan->statusEkspedisi}}">
                                        <option value=""> Pilih kota </option>
                                        @if(array_key_exists('provinsi',$ekspedisi))
                                            @if($ekspedisi['provinsi'] != NULL || $ekspedisi['provinsi'] != '')
                                                @foreach($provinsi->find($ekspedisi['provinsi'])->kabupaten as $item)
                                                <option value="{{$item->id}}" {{ array_key_exists('kota',$ekspedisi) ? ($ekspedisi['kota'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                                @endforeach
                                            @endif
                                        @endif
                                        
                                    </select>

                                </div>
                            </div>
                            <!--  -->
                            <div class="control-group">
                            <label class="control-label" for="inputEmail"> Kode Pos</label>
                            <div class="controls">
                              <input class="span6" type="text" name='kodepos' value='{{$user ? $user->kodepos :(Input::old("kodepos")? Input::old("kodepos") :($usertemp!=null?$usertemp["kodepos"]:''))}}' required>
                            </div>
                            </div>
                            <div class="control-group">
                            <label class="control-label" for="inputEmail"> Telepon / HP</label>
                            <div class="controls">
                              <input class="span10" type="text" name='telp' value='{{$user ? $user->telp :(Input::old("telp")? Input::old("telp") :($usertemp!=null?$usertemp["telp"]:''))}}' placeholder="087xxxxxx" required>
                            </div>
                            </div>
                            <div class="control-group">
                            <label class="control-label" for="inputEmail"> Pesan</label>
                            <div class="controls">
                              <textarea class="span10" name="pesan">{{Input::old("pesan")}}{{($usertemp!=null?$usertemp["pesan"]:'')}}</textarea>
                            </div>
                            </div>

                        </div>
                        <div class="span6 well">
                            <h4>Data Penerima</h4>
                            <label class="radio">
                                <input type="radio" checked name='statuspenerima' value="0" {{($usertemp!=null?($usertemp["statuspenerima"]==0?'checked':''):'')}}> Data penerima sama dengan data pembeli                                
                            </label><br>
                            <label class="radio">
                                <input type="radio" name='statuspenerima' value="1" {{($usertemp!=null?($usertemp["statuspenerima"]=='1'?'checked':''):'')}}> Data penerima berbeda
                            </label>
                            <hr>
                            <div class="well" id='datapenerima'>
                                <div class="control-group">
                                    <label class="control-label" for="inputEmail">Nama Penerima</label>
                                    <div class="controls">
                                        <input class="span10" type="text" name='namapenerima' value="{{($usertemp!=null?$usertemp["namapenerima"]:'')}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputAlamat">Alamat</label>
                                    <div class="controls">
                                        <textarea class="span10" name='alamatpenerima'>{{($usertemp!=null?$usertemp["alamatpenerima"]:'')}}</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputTelepon">Telepon</label>
                                    <div class="controls">
                                        <input class="span10" type="text" name='telppenerima' value="{{($usertemp!=null?$usertemp["telppenerima"]:'')}}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputNegara">Negara</label>
                                    <div class="controls">
                                        
                                        <select id="negarapenerima" name="negarapenerima" data-select="chosen" style="width:50%">
                                            <option value=""> Pilih negara </option>
                                            @foreach($negara as $item)
                                            <option value="{{$item->id}}" {{ array_key_exists('negara',$ekspedisi) ? ($ekspedisi['negara'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputProvinsi">Provinsi</label>
                                    <div class="controls">
                                       
                                       <select id="provinsipenerima" name="provinsipenerima" data-select="chosen" style="width:50%"> 
                                            <option value=""> Pilih provinsi </option>
                                            @foreach($provinsi as $item)
                                            <option value="{{$item->id}}" {{ array_key_exists('provinsi',$ekspedisi) ? ($ekspedisi['provinsi'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputKota">Kota</label>
                                    
                                    <div class="controls">
                                        <select id="kotapenerima" name="kotapenerima" data-select="chosen" style="width:50%" data-status="{{$pengaturan->statusEkspedisi}}">
                                            <option value=""> Pilih kota </option>
                                            @if(array_key_exists('provinsi',$ekspedisi))
                                                @if($ekspedisi['provinsi'] != NULL || $ekspedisi['provinsi'] != '')
                                                    @foreach($provinsi->find($ekspedisi['provinsi'])->kabupaten as $item)
                                                    <option value="{{$item->id}}" {{ array_key_exists('kota',$ekspedisi) ? ($ekspedisi['kota'] == $item->id ? 'selected' : '') : ''}}> {{$item->nama}} </option>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputKodepos">Kodepos</label>
                                    <div class="controls">
                                        <input type="text" id="inputKodepos" class="span10" name="kodepospenerima" value="{{($usertemp!=null?$usertemp["kodepospenerima"]:'')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="clear:both;"></div>
                    <button class="next-button btn btn-info" type="submit">Lanjut <i class="icon-arrow-right"></i></button>
                    <a href="{{URL::to('checkout')}}" class="back-button btn btn-warning"><i class="icon-arrow-left"></i> Kembali</a
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
            <div class="span12 box-content">
                <h2>Rincian Belanja</h2>
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>                                                       
                                <tr>
                                    <td class="center">
                                        Subtotal
                                    </td> 
                                    <td colspan="2"><span class="price" id='subtotalcart'>{{price(Shpcart::cart()->total())}}</span></td>                             
                                </tr>
                                <tr>
                                    <td class="center">
                                        Ongkos Kirim
                                    </td> 
                                    <td colspan="2">

                                        <span id='ekspedisiname'>
                                            {{ strtoupper($ekspedisi['ekspedisi']) }} <br>
                                        </span><br>
                                        <span id='ekspedisitext'>


                                            {{ 
                                            $pengaturan->statusEkspedisi==2 ? 

                                            '<strong>Free Shipping</strong>' 
                                            : 
                                            ( $pengaturan->statusEkspedisi == 3 ? 
                                            'Pengiriman Menyusul'
                                            :
                                            price($ekspedisi != null ? $ekspedisi['tarif'] : 0 ))
                                        }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="center">
                                    Potongan/diskon
                                </td> 
                                <td colspan="2"><span id='kupontext'>{{$diskon!=null?price($diskon['besarPotongan']):price(0)}}</span></td>               
                            </tr>    
                            <tr>
                                <td class="center">
                                    Pajak
                                </td> 
                                <td colspan="2"><span id='pajaktext'>{{$pajak->status==0? '<span class="label label-success">non-aktif</span>' : $pajak->pajak.'%'}}</td>                            
                            </tr> 
                            <tr>
                                <td>
                                    Kode Unik
                                </td> 
                                <td colspan="2"><span id='kodeuniktext'>{{price($kodeunik)}}</td>                            
                            </tr> 
                            <tr class="success">
                                <td class="center">
                                    <h3>Total</h3>
                                </td> 
                                <td colspan="2"><h3><span id='totalcart'>
                                    {{price(Shpcart::cart()->total())}}</span></h3></td>                            
                                </tr>                                                         
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
        <hr>
</div>

<div id="update-ekspedisi" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
  <div class="modal-body">
    <h2>Pilih Ekspedisi</h2>
    <div id='ekspedisiplace'>
        {{$ekspedisi!=null? "- ".$ekspedisi['ekspedisi']." (".price_format($ekspedisi['tarif']).")<br><br>":''}} 
    </div>
  </div>
</div>
@endsection