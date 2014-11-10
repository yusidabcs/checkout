<p>Segera lakukan pembayaran ke salah satu rekening di bawah agar order anda dapat diproses secepatnya.</p>
<table id="items" cellpadding="10" style="border-collapse: collapse;clear: both;border: 1px solid black;">
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
		<td >
			@foreach($banks as $key => $logoBank)
				@if($banktran->bankDefaultId==$logoBank->id)
					<img src="{{URL::to('img/'.$logoBank->logo)}}" width="80">
				@endif
			@endforeach
		</td>
		<td class="center">
			@if($banktran->status==0)
				<span style="text-decoration:line-through">{{$banktran->noRekening}}</span>
			@else
				{{$banktran->noRekening}}
			@endif
		</td>
		<td class="center">
			@if($banktran->status==0)
				<span style="text-decoration:line-through">{{$banktran->atasNama}}</span>
			@else
				{{$banktran->atasNama}}
			@endif
		</td>
	</tr>
	@endforeach
  </tbody>
</table>
<p>Setelah melakukan pembayaran mohon segera Konfirmasi Pembayaran anda agar kami bisa segera mengirimkan pesanan anda. Jika ada pertanyaan, saran dan keluhan mengenai toko online kami, anda bisa menghubingi kami melalui website kami.<p>