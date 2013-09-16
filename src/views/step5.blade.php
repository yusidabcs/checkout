@section('content')
<div id="demos">
	<h2>Finish - Konfirmasi Order</h2>
	<br>
	<div id="psteps_horiz_layout" class="pf-form">
		<div class="row-fluid">
			<div class="span12">
				<div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name">Rincian Belanja</span></div>
				<div class="step-title btn disabled"><span class="step-order">2.</span> <span class="step-name">Data Pembeli Dan Pengiriman</span></div>
				<div class="step-title btn disabled"><span class="step-order">3.</span> <span class="step-name">Metode Pembayaran</span></div>
				<div class="step-title btn disabled"><span class="step-order">4.</span> <span class="step-name">Ringkasan Order</span></div>
				<div class="step-title btn btn-success"><span class="step-order">5.</span> <span class="step-name">Selesai</span></div>
			</div>
		</div>
		<div class="row-fluid box">
			<div class="span12 box-content">
				<div class="span12">
						<div class="well">
							Terima Kasih {{$datapengirim['nama']}} telah berbelanja dengan kami.
							<br>
							<h3>ID ORDER: {{$pengaturan->invoice}}{{$order->kodeOrder}}</h3>Total Harga Belanjaan: {{jadiRupiah($order->total)}}<hr>
							Data pesanan Anda telah berhasil dikirimkan. Sebuah email, yang berisikan informasi pesanan ini dan tahap selanjutnya yang harus dilakukan, telah dikirimkan ke alamat email Anda.
						</div>
				</div>
														
				</div>
			</div>
		</div>
		<div class="row-fluid box">
			<div class="span12 box-content">
				@if($datapembayaran=='1')
						<div class="span12">
							<div class="well">
								<!-- pembayaran via transfer bank -->
								Silahkan anda melakukan pembayaran kesalah satu rekening berikut
								<br>

								<table class="table">
									@foreach($banktrans as $key =>$banktran)
									<tr>
										<td >
											@if($banktran->bankDefaultId=='1')
												<img src="{{URL::to('img/bank/bri.png')}}" style="width:75px; height 75px;">
											@elseif($banktran->bankDefaultId=='2')
												<img src="{{URL::to('img/bank/bca.png')}}" width="80">
											@elseif($banktran->bankDefaultId=='3')
												<img src="{{URL::to('img/bank/mandiri.png')}}" width="80">
											@endif
										</td>
										<td width='90%'><h4>{{ $banktran->bankdefault->nama}} : {{$banktran->noRekening}}</h4> A/n {{$banktran->atasNama}}</td>
									</tr>
									@endforeach									
								</table>
								<hr>
								<p>Setelah melakukan pembayaran anda bisa mengkonfirmasi pembayaran anda disini:</p>
								<a href="{{URL::to('konfirmasiorder/'.$order->id)}}" class="btn theme">Konfirmasi Pembayaran</a>
							</div>
						</div>
					@endif
					@if($datapembayaran=='2')
						<div class="span12">
							<div class="well">
								<!-- pembayaran via paypal -->
								<p>Silakan melakukan pembayaran dengan paypal Anda secara online via paypal payment gateway. Transaksi ini berlaku jika pembayaran dilakukan sebelum 02 Jul 2013 pukul 17:26 WIB (2x24 jam). Klik tombol "Bayar Dengan Paypal" di bawah untuk melanjutkan proses pembayaran.</p>
								{{$paypalbutton}}
							</div>
						</div>
					@endif
					@if($datapembayaran=='3')
						Via Credit Card
					@endif	
			</div>
		</div>
		</div>

	</div>
	@endsection