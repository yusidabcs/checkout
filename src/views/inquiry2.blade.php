@section('content')
<div id="demos">
    <h2>Checkout - Data Pembeli</h2>
    <br>
    <div id="psteps_horiz_layout" class="pf-form">
        <div class="row-fluid">
            <div class="span12">
                <div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name hidden-phone">Rincian Belanja</span></div>
                <div class="step-title btn btn-success"><span class="step-order">2.</span> <span class="step-name">Data Pembeli</span></div>
                <div class="step-title btn disabled"><span class="step-order">3.</span> <span class="step-name hidden-phone">Ringkasan Inquiry</span></div>
                <div class="step-title btn disabled"><span class="step-order">4.</span> <span class="step-name hidden-phone">Selesai</span></div>
            </div>
        </div>
        <div class="row-fluid box">
            <div class="span12 box-content">
                <div class="step-content">
                    <form action="{{URL::to('konfirmasi')}}" name='pengiriman' method='post'>
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
                            

                        </div>
                        <div class="span6 well">
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