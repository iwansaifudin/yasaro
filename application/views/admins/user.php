<?php

	// declare placeholder variable
	$blank1 = ".  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  ";
	$blank2 = "x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  x  ";

	// declare variable
	$id = (isset($form['id'])?$form['id']:0);
	$code = (isset($form['code'])?$form['code']:null);
	$name = (isset($form['name'])?$form['name']:null);
	$status = (isset($form['status'])?$form['status']:1);
	$birth_place = (isset($form['birth_place'])?$form['birth_place']:null);
	$birth_date = (isset($form['birth_date'])?$form['birth_date']:null);
	$age = (isset($form['age'])?$form['age']:0);
	$gender = (isset($form['gender'])?$form['gender']:'M');
	$address1 = (isset($form['address1'])?$form['address1']:null);
	$address2 = (isset($form['address2'])?$form['address2']:null);
	$telephone = (isset($form['telephone'])?$form['telephone']:null);
	$handphone = (isset($form['handphone'])?$form['handphone']:null);
	$patriarch_id = (isset($form['patriarch_id'])?$form['patriarch_id']:null);
	$patriarch_name = (isset($form['patriarch_name'])?$form['patriarch_name']:null);
	$family = (isset($form['family'])?$form['family']:null);
	$cluster = (isset($form['cluster'])?$form['cluster']:null);
	$nationality = (isset($form['nationality'])?$form['nationality']:0);
	$stock_qty = (isset($form['stock_qty'])?$form['stock_qty']:0);
	$stock_total_price = (isset($form['stock_total_price'])?$form['stock_total_price']:0);
	$information = (isset($form['information'])?$form['information']:null);
	
?>

	<div style="height:670px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:570px; width:700px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

		<h1>PENDAFTARAN ANGGOTA</h1>
		<table border='0' cellspacing='15' cellpadding='0' width="100%"><tr><td>
			<table border="0" cellspacing="3" cellpadding="3" width="100%">
				<tr>
					<td width="160px">1. ID</td>
					<td>:</td>
					<td>
						<input type="text" id="id" style="border: none; font-size: 110%; width: 30px;" 
							value="<?=($id<>0?$id:null)?>" placeholder="<?=$blank2;?>" disabled 
						/>
					</td>
				</tr>
				<tr>
					<td>2. Login</td>
					<td>:</td>
					<td>
						<input type="text" id="code" style="border: none; font-size: 110%; width: 70px" maxlength="10" <?=($code<>null?'disabled':null)?>
							value="<?=($code<>null?$code:null)?>" placeholder="<?=$blank1;?>"
							onkeypress="(window.event?(event.keyCode==13?check_code($('#code').val()):null):(event.which==13?check_code($('#code').val()):null))" 
						/>
						<a href="#" class='nounderline' onclick="check_code($('#code').val())" style="cursor: pointer"><?=($code<>null?null:'[Cek Login]')?></a> 
						<input type="hidden" id="code_temp" style="border: none; font-size: 110%; width: 70px" value="<?=($code<>null?$code:null)?>" />
					</td>
				</tr>
				<tr>
					<td>3. Nama Lengkap</td>
					<td>:</td>
					<td>
						<input type="text" id="name" style="border: none; font-size: 110%; width: 200px" maxlength="35"
							value="<?=($name<>null?$name:'')?>" placeholder="<?=$blank1;?>" 
						/> 
					</td>
				</tr>
				<tr>
					<td>4. Tempat, Tanggal Lahir</td>
					<td>:</td>
					<td>
						<input type="text" id="birth_place" style="border: none; font-size: 110%; width: 150px" maxlength="20"  
							value="<?=($birth_place<>null?$birth_place:'')?>" placeholder="<?=$blank1;?>" 
						/>, 
						&nbsp;
						<input type="text" id="birth_date" style="border: none; font-size: 110%; width: 75px" maxlength="20" readonly
							value="<?=($birth_date<>null?$birth_date:'')?>" placeholder="<?=$blank1;?>" 
						/>
						(<font id="age"><?=$age?></font> tahun)
					</td>
				</tr>
				<tr>
					<td>5. Jenis Kelamin</td>
					<td>:</td>
					<td> 
						<select id='gender' style="border: none; font-size: 110%">
							<option value='M' <?=($gender=='M'?'selected':'')?>>Laki-Laki</option>
							<option value='F' <?=($gender=='F'?'selected':'')?>>Perempuan</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td>6. Alamat</td>
					<td>:</td>
					<td>
						<input type="text" id="address1" style="border: none; font-size: 110%; width: 350px" maxlength="80" 
							value="<?=($address1<>null?$address1:'')?>" placeholder="<?=$blank1;?>" 
						/>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>
						<input type="text" id="address2" style="border: none; font-size: 110%; width: 350px" maxlength="80" 
							value="<?=($address2<>null?$address2:'')?>" placeholder="<?=$blank1;?>" 
						/>
					</td>
				</tr>
				<tr>
					<td>7. Telepon</td>
					<td>:</td>
					<td>
						<input type="text" id="telephone" style="border: none; font-size: 110%; width: 120px" maxlength="20" 
							value="<?=($telephone<>null?$telephone:'')?>" placeholder="<?=$blank1;?>" 
						/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Handphone&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;
						<input type="text" id="handphone" style="border: none; font-size: 110%; width: 120px" maxlength="20" 
							value="<?=($handphone<>null?$handphone:'')?>" placeholder="<?=$blank1;?>" 
						/>
					</td>
				</tr>
				<tr>
					<td>8. Nama Kepala Keluarga</td>
					<td>:</td>
					<td>
						<input type="hidden"" id="patriarch_id" style="border: none; font-size: 110%; width: 120px;"
							value="<?=($patriarch_id<>null?$patriarch_id:'')?>"
						>
						<input type="text" id="patriarch_name" style="border: none; font-size: 110%; width: 120px" maxlength="20" readonly
							value="<?=($patriarch_name<>null?$patriarch_name:'')?>" placeholder="<?=$blank2;?>" 
						/>
						<a href='#' class='nounderline' style='font-size: 90%' onclick="user_dialog_search('Search Kepala Keluarga', 'patriarch_id', 'patriarch_name');">[...]</a>
					</td>
				</tr>
				<tr>
					<td>9. Status dalam Keluarga</td>
					<td>:</td>
					<td>
						<select id='family' style="border: none; font-size: 110%">
						<?php
						for($i = 0; $i < sizeof($family_group); $i++) {
							$family_id = $family_group[$i]['id'];
							$family_name = $family_group[$i]['name'];
							echo "<option value='$family_id' ".($family_id==$family?'selected':null).">$family_name</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>10. Kelompok</td>
					<td>:</td>
					<td>
						<select id='cluster' style="border: none; font-size: 110%">
						<?php
						for($i = 0; $i < sizeof($cluster_group); $i++) {
							$cluster_id = $cluster_group[$i]['id'];
							$cluster_name = $cluster_group[$i]['name'];
							echo "<option value='$cluster_id' ".($cluster_id==$cluster?'selected':null).">$cluster_name</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>11. Kewarganegaraan</td>
					<td>:</td>
					<td> 
						<select id='nationality' style="border: none; font-size: 110%">
							<option value='1' <?=($nationality=='1'?'selected':'')?>>Indonesia</option>
							<option value='2' <?=($nationality=='2'?'selected':'')?>>Asing</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>12. Jumlah Saham</td>
					<td>:</td>
					<td>
						<font style="font-size: 110%;"><?=$stock_qty?></font> pcs&nbsp;&nbsp;&nbsp;(Rp. <font style="font-size: 110%;"><?=$stock_total_price?></font>)
					</td>
				</tr>
				
				<tr>
					<td>13. Status Keanggotaan</td>
					<td>:</td>
					<td>
						<select id='status' style="border: none; font-size: 110%">
							<option value='1' <?=($status==1?'selected':'')?>>Aktif</option>
							<option value='0' <?=($status==0?'selected':'')?>>Tidak Aktif</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<td>14. Keterangan</td>
					<td>:</td>
					<td>
						<input type="text" id="information" style="border: none; font-size: 110%; width: 350px" maxlength="80" 
							value="<?=($information<>null?$information:'')?>" placeholder="<?=$blank1;?>" 
						/>
					</td>
				</tr>
			</table>
		</td></tr></table>
			
	</div>
</div>
