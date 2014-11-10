@section('content')
<div id="demos">
	<h2>Checkout - Metode Pembayaran</h2>
	<br>
	<div id="psteps_horiz_layout" class="pf-form">
		<div class="row-fluid">
			<div class="span12">
				<div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name hidden-phone">Rincian Belanja</span></div>
				<div class="step-title btn disabled"><span class="step-order">2.</span> <span class="step-name hidden-phone">Data Pembeli Dan Pengiriman</span></div>
				<div class="step-title btn btn-success"><span class="step-order">3.</span> <span class="step-name">Metode Pembayaran</span></div>
				<div class="step-title btn disabled"><span class="step-order">4.</span> <span class="step-name hidden-phone">Ringkasan Order</span></div>
				<div class="step-title btn disabled"><span class="step-order">5.</span> <span class="step-name hidden-phone">Selesai</span></div>
			</div>
		</div>
		<div class="row-fluid box">
			<div class="span12 box-content">
				<form class="form-horizontal" action="{{URL::to('konfirmasi')}}" name='pembayaran' method='post'>
				<div class="step-content">
					<div class="row-fluid">
                       <div class="span4">
                       	Pilih Salah Satu Jenis Pembayaran:
                       	<hr>
						<label class="radio">
							<input type="radio" name="tipepembayaran" id="optionsRadios1" value="bank" {{$pembayaran!=null? ($pembayaran['tipepembayaran']=='bank'?'checked':''):'checked' }}>
							  Transfer Bank<br>
						</label><br>
						@if($paypal->aktif)
						<label class="radio">
						  <input type="radio" name="tipepembayaran" id="optionsRadios2" value="paypal" {{$pembayaran!=null? ($pembayaran['tipepembayaran']=='paypal'?'checked':''):'' }}>
						  Paypal
						</label><br>
						@endif
						@if($creditcard->aktif)
						<label class="radio">
						  <input type="radio" name="tipepembayaran" id="optionsRadios2" value="creditcard" {{$pembayaran!=null? ($pembayaran['tipepembayaran']=='creditcard'?'checked':''):'' }}>
						  Kartu Kredit
						</label><br>
						@endif

						@if($doku_account)
							@if($doku_account->status==1)
							<label class="radio">
							  <input type="radio" name="tipepembayaran" id="optionsRadios2" value="doku_payment" {{$pembayaran!=null? ($pembayaran['tipepembayaran']=='doku_payment'?'checked':''):'' }}>
							  Doku MyShopCart
							</label><br>
							@endif
						@endif

                       </div>
                       <div class="span8">
                       		<div class="well" style="display:none" id="bank">
                       			<table class="table table-striped">
								  <thead>
									  <tr>
										  <th>Bank</th>
										  <th>No. Rekening</th>
										  <th>Atas Nama</th>                                       
									  </tr>
								  </thead>   
								  <tbody>
								  	@foreach($banktrans as $key =>$banktran)
									<tr>
										<td class="center">
											@foreach($banks as $key => $logoBank)
												@if($banktran->bankDefaultId==$logoBank->id)
													<img src="{{URL::to('img/'.$logoBank->logo)}}" width="80">
												@endif
											@endforeach
										</td>
										<td class="center">{{$banktran->noRekening}}</td>
										<td class="center">{{$banktran->atasNama}}</td>                   
									</tr>
									@endforeach
								  </tbody>
							 </table>
                       		</div>
                       		@if($paypal->aktif)
							<div class="well" style="display:none" id="paypal">
								<p>Silakan melakukan pembayaran dengan paypal Anda secara online via paypal payment gateway. Transaksi ini berlaku jika pembayaran dilakukan maximal (2x24 jam). </p>
                       		</div>
							@endif
							@if($creditcard->aktif)
							<div class="well" style="display:none" id="creditcard">
                       		</div>
							@endif

							@if($doku_account)
								@if($doku_account->status==1)
								<div class="well" style="display:none" id="doku_payment">
									<img src="{{url('packages/yusidabcs/checkout/img/doku-myshopcart.png')}}" class="img">
									<hr>
									<p>DOKU MyShortCart is the easiest and fastest way to sell your services and products online. It offers a simpler way to sell directly to your audience via platforms like emails, Facebook, twitter, Instagram, blogs, or YouTube, by simply clicking on a link that will lead to a secure payment page. (<a href="http://doku.com/my-short-cart" target="_blank">Doku MyShopCart</a>)</p>
									<hr>
									Pembayaran tersedia via:<br><br>
									<img src="{{url('packages/yusidabcs/checkout/img/doku-payment.png')}}" class="img">
                       			</div>
								@endif
							@endif
                       </div>
                    </div>					
				</div>

				<div style="clear:both;"></div>
				<button class="next-button btn btn-info" type="submit">Lanjut <i class="icon-arrow-right"></i></button>
				<a href="{{URL::to('pengiriman')}}" class="back-button btn btn-warning"><i class="icon-arrow-left"></i> Kembali</a
				</form>
			</div>
		</div>
	</div>

</div>
@endsection