@if(!empty($cart_contents))
<?php $i=0;?>
<table id="items" style="margin:5% 0;padding: 10px;border-collapse: collapse;clear: both;width: 100%;border: 1px solid black;">
<tr style="margin: 0;padding: 0;">
    <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">No</th>
    <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Nama Produk</th>
    <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Varian</th>
    <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Qty</th>          
    <th style="margin: 0;padding: 5px;border: 1px solid black;background: #eee;">Subtotal</th>
</tr>
@foreach ($cart_contents as $key => $item)													
	<tr class="item-row">
		<td style="padding:10px">{{$key+1}}</td>
		<td style="padding:10px" align="center">{{$item['name']}}</td>
		<td style="padding:10px" align="center">{{ $item['opsiskuId']=='0' ? 'No Opsi' : Opsisku::find($item['opsiskuId'])->opsi1.(Opsisku::find($item['opsiskuId'])->opsi2!='' ? ' / '.Opsisku::find($item['opsiskuId'])->opsi2 : '').(Opsisku::find($item['opsiskuId'])->opsi3!='' ? ' / '.Opsisku::find($item['opsiskuId'])->opsi3 : '')}}</td>
		<td style="padding:10px" align="center">{{$item['qty']}} @ {{ price_format($item['price'] )}} {{price_format($item['subtotal'])}}</td>
		<td style="padding:10px" align="center">{{price_format($item['subtotal'])}}</td>
	</tr>
	<?php $berat = $berat + ($item['qty']*$item['berat'])?>
@endforeach
     <tr style="margin: 0;padding: 0;">
	  <td colspan="3" style="margin: 0;padding: 5px;border: 0;border-top: 1px solid black;"> </td>
	  <td colspan="1" style="margin: 0;padding: 5px;border: 1px solid black;border-right: 0;text-align: right;">Subtotal</td>
	  <td style="margin: 0;padding: 10px;border: 1px solid black"><div id="subtotal" style="margin: 0;padding: 0;">{{ price_format(Shpcart::cart()->total() )}}</div></td>
	</tr>
	<tr style="margin: 0;padding: 0;">
	  <td colspan="3" style="margin: 0;padding: 5px;border: 0;"> </td>
	  <td colspan="1" style="margin: 0;padding: 5px;border: 1px solid black;border-right: 0;text-align: right;">Ongkos Kirim</td>
	  <td style="margin: 0;padding: 10px;border: 1px solid black"><div id="subtotal" style="margin: 0;padding: 0;">{{ price_format($order->ongkoskirim).' ('.($berat/100).' gram)'}}</div></td>
	</tr>
	<tr style="margin: 0;padding: 0;">
	  <td colspan="3" class="blank" style="margin: 0;padding: 5px;border: 0;"> </td>
	  <td colspan="1"  style="margin: 0;padding: 5px;border: 1px solid black;border-right: 0;text-align: right;">Kode Unik</td>
	  <td style="margin: 0;padding: 10px;border: 1px solid black;"><div id="subtotal" style="margin: 0;padding: 0;">{{($order->total - (Shpcart::cart()->total() + $order->ongkoskirim))}}</div></td>
	</tr>
	<tr style="margin: 0;padding: 0;">
	  <td colspan="3" style="margin: 0;padding: 5px;border: 0;"> </td>
	  <td colspan="1"  style="margin: 0;padding: 5px;border: 1px solid black;border-right: 0;text-align: right;background: #eee;">Total yang harus dibayar</td>
	  <td style="margin: 0;padding: 10px;border: 1px solid black;background: #eee;"><div class="due" style="margin: 0;padding: 0;">{{ price_format($order->total)}}</div></td>
	</tr>
	</table>
@else
	<tr>
		<td style='text-align:center' colspan='6'><p>Keranjang belanja masih kosong.</p><input type='hidden' id='totalBelanja' value='{{ Shpcart::cart()->total() }}'></td>
	</tr>
	</table>
@endif




	
	
