function role_user() {

	$('#footer_information').html('Pengaturan Akses Pemakai');

	// minimize north layout
	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');

	// refresh form
	$('.center-center-center').load(
		'admins/role_user' 
		, function(text, status){ 

			jQuery("#list_3").jqGrid({
				datatype: "local"
				, height: 150
				, colNames: ['ID', 'Nama', 'Kelompok']
				, colModel: [
					{ name: 'id', index: 'id', width: 100 }
					, { name: 'name', index: 'name', width: 120 }
					, { name: 'cluster', index: 'cluster', width: 100 }
				]
				, rowNum: 100
				, caption: 'Daftar Akses Pengguna'
				, toolbar: [true,"top"]
			});
			
			// design grid
			jQuery("#list_4").jqGrid({ 
				datatype: "local"
				, height: 150
				, colNames: ['ID', 'Nama', 'Kelompok']
				, colModel: [
					{ name: 'id', index: 'id', width: 100 }
					, { name: 'name', index: 'name', width: 120 }
					, { name: 'cluster', index: 'cluster', width: 100 }
				]
				, rowNum: 100
				, caption: 'Daftar Pengguna'
				, toolbar: [true,"top"]
			}); 
			
			jQuery("#list_3").jqGrid('gridDnD',{connectWith:'#list_4,#list_3'});
			jQuery("#list_4").jqGrid('gridDnD',{connectWith:'#list_3,#list_4'});

			$("#t_list_3").append(
				"&nbsp;<select id='role' style='width: 155px; font-size: 90%'>"
				+ "</select>"
				+ "<input type='button' id='save_button' value='Save' style='font-size: 90%' />"
			); 
			
			$("#t_list_4").append(
				"&nbsp;<input type='text' id='search_key' style='width: 150px; font-size: 90%'"
				+ "	placeholder='ID / Name / Kelompok'"
				+ "	onkeypress='(window.event?(event.keyCode==13?get_user_list():null):(event.which==13?get_user_list():null))'"
				+ "/>"
				+ "<input type='button' id='search_button' value='Search' style='font-size: 90%' />"
				+ "<input type='button' id='clear_button' value='Clear' style='font-size: 90%' />"
			); 
			
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON(
				'admins/role_user/get_role' 
				, {}
				, function(data) {
					
					for(i = 0; i < data.length; i++) {
						$('#role').append("<option value='"+data[i]['id']+"'>"+data[i]['name']+"</option>");
					}
					
					// set data grid
					var role_id = $('select#role option:selected').val();
					get_user_role_list(role_id);
		
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
		
				}
			);

			// role action
			$('#role').change(
				function() {

					jQuery("#list_4").clearGridData();
					
					var role_id = $('select#role option:selected').val();
					get_user_role_list(role_id);

				}
			);
			
			// search action
			$('#search_button').click(function() {
				get_user_list();
			});

			// clear action
			$('#clear_button').click(function() {
				$('#search_key').val('');
				get_user_list();
			});
			
			// save action
			$('#save_button').click(
				function() {

					var length = jQuery('#list_3').jqGrid('getGridParam','records');

            		var parm = {};
					parm['role_id'] = $('select#role option:selected').val();
					parm['length'] = length;

					// get data
					var latestData = $('#list_3').jqGrid('getRowData');
					var i = 0;
					for(row in latestData) {
						i++;
						var j = 0;
						var record = latestData[row];
						for(cell in record) {
							j++;
							if(j == 1) {
								parm['user_id' + i] = record[cell];
							}
						}
					}
					
					$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
					$.ajaxSetup({ cache: false });
					$.getJSON('admins/role_user/update_user_role' 
						, parm
						, function(data) {

							var result = data['result'];
							if(result) {
								alert('Data akses pengguna berhasil disimpan!');
							} else {
								alert('Data akses pengguna gagal disimpan!');
							}
							
							$('#loading').html("");
							$.ajaxSetup({ cache: true });
							
						}
					);

				}
			);
			
			$('input, textarea').placeholder();
			
		}
	);

}


