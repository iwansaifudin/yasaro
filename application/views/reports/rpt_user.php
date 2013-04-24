<p align='center' style='font-size:15px'><b>Laporan Pemegang Saham</b></p>
<br />

<table border='0' cellpadding='0' cellspacing='15'><tr><td>
	<table border='0' cellpadding='0' cellspacing='5'>
		<tr style='vertical-align:middle;'>
			<td width="120px">Kelompok</td>
			<td>
				:
				<select id='cluster' style="font-size: 90%">
				<option value='0' selected>Semua</option>
				<?php
				for($i = 0; $i < sizeof($cluster); $i++) {
					$cluster_id = $cluster[$i]['id'];
					$cluster_name = $cluster[$i]['name'];
					echo "<option value='$cluster_id'>$cluster_name</option>";
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
					onkeypress="(window.event?(event.keyCode==13?get_rpt_user():null):(event.which==13?get_rpt_user():null))"
				/>
			</td>
		</tr>
		<tr style='vertical-align:middle;'>
			<td colspan="2">
				<input type='button' id='search_button' value='Search' onclick="get_rpt_user();" style="font-size: 90%" />
				<input type='button' id='excel_button' value='Excel' onclick="get_rpt_user_excel();" style="font-size: 90%" />
			</td>
		</tr>
		<tr style='vertical-align:top;'>
			<td colspan="2">
		        <table id='list_3'></table>
			</td>
		</tr>
	</table>
</td></tr></table>

<script type="text/javascript">
	$('input, textarea').placeholder();
</script>
