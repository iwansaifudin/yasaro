function role_menu() {

	$('#footer_information').html('Pengaturan Akses Menu');

	// minimize north layout
	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');

	// refresh form
	$('.center-center-center').load(
		'admins/role_menu' 
		, function(text, status) {
			
			var role_id = $('select#role option:selected').val();
			get_role_menu(role_id);
			
			// role action
			$('#role').change(
				function() {
					
					var role_id = $('select#role option:selected').val();
					get_role_menu(role_id);
		
				}
			);

			// save action
			$('#save_button').click(
				function() {

            		var parm = {};
            		parm['role_id'] = $('select#role option:selected').val();
            		
            		var menu = update_role_menu_recursive('');

            		var sub_menu = menu.split(';');
            		for(var i = 0; i < sub_menu.length; i++) {
            			
            			var sub_menu2 = sub_menu[i].split(',');
						var seq = sub_menu2[0];
            			var menu_id = sub_menu2[1];
            			
            			seq2 = seq;
            			for(var j = 1; j <= seq.length - 1; j++) {

            				seq2 = seq2.substr(0, seq2.length - 1);
            				menu_id2 = $('#menu' + seq2).val();
            				menu_temp = seq2 + ',' + menu_id2;
            				
        					var is_exists = false;
            				var sub_menu2 = menu.split(';');
            				for(var k = 0; k < sub_menu2.length; k++) {
            					if(menu_temp == sub_menu2[k]) {
            						is_exists = true;
            					}
            				}

        					if(!is_exists) {
            					menu += ';' + menu_temp;
        					}
            				
            			}
            			
            		}
            		parm['menu'] = menu;
            		
					$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
					$.ajaxSetup({ cache: false });
					$.getJSON('admins/role_menu/update_role_menu' 
						, parm
						, function(data) {

							var result = data['result'];
							if(result) {
								alert('Data akses menu berhasil disimpan!');
							} else {
								alert('Data akses menu gagal disimpan!');
							}
							
							$('#loading').html("");
							$.ajaxSetup({ cache: true });
							
						}
					);

				}
			);
			
		}
	);

}
