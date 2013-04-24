<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>
<div style="height:400px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:320px; width:750px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">
		
		<p align='center' style='font-size:15px'><b>Pengaturan Akses Pengguna</b></p>

		<table border='0' cellpadding='0' cellspacing='15'><tr><td>
			<table border='0' cellpadding='0' cellspacing='5'>
				<tr style='vertical-align:top;'>
					<td>
						<br />
						<table border="0" cellpadding="0" cellspacing="0"><tr>
						    <td style="vertical-align:top">
						        <table id='list_3'></table>
						    </td>
						    <td style="text-align:center; vertical-align:middle; width: 50px;">
								<p align='center' style='font-size:90%'>Drag<br />&<br />Drop</p>
							</td>   
						    <td style="vertical-align:top">
						        <table id='list_4'></table>
						    </td>
						</tr></table>
					</td>
				</tr>
			</table>
		</td></tr></table>
		
	</div>
</div>
</td></tr></table>

<script type="text/javascript">

	function get_user_role_list(role_id) {
		
		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON('admins/role_user/get_user_role_list' 
			, {
				role_id: role_id
			}
			, function(data) {
				
				jQuery("#list_3").clearGridData();
				
				for(i = 0; i < data.length; i++) {
					jQuery("#list_3").addRowData(
						data[i]['id']
						, {
							id: data[i]['id']
							, name: data[i]['name']
							, cluster: data[i]['cluster']
						}
					);
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
	
			}
		);
		
	}
	
	function get_user_list() {
	
		var search_key = $('#search_key').val();
		
		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON('admins/role_user/get_user_list' 
			, {
				role_id: $('select#role option:selected').val()
				, search_key: search_key
			}
			, function(data) {
				
				jQuery("#list_4").clearGridData();
				
				for(i = 0; i < data.length; i++) {
					jQuery("#list_4").addRowData(
						data[i]['id']
						, {
							id: data[i]['id']
							, name: data[i]['name']
							, cluster: data[i]['cluster']
						}
					);
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
	
			}
		);
		
	}

</script>