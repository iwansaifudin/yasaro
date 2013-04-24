<?php

	// declare placeholder variable
	$blank1 = ".  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  ";
	$blank2 = "x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  ";

	// declare public variable
	$trans_id = (isset($form['trans_id'])?$form['trans_id']:null);
	$ref_no = (isset($form['ref_no'])?$form['ref_no']:null);
	$trans_date = (isset($form['trans_date'])?$form['trans_date']:null);
	$trans_status = (isset($form['trans_status'])?$form['trans_status']:0);
	$stockholder_id_from = (isset($form['stockholder_id_from'])?$form['stockholder_id_from']:null);
	$stockholder_name_from = (isset($form['stockholder_name_from'])?$form['stockholder_name_from']:null);
	$stockholder_cluster_from = (isset($form['stockholder_cluster_from'])?$form['stockholder_cluster_from']:null);
	$stock_qty = (isset($form['qty'])?$form['qty']:0);
	$stock_total_price = (isset($form['total_price'])?$form['total_price']:0);
	$stock_qty_from_before = (isset($form['qty_from_before'])?$form['qty_from_before']:0);
	$stock_qty_from_after = (isset($form['qty_from_after'])?$form['qty_from_after']:0);
	$message = (isset($form['message'])?$form['message']:null);
	
?>

	<div style="height:790px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:640px; width:700px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

		<h1>TRANSAKSI PENJUALAN SAHAM</h1>
		<table border='0' cellpadding='0' cellspacing='15'><tbody><tr><td>
			
			<input type='hidden' id='trans_status' value='<?=$trans_status?>' />

			<table border="0" cellspacing="0" cellpadding="3"><tbody>
				
				<tr><td colspan="3" height="25px"><strong>TRANSAKSI</strong></td></tr>
				<tr>
					<td width='125px'>ID</td>
					<td width="5px">:</td>
					<td><input type="text" id="trans_id" value="<?=$trans_id?>" style="border: none; font-size: 110%; width: 50px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				<tr>
					<td width='120px'>No Referensi</td>
					<td width="5px">:</td>
					<td><input type="text" id="ref_no" value="<?=$ref_no?>" style="border: none; font-size: 110%; width: 110px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><input type="text" id="trans_date" value="<?=$trans_date?>" style="border: none; font-size: 110%; width: 130px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				
				<tr><td colspan="3" height="25px"><strong>PEMEGANG SAHAM</strong></td></tr>
				<tr>
					<td>ID*</td>
					<td>:</td>
					<td>
						<input type="text" id="stockholder_id_from" value="<?=$stockholder_id_from?>" style="border: none; font-size: 110%; width: 50px" placeholder="<?=$blank2;?>" readonly />
						<?php if($trans_status == 0) { ?>
						<a href="#" class="nounderline" onclick="stock_dialog_stockholder_search('Daftar Jamaah', 'from');" style="font-size: 90%">[...]</a>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Nama </td>
					<td>:</td>
					<td>
						<input type="text" id="stockholder_name_from" value="<?=$stockholder_name_from?>" style="border: none; font-size: 110%; width: 250px" placeholder="<?=$blank2;?>" readonly /> 
					</td>
				</tr>
				<tr>
					<td>Kelompok</td>
					<td>:</td>
					<td><input type="text" id="stockholder_cluster_from" value="<?=$stockholder_cluster_from?>" style="border: none; font-size: 110%; width: 150px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				<tr>
					<td>Jumlah Saham</td>
					<td>:</td>
					<td>
						Sebelum : <input type='text' id='stock_qty_from_before' value='<?=$stock_qty_from_before?>' style='border: none; font-size: 110%; width: 30px' readonly />
						Sesudah : <input type='text' id='stock_qty_from_after' value='<?=$stock_qty_from_after?>' style='border: none; font-size: 110%; width: 30px' readonly />
					</td>
				</tr>

				<tr><td colspan="3" height="25px"><strong>SAHAM</strong></td></tr>
				<tr valign='top'>
					<td>Penjualan</td>
					<td>:</td>
					<td>
						Jumlah : <input type='text' id='stock_qty' value='<?=$stock_qty?>' style='border: none; font-size: 110%; width: 30px' readonly />
						Harga : Rp.<input type='text' id='stock_total_price' value='<?=$stock_total_price?>' style='border: none; font-size: 110%; width: 120px' readonly />
					</td>
				</tr>
				<tr valign='top'>
					<td>Saham
						<?php if($trans_status == 0) { ?>
						<a href='#' class='nounderline' style='font-size: 90%' onclick='stock_dialog_stock_search("Daftar Saham");'>[...]</a>
						<?php } ?>
					</td>
					<td>:</td>
					<td>
						<table id='table_stock' border='0' cellspacing='0' cellpadding='0'>
							<tbody id='tbody_stock'>
							</tbody>
						</table>
					</td>
				</tr>
				
				<tr><td colspan="3" height="10px"></td></tr>
				<tr>
					<td>Keterangan</td>
					<td>:</td>
					<td><input type="text" id="message" value="<?=$message?>" style="border: none; font-size: 110%; width: 500px" placeholder="<?=$blank1;?>" <?=($trans_status==1?'readonly':null)?>/></td>
				</tr>
				
			</tbody></table>
			
		</td></tr></tbody></table>
			
	</div>
</div>

<script type='text/javascript'>

	function stock_sell_detail_append(stock_no, total_price, trans_status, qty_flag) {

		// set parameter 
		if(qty_flag == 0) { // setting qty hanya berlaku jika status transaksi masih open (0)
			$('#stock_qty').val(stock_no);
			$('#stock_total_price').val(total_price);
			$('#stock_qty_from_after').val(Number($('#stock_qty_from_before').val()) - Number(stock_no));
		}		
		var str = "";
		if(stock_no % 2 == 1) {
			str += "<tr id='tr_stock_"+stock_no+"'>";
		}
		
		str +="<td id='td_stock_"+stock_no+"1'>";
		if(trans_status == 0) {
		str +="	<a href='#' class='nounderline' style='font-size: 90%' onclick='stock_sell_detail_remove("+stock_no+")'>[-]</a> ";
		}
		str += stock_no+".&nbsp;</td>"
			+ "<td id='td_stock_"+stock_no+"2' width='200px'>"
			+ "<input type='hidden' id='stock_id_"+stock_no+"' style='border: none; font-size: 110%;' />"
			+ "<input type='text' id='stock_name_"+stock_no+"' value='' style='border: none; font-size: 110%; width: 170px' maxlength='50' placeholder='<?=$blank2;?>' readonly />"
			+ "</td>";

		if(stock_no % 2 == 1) {
			str += "</tr>";
			$('#tbody_stock').append(str);
		} else {
			$('#tr_stock_' + (stock_no - 1)).append(str);
		}
			
		$('input, textarea').placeholder();
		
	}
	
	function stock_sell_detail_remove(stock_no) {
		
		var stock_qty = Number($('#stock_qty').val());
		var stock_total_price = Number($('#stock_total_price').val());
		if(stock_qty == 1) { // jika stock tinggal 1 record
			
			$('#stock_id_' + 1).val('');
			$('#stock_name_' + 1).val('');
			
		} else { // untuk stock yang lebih dari 1 record
			
			// menggeser data sebelum record terakhir di delete
			for(var i = stock_no; i < stock_qty; i++) {

				var stock_id = $('#stock_id_' + (i + 1)).val();
				var stock_name = $('#stock_name_' + (i + 1)).val();
				$('#stock_id_' + i).val(stock_id);
				$('#stock_name_' + i).val(stock_name);
				
			}
			
			// menghapus record terakhir
			$('#td_stock_' + stock_qty + 1).remove();
			$('#td_stock_' + stock_qty + 2).remove();
			if(stock_qty % 2 == 1) { // untuk menghapus tr yang sudah tidak punya td
				$('#tr_stock_' + stock_qty).remove();
			}

			$('#stock_qty').val(stock_qty - 1); // mengurangi nilai variable pencatat stock qty
			$('#stock_total_price').val(stock_total_price - 25000); // mengurangi nilai variable pencatat stock qty
			$('#stock_qty_from_after').val(Number($('#stock_qty_from_after').val()) + 1);
			
		}
			
	}
</script>
