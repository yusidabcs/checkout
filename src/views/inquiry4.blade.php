@section('content')
<div id="demos">
	<h2>Finish - Konfirmasi Inquiry</h2>
	<br>
	<div id="psteps_horiz_layout" class="pf-form">
		<div class="row-fluid">
			<div class="span12">
				<div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name hidden-phone">Rincian Belanja</span></div>
				<div class="step-title btn disabled"><span class="step-order">2.</span> <span class="step-name hidden-phone">Data Pembeli Dan Pengiriman</span></div>
				<div class="step-title btn disabled"><span class="step-order">3.</span> <span class="step-name hidden-phone">Ringkasan Order</span></div>
				<div class="step-title btn btn-success"><span class="step-order">4.</span> <span class="step-name">Selesai</span></div>
			</div>
		</div>
		<div class="row-fluid box">
			<div class="span12 box-content">
				<div class="span12">
					<div class="well">
						Terima Kasih {{$datapengirim['nama']}} telah berbelanja dengan kami.
						<br>
						<h3>ID INQUIRY: {{$pengaturan->invoice}}{{$inquiry->kodeInquiry}}</h3><hr>
						Data pesanan Anda telah berhasil dikirimkan. Sebuah email, yang berisikan informasi pesanan ini dan tahap selanjutnya yang harus dilakukan, telah dikirimkan ke alamat email Anda.
					</div>
				</div>
														
			</div>
		</div>
	</div>
	<div class="row-fluid box">
			
	</div>

</div>
@endsection