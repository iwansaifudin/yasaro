<?php

	// declare placeholder variable
	$blank1 = ".  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  ";
	$blank2 = "x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  ";

	// declare public variable
	$trans_id = (isset($form['trans_id'])?$form['trans_id']:null);
	$ref_no = (isset($form['ref_no'])?$form['ref_no']:null);
	$trans_date = (isset($form['trans_date'])?$form['trans_date']:null);
	$trans_status = (isset($form['trans_status'])?$form['trans_status']:0);
	$shu_id = (isset($form['shu_id'])?$form['shu_id']:0);	$stockholder_id = (isset($form['stockholder_id'])?$form['stockholder_id']:null);
	$stockholder_name = (isset($form['stockholder_name'])?$form['stockholder_name']:null);
	$stockholder_cluster = (isset($form['stockholder_cluster'])?$form['stockholder_cluster']:null);
	$shu_nominal = (isset($form['shu_nominal'])?$form['shu_nominal']:0);
	$stock_qty = (isset($form['stock_qty'])?$form['stock_qty']:0);
	$stock_trans = (isset($form['stock_trans'])?$form['stock_trans']:0);
	$stock_before = (isset($form['stock_before'])?$form['stock_before']:0);
	$stock_after = (isset($form['stock_after'])?$form['stock_after']:0);
	$shu_qty = (isset($form['shu_qty'])?$form['shu_qty']:0);
	$shu_trans = (isset($form['shu_trans'])?$form['shu_trans']:0);
	$shu_before = (isset($form['shu_before'])?$form['shu_before']:0);
	$shu_after = (isset($form['shu_after'])?$form['shu_after']:0);
	$message = (isset($form['message'])?$form['message']:null);

?>

	<div style="height:790px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:640px; width:700px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

		<h1>TRANSAKSI PEMBATALAN SHU</h1>
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
				<tr>
					<td>Periode</td>
					<td>:</td>
					<td>
						<select id='period' style="border: none; font-size: 110%" onchange="period_change(this.value);" <?=($trans_status==1?'disabled':'')?>>
							<?php
							for($i = 0; $i < sizeof($period); $i++) {
								echo "<option value='".($period[$i]['id'])."' ".($period[$i]['id']==$shu_id?'selected':'').">".($period[$i]['period'])."</option>";
							}
							?>
						</select>
					</td>
				</tr>
				
				<tr><td colspan="3" height="25px"><strong>PEMEGANG SAHAM</strong></td></tr>
				<tr>
					<td>ID*</td>
					<td>:</td>
					<td>
						<input type="text" id="stockholder_id" value="<?=$stockholder_id?>" style="border: none; font-size: 110%; width: 50px" placeholder="<?=$blank2;?>" readonly />
						<?php if($trans_status == 0) { ?>
						<a href="#" class="nounderline" onclick="shu_dialog_stockholder('Daftar Jamaah');" style="font-size: 90%">[...]</a>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Nama </td>
					<td>:</td>
					<td>
						<input type="text" id="stockholder_name" value="<?=$stockholder_name?>" style="border: none; font-size: 110%; width: 250px" placeholder="<?=$blank2;?>" readonly /> 
					</td>
				</tr>
				<tr>
					<td>Kelompok</td>
					<td>:</td>
					<td><input type="text" id="stockholder_cluster" value="<?=$stockholder_cluster?>" style="border: none; font-size: 110%; width: 350px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				<tr>
					<td>SHU per Lembar</td>
					<td>:</td>
					<td>Rp.<input type="text" id="shu_nominal" value="<?=$shu_nominal?>" style="border: none; font-size: 110%; width: 100px" placeholder="<?=$blank2;?>" readonly /></td>
				</tr>
				<tr valign="top">
					<td>Jumlah Saham</td>
					<td>:</td>
					<td>
						Total : <input type='text' id='stock_qty' value='<?=$stock_qty?>' style='border: none; font-size: 110%; width: 30px' readonly /><br />
						Sebelum : <input type='text' id='stock_before' value='<?=$stock_before?>' style='border: none; font-size: 110%; width: 30px' readonly />
						Sesudah : <input type='text' id='stock_after' value='<?=$stock_after?>' style='border: none; font-size: 110%; width: 30px' readonly />
					</td>
				</tr>
				<tr valign="top">
					<td>Jumlah SHU</td>
					<td>:</td>
					<td>
						Total : Rp.<input type='text' id='shu_qty' value='<?=$shu_qty?>' style='border: none; font-size: 110%; width: 90px' readonly /><br />
						Sebelum : Rp.<input type='text' id='shu_before' value='<?=$shu_before?>' style='border: none; font-size: 110%; width: 90px' readonly />
						Sesudah : Rp.<input type='text' id='shu_after' value='<?=$shu_after?>' style='border: none; font-size: 110%; width: 90px' readonly />
					</td>
				</tr>
				
				<tr><td colspan="3" height="25px"><strong>SHU</strong></td></tr>
				<tr valign='top'>
					<td>Pembatalan</td>
					<td>:</td>
					<td>
						Jumlah Saham : <input type='text' id='stock_trans' value='<?=$stock_trans?>' style='border: none; font-size: 110%; width: 30px' readonly />
						Jumlah SHU : Rp.<input type='text' id='shu_trans' value='<?=$shu_trans?>' style='border: none; font-size: 110%; width: 120px' readonly />
					</td>
				</tr>
				<tr valign='top'>
					<td>Saham
						<?php if($trans_status == 0) { ?>
						<a href='#' class='nounderline' style='font-size: 90%' onclick='shu_dialog_stock("Daftar Saham");'>[...]</a>
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

	function period_change(shu_id) {
		
		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'transactions/shu/period_change'
			, {
				shu_id: shu_id
				, stockholder_id: $('#stockholder_id').val()
			}
			, function(data) {
				
				var shu_nominal = Number(data['shu_nominal']);
				var stock_qty = Number(data['stock_qty']);
				var stock_before = Number(data['stock_before']);
				var shu_qty = Number(data['shu_qty']);
				var shu_before = Number(data['shu_before']);
				var stock_trans = Number($('#stock_trans').val());
				
				$('#shu_nominal').val(shu_nominal);
				$('#stock_qty').val(stock_qty);
				$('#stock_before').val(stock_before);
				$('#stock_after').val(stock_before - stock_trans);
				$('#shu_qty').val(data['shu_qty']);
				$('#shu_before').val(shu_before);
				$('#shu_after').val(shu_before  - (stock_trans * shu_nominal));
				$('#shu_trans').val(stock_trans * shu_nominal);
				
				// clear stock data
				for(var i = 1; i <= stock_trans; i++) {
					$('#stock_id_' + i).val('');
					$('#stock_name_' + i).val('');
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
				
			}
		);
		
	}

	function shu_cancellation_detail_append(stock_no, trans_status, qty_flag) {

		// set parameter 
		if(qty_flag == 0) { // setting qty hanya berlaku jika status transaksi masih open (0)
			var shu_nominal = Number($('#shu_nominal').val());
			$('#stock_trans').val(stock_no);
			$('#shu_trans').val(stock_no * shu_nominal);
			$('#stock_after').val(Number($('#stock_before').val()) - Number(stock_no));
			$('#shu_after').val(Number($('#shu_before').val()) - shu_nominal);

		}		
		var str = "";
		if(stock_no % 2 == 1) {
			str += "<tr id='tr_stock_"+stock_no+"'>";
		}

		str +="<td id='td_stock_"+stock_no+"1'>";
		if(trans_status == 0) {
		str +="	<a href='#' class='nounderline' style='font-size: 90%' onclick='shu_cancellation_detail_remove("+stock_no+")'>[-]</a> ";
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
	
	function shu_cancellation_detail_remove(stock_no) {
		
		var stock_trans = Number($('#stock_trans').val());
		if(stock_trans == 1) { // jika stock tinggal 1 record
			
			$('#stock_id_' + 1).val('');
			$('#stock_name_' + 1).val('');
			
		} else { // untuk stock yang lebih dari 1 record
			
			// menggeser data sebelum record terakhir di delete
			for(var i = stock_no; i < stock_trans; i++) {

				var stock_id = $('#stock_id_' + (i + 1)).val();
				var stock_name = $('#stock_name_' + (i + 1)).val();
				$('#stock_id_' + i).val(stock_id);
				$('#stock_name_' + i).val(stock_name);
				
			}
			
			$('#stock_trans').val(stock_trans - 1); // mengurangi nilai variable pencatat stock qty
			$('#shu_trans').val(Number($('#shu_trans').val()) - Number($('#shu_nominal').val())); // mengurangi nilai variable pencatat stock qty
			$('#stock_after').val(Number($('#stock_after').val()) + 1);
			$('#shu_after').val(Number($('#shu_after').val()) + Number($('#shu_nominal').val()));

			// menghapus record terakhir
			$('#td_stock_' + stock_trans + 1).remove();
			$('#td_stock_' + stock_trans + 2).remove();
			if(stock_trans % 2 == 1) { // untuk menghapus tr yang sudah tidak punya td
				$('#tr_stock_' + stock_trans).remove();
			}


		}
			
	}
</script>
