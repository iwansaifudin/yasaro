<div style="height:600px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:470px; width:660px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">

	<p align='center' style='font-size:15px'><b>Laporan Saham</b></p>
	<br />
	
	<table border='0' cellpadding='0' cellspacing='15'><tr><td>
		<table border='0' cellpadding='0' cellspacing='5'>
			<tr style='vertical-align:middle;'>
				<td width="130px">Saham</td>
				<td>
					:
					<input type='text' id='stock_name' style="width: 100px; font-size: 90%" value="" placeholder="Saham"
						onkeypress="(window.event?(event.keyCode==13?get_rpt_stock():null):(event.which==13?get_rpt_stock():null))"
					/>
				</td>
			</tr>
	
			<tr style='vertical-align:middle;'>
				<td>Pemegang Saham</td>
				<td>
					:
					<input type='text' id='stockholder_name' style="width: 150px; font-size: 90%" value="" placeholder="Pemegang Saham"
						onkeypress="(window.event?(event.keyCode==13?get_rpt_stock():null):(event.which==13?get_rpt_stock():null))"
					/>
				</td>
			</tr>
			<tr style='vertical-align:middle;'>
				<td colspan="2">
					<input type='button' id='search_button' value='Search' onclick="get_rpt_stock();" style="font-size: 90%" />
					<input type='button' id='excel_button' value='Excel' onclick="get_rpt_stock_excel();" style="font-size: 90%" />
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
