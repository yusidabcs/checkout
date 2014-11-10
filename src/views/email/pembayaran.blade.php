@if($order->jenisPembayaran==1)
	<h3>Pembayaran : Transfer Bank</h3>
	<table cellpadding="10" cellspacing="0">
	  <thead>
		  <tr>
			  <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Bank</th>
			  <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">No. Rekening</th>
			 	<th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Atas Nama</th>
			  <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Action</th>
		  </tr>
	  </thead>   
	  <tbody>
	  	@foreach($banktrans as $key =>$banktran)
		<tr>
			<td style="border:1px solid #000">
				@foreach($banks as $key => $logoBank)
					@if($banktran->bankDefaultId==$logoBank->id)
						<img src="{{URL::to('http://jarvis-store.com/img/'.$logoBank->logo)}}" width="80">
					@endif
				@endforeach
			</td>
			<td class="center" style="border:1px solid #000">
				@if($banktran->status==0)
					<span style="text-decoration:line-through">{{$banktran->noRekening}}</span>
				@else
					{{$banktran->noRekening}}
				@endif
			</td>
			<td class="center" style="border:1px solid #000">
				@if($banktran->status==0)
					<span style="text-decoration:line-through">{{$banktran->atasNama}}</span>
				@else
					{{$banktran->atasNama}}
				@endif
				
			</td>
			<td class="center" style="border:1px solid #000">
				<a class="btn hps" id="hps" href="#" onclick="deleteBank({{$banktran->id}})">
					<i class="halflings-icon remove"></i>
				</a>
				@if($banktran->status==0)
				<a class="btn" id="update" href="#" onclick="enableBank({{$banktran->id}})" title="Enable Bank">
					<i class="halflings-icon ok"></i>
				</a>
				@endif
			</td>
		</tr>
		@endforeach
	  </tbody>
	</table>
@endif

@if($order->jenisPembayaran==2)
	<h3>Pembayaran : Paypal Payment</h3>
	<p>Silakan melakukan pembayaran dengan paypal Anda secara online via paypal payment gateway. Transaksi ini berlaku jika pembayaran dilakukan minimal (1x24 jam). Klik tombol "Bayar Dengan Paypal" di bawah untuk melanjutkan proses pembayaran.</p>
	{{$paypalbutton}}
@endif


@if($order->jenisPembayaran==4)
	<h3>Pembayaran yg Dipilih : Doku MyShopCart</h3>
	<p>Silakan melakukan pembayaran melalui Doku MyShopCart. Transaksi ini akan di batalkan jika dalam 1x24 jam belum dilakukan pembayaran.</p>
	{{$doku_payment}}
@endif

@if($order->jenisPembayaran==6)
	<h3>Pembayaran : Jarvis Payment</h3>
	<p>Silakan melakukan pembayaran melalui bank channel yang kami sediakan (ATM/I-banking). Pembayaran paling lambat dilakukan setelah 1x12 jam.</p>
	<?php
	$payment = Bcscoder\Jcheckout\FinPay::where('invoice','=',$order->kodeOrder)->first();
	?>
	Kode Pembayaran anda : <h3>{{$payment->payment_code}}</h3>
@endif