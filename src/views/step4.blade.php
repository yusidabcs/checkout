@section('content')
<div id="demos">
	<h2>Checkout - Ringkasan Order</h2>
	<br>
	<div id="psteps_horiz_layout" class="pf-form">
		<div class="row-fluid">
			<div class="span12">
				<div class="step-title btn disabled"><span class="step-order">1.</span> <span class="step-name hidden-phone">Rincian Belanja</span></div>
				<div class="step-title btn disabled"><span class="step-order">2.</span> <span class="step-name hidden-phone">Data Pembeli Dan Pengiriman</span></div>
				<div class="step-title btn disabled"><span class="step-order">3.</span> <span class="step-name hidden-phone">Metode Pembayaran</span></div>
				<div class="step-title btn btn-success"><span class="step-order">4.</span> <span class="step-name">Ringkasan Order</span></div>
				<div class="step-title btn disabled"><span class="step-order">5.</span> <span class="step-name hidden-phone">Selesai</span></div>
			</div>
		</div>
		<div class="row-fluid box">
			<div class="span12 box-content">
				<div class="step-content">
					<table class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Gambar</th>
								<th>Nama Produk</th>
								<th>Qty</th>
								<th>Harga</th>
								<th>Total</th>      
							</tr>
						</thead>   
						<tbody>
							@foreach ($cart->contents() as $item)
							<tr>
								<td>
									{{HTML::image(getPrefixDomain().'/produk/thumb/'.$item['image'],'',array('width'=>'75px','height'=>'75px'))}}
								</td>
								<td>
									<div class="item pull-left">
										<span><a href="#">{{$item['name']}}</a> </span>
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
									</div>
								</td>	
								<td>{{$item['qty']}}</td>								
								<td><strong>{{jadiRupiah($item['price'])}}</strong></td>
								<td><strong>{{jadiRupiah($item['qty'] * $item['price'])}}</strong></td>
							</tr>
							@endforeach
							<tr>
								<td colspan="4" style="text-align: right">
									<div class="item">
										Biaya Pengiriman : ({{$dataekspedisi}})
									</div>
								</td>
								<td>{{jadiRupiah(Session::get('ongkosKirim'))}}</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right">
									<div class="item">
										Potongan Kupon {{$kodekupon=='' ? '':'('.$kodekupon.')' }}:
									</div>
								</td>
								<td>- {{jadiRupiah($diskon)}}</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right">
									<div class="item">
										Kode Unik:
									</div>
								</td>
								<td>{{jadiRupiah($kodeunik)}}</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right">
									<div class="item">
										Pajak
									</div>
								</td>
								<td>{{Pajak::all()->first()->status==0? '<span class="label label-success">non-aktif</span>' : Pajak::all()->first()->pajak.'%'}}</td>
							</tr>
							<tr>
								<td colspan="4" style="text-align: right"><div class="item">Total :</div></td>
								<td>									
									<strong>{{jadiRupiah($total)}}</strong></td>
								</tr>                           
							</tbody>
						</table>
						<div class="row-fluid">
							<div class="span6">
								<h4>Data Pembeli</h4>
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td><strong>Nama</strong></td>
											<td>{{$datapengirim['nama']}}</td>
										</tr>
										<tr>
											<td><strong>Email</strong></td>
											<td>{{$datapengirim['email']}}</td>
										</tr>
										<tr>
											<td><strong>Alamat</strong></td>
											<td>{{$datapengirim['alamat']}}</td>
										</tr>
										<tr>
											<td><strong> Negara</strong></td>
											<td>{{$datapengirim['negara']}}</td>
										</tr>
										<tr>
											<td><strong>Provisi</strong></td>
											<td>{{$datapengirim['provinsi']}}</td>
										</tr>
										<tr>
											<td><strong>Kota</strong></td>
											<td>{{$datapengirim['kota']}}</td>
										</tr>
										<tr>
											<td><strong>Kode Pos</strong></td>
											<td>{{$datapengirim['kodepos']}}</td>
										</tr>
										<tr>
											<td><strong>Telp / HP</strong></td>
											<td>{{$datapengirim['telp']}}</td>
										</tr>
										<tr>
											<td><strong>Pesan</strong></td>
											<td>{{$datapengirim['pesan']}}</td>
										</tr>
									</tbody>
								</table>								
							</div>
							<div class="span6">
								<h4>Data Pembeli</h4>
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td><strong>Nama</strong></td>
											<td>{{($datapengirim['statuspenerima']==1) ?  $datapengirim['namapenerima'] : $datapengirim['nama']}}</td>
										</tr>
										<tr>
											<td>Telp / HP</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['telppenerima'] : $datapengirim['telp']}}</td>
										</tr>
										<tr>
											<td>Alamat</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['alamatpenerima'] : $datapengirim['alamat']}}</td>
										</tr>
										<tr>
											<td>Negara</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['negarapenerima'] : $datapengirim['negara']}}</td>
										</tr>
										<tr>
											<td>Provinsi</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['provinsipenerima'] : $datapengirim['provinsi']}}</td>
										</tr>
										<tr>
											<td>Kota</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['kotapenerima'] : $datapengirim['kota']}}</td>
										</tr>
										<tr>
											<td>Kode Pos</td>
											<td>{{($datapengirim['statuspenerima']==1) ? $datapengirim['kodepospenerima'] : $datapengirim['kodepos']}}</td>
										</tr>
									</tbody>
								</table>								
							</div>
							
						</div>
						<div class="row-fluid">
							<div class="span12">
								<h4>Metode Pembayaran</h4>
								@if($datapembayaran['tipepembayaran']=='bank')
									<span class="label label-info">Via Transfer Bank</span>
								@endif
								@if($datapembayaran['tipepembayaran']=='paypal')
									<span class="label label-info">Via Paypal</span>										
								@endif
								@if($datapembayaran['tipepembayaran']=='creditcard')
									<span class="label label-info">Via Credit Card</span>										
								@endif
							</div>
						</div>

					</div>
					<hr>
					<div style="clear:both;"></div>
					{{Form::open(array('url'=>'finish','method'=>'post','name'=>'finish'))}}
					
					<button style="margin-top: 8%;" type="submit" class="next-button btn btn-info"> Selesaikan Pemesanan</button>
					<a style="margin-top: 8%;" href="{{URL::to('pembayaran')}}" class="back-button btn btn-warning"><i class="icon-arrow-left"></i> Kembali</a>
					{{Form::close()}}

				</div>
			</div>
		</div>

	</div>
	@endsection