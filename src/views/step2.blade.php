@section('content')
<div id="demos">
    <h2>Checkout - Data Pembeli dan Pengiriman</h2>
    <br>
    <div id="psteps_horiz_layout" class="pf-form">
        <div class="row-fluid">
            <div class="span12">
                <div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name">Rincian Belanja</span></div>
                <div class="step-title btn btn-success"><span class="step-order">2.</span> <span class="step-name">Data Pembeli Dan Pengiriman</span></div>
                <div class="step-title btn disabled"><span class="step-order">3.</span> <span class="step-name">Metode Pembayaran</span></div>
                <div class="step-title btn disabled"><span class="step-order">4.</span> <span class="step-name">Ringkasan Order</span></div>
                <div class="step-title btn disabled"><span class="step-order">5.</span> <span class="step-name">Selesai</span></div>
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
                                    {{Form::select('negara',array('' => '-- Pilih Negara --') + $negara , ($user ? $user->negara :(Input::old("negara")? Input::old("negara") :($usertemp!=null?$usertemp["negara"]:''))), array('required'=>'', 'id'=>'negara'))}}
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Provinsi</label>
                                <div class="controls" id="provinsiPlace">
                                    {{Form::select('provinsi',array('' => '-- Pilih Provinsi --') + $provinsi , ($user ? $user->provinsi :(Input::old("provinsi")? Input::old("provinsi") :($usertemp!=null?$usertemp["provinsi"]:''))),array('required'=>'','id'=>'provinsi'))}}
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputEmail"> Kota</label>
                                <div class="controls" id="kotaPlace">
                                    {{Form::select('kota',array('' => '-- Pilih Kota --') + $kota , ($user ? $user->kota :(Input::old("kota")? Input::old("kota") :($usertemp!=null?$usertemp["kota"]:''))),array('required'=>'','id'=>'kota'))}}
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
                              <input class="span10" type="text" name='telp' value='{{$user ? $user->telp :(Input::old("telp")? Input::old("telp") :($usertemp!=null?$usertemp["telp"]:''))}}' required>
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
                                         {{Form::select('negarapenerima',array('' => '-- Pilih Negara --') + $negara , ((Input::old("negarapenerima")? Input::old("negarapenerima") :"")).($usertemp!=null?$usertemp["negarapenerima"]:''), array( 'id'=>'negarapenerima','class="input"'))}}
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputProvinsi">Provinsi</label>
                                    <div class="controls">
                                       {{Form::select('provinsipenerima',array('' => '-- Pilih Provinsi --') + $provinsi , ((Input::old("provinsipenerima")? Input::old("provinsipenerima") :"")).($usertemp!=null?$usertemp["provinsipenerima"]:''),array('id'=>'provinsipenerima'))}}
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputKota">Kota</label>
                                    <div class="controls">
                                        {{Form::select('kotapenerima',array('' => '-- Pilih Kota --') + $kota,((Input::old("kotapenerima")? Input::old("kotapenerima") :"")).($usertemp!=null?$usertemp["kotapenerima"]:''), array('id'=>'kotapenerima'))}}
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

</div>
@endsection