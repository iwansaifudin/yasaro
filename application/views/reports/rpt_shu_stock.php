<div style="height:620px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:490px; width:850px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

		<p align='center' style='font-size:15px'><b>Laporan Saham SHU</b></p>
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
					<td width="120px">Kelompok</td>
					<td>
						:
						<select id='cluster' style="font-size: 90%">
						<option value='0' selected>Semua</option>
						<?php
						for($i = 0; $i < sizeof($cluster); $i++) {
							echo "<option value='".$cluster[$i]['id']."'>".$cluster[$i]['name']."</option>";
						}
						?>
						</select>
					</td>
				</tr>
		
				<tr style='vertical-align:middle;'>
					<td>Pemegang Saham</td>
					<td>
						:
						<input type='text' id='user_name' style="width: 200px; font-size: 90%" value="" placeholder="Nama"
							onkeypress="(window.event?(event.keyCode==13?get_rpt_shu_stock():null):(event.which==13?get_rpt_shu_stock():null))"
						/>
					</td>
				</tr>
				<tr style='vertical-align:middle;'>
					<td colspan="2">
						<input type='button' id='search_button' value='Search' onclick="get_rpt_shu_stock();" style="font-size: 90%" />
						<input type='button' id='excel_button' value='Excel' onclick="get_rpt_shu_stock_excel();" style="font-size: 90%" />
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
