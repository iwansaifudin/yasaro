<div style="height:620px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:490px; width:850px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

		<p align='center' style='font-size:15px'><b>Laporan Transaksi SHU</b></p>
		<br />
		
		<table border='0' cellpadding='0' cellspacing='15' align="center"><tr><td>
			<table border='0' cellpadding='0' cellspacing='5'>
				<tr style='vertical-align:middle;'>
					<td width="120px">Periode</td>
					<td>
						:
						<select id='period' style="font-size: 90%">
						<option value='0' selected>Semua</option>
						<?php
						for($i = 0; $i < sizeof($period); $i++) {
							echo "<option value='".$period[$i]['id']."'>".$period[$i]['name']."</option>";
						}
						?>
						</select>
					</td>
				</tr>
				<tr style='vertical-align:middle;'>
					<td width="120px">Transaksi</td>
					<td>
						:
						<select id='form' style="font-size: 90%">
							<option value='0' selected>Semua</option>
							<?php
							for($i = 0; $i < sizeof($form); $i++) {
								echo "<option value='".$form[$i]['id']."'>".$form[$i]['name']."</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr style='vertical-align:middle;'>
					<td>Tanggal</td>
					<td>
						:
						<input type='text' id='trans_date1' style="width: 70px; font-size: 90%" value="" readonly 
							onkeypress="(window.event?(event.keyCode==13?get_rpt_shu_trans():null):(event.which==13?get_rpt_shu_trans():null))"
						/> - 
						<input type='text' id='trans_date2' style="width: 70px; font-size: 90%" value="" readonly
							onkeypress="(window.event?(event.keyCode==13?get_rpt_shu_trans():null):(event.which==13?get_rpt_shu_trans():null))"
						/>
					</td>
				</tr>
				<tr style='vertical-align:middle;'>
					<td colspan="2">
						<input type='button' id='search_button' value='Search' onclick="get_rpt_shu_trans();" style="font-size: 90%" />
						<input type='button' id='excel_button' value='Excel' onclick="get_rpt_shu_trans_excel();" style="font-size: 90%" />
					</td>
				</tr>
				<tr style='vertical-align:top;'>
					<td colspan="2">
				        <table id='list_3'></table>
					</td>
				</tr>
			</table>
		</td></tr></table>

	</div>
</div>


<script type="text/javascript">
	$('input, textarea').placeholder();
</script>
