<div id="stock_dialog" style="display:none; font-size: 90%">
	<input type='text' id='total' style="font-size: 90%; width: 150px" maxlength="25" placeholder="Jumlah Saham"
		onkeypress="character = false; metachar = false; allowed_character(); (window.event?(event.keyCode==13?generate():null):(event.which==13?generate():null))" 
	/>
	<input type='button' value='Generate' style="font-size: 90%" onclick="generate();" />
</div>

<script type="text/javascript">

	function load_generate() {
		
		$("#stock_dialog").dialog({
			resizable: false
			, modal: true
			, width: 250			, height: 70
			, title: 'Generate Saham'
			, open: function(event, ui) {
	        }
		});
	
		return false;
	
	}
	
	function generate() {
		
		var total = $('#total').val()
		
		if(total <= 0) {
			alert('Mohon maaf, total pembuatan saham baru harus lebih besar dari nol!');
			return false;
		}
		
		if(!confirm('Anda yakin ingin membuat saham baru sejumlah ' + total + ' lembar!')) {return false;}
		
		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'admins/stock_dialog/generate' 
			, {total: total}
			, function(data) {
				
				if(data['result']) {
					alert('Pembuatan saham baru sejumlah ' + total + ' lembar berhasil!');
				} else {
					alert('Pembuatan saham baru sejumlah ' + total + ' lembar gagal!');
				}
				
				jQuery("#list_3").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
	
			}
		);
	}

</script>