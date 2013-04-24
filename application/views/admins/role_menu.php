<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>
<div style="height:523px; overflow:hidden; position:relative; width:99.9%;">
	<div style="height:423px; width:950px; border: 1px solid darkgray; background-color: white; position:relative; top:25px; text-align:left; margin-left:auto; margin-right:auto;">
		<p align='center' style='font-size:15px'><b>Pengaturan Akses Menu</b></p>
		<table border='0' cellpadding='0' cellspacing='15'><tr><td>
			<table border='0' cellpadding='0' cellspacing='5'>
				<tr style='vertical-align:top;'>
					<td>
						<font style='font-size:11px;'>Role : </font>
						<select id='role' style='width: 155px; font-size: 90%'>
							<?php
							for($i = 0; $i < sizeof($role); $i++) {
								$id = $role[$i]['id'];
								$name = $role[$i]['name'];
							
								echo "<option value='$id'>$name</option>";
							}
							?>
						</select>
						<input type='button' id='save_button' value='Save' style='font-size: 90%' />
						<br /><br />
						<table border="1" cellpadding="0" cellspacing="0"><tr id="tr_role_menu">
						</tr></table>
					</td>
				</tr>
			</table>
		</td></tr></table>
	</div>
</div>
</td></tr></table>

<script type="text/javascript">

	function get_role_menu(role_id) {
		
		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'admins/role_menu/get_role_menu' 
			, {
				role_id: role_id
			}
			, function(data) {
				
				var menu = get_role_menu_recursive(data, 0, '', 0);
				$('#tr_role_menu').html(menu);
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
	
			}
		);
	}
	
	function get_role_menu_recursive(data, parents, seqs, lvl) {
	
		var menu = "";
		
		var seq = 0;
		for(var i = 0; i < data.length; i++) {
			var id = data[i]['id'];
			var name = data[i]['name'];
			var parent = data[i]['parent'];
			var selected = data[i]['selected'];
			var is_active = data[i]['is_active'];
	
			if(parent == parents) {
				seq += 1;
				
				if(parents == 0) {
					menu += "<td style='vertical-align:top; width: 300px; height: 300px'>";
				}
	
				// setting selected parent (berdasarkan selected child-nya)
				var seq_child = 0;
				var checked = true;
				for(var j = 0; j < data.length; j++) {
					var parent_child = data[j]['parent'];
					var selected_child = data[j]['selected'];
	
					if(parent_child == id) {
						seq_child++;
						if(selected_child == 0) {
							checked = false;
						}
					}
				}
				
				if(seq_child == 0) {
					checked = selected;
				}
	
				for(var j = 1; j <= lvl; j++) {
					menu += "&nbsp;&nbsp;&nbsp;&nbsp";
				}
				menu += "<input type='checkbox' id='menu"+seqs+seq+"' value='"+id+"' "+(checked==1?'checked':'')+" onclick='role_menu_child_checked("+seqs+seq+");role_menu_parent_checked("+seqs+seq+");' /><b><font style='font-size:11px;"+(is_active==0?'color:red;':'')+"'>"+name+"</font></b><br />";
				
				menu += get_role_menu_recursive(data, id, (seqs+seq), lvl+1);
	
				if(parents == 0) {
					menu += "</td>";
				}
				
			}
		}
		menu += "<input type='hidden' id='menu_qty"+seqs+"' value='"+seq+"' style='font-size:70%' />";
		
		return menu;
		
	}
	
	function update_role_menu_recursive(seq) {
	
		var menu = "";
		var menu_qty = $('#menu_qty' + seq).val();
		if(menu_qty <= 0) return "";
		
		for(var i = 1; i <= menu_qty; i++) {
			
			var status = $('#menu' + seq + i).attr('checked');
			if(status) {
				if(menu.length > 0) {
					menu += ";";
				}
				menu += (seq + '' + i) + ',' + $('#menu' + seq + i).val();
			}
			
			var menu_recursive = update_role_menu_recursive(seq + '' + i);
			if(menu_recursive.length > 0) {
				if(menu.length > 0) {
					menu += ";";
				}
				menu += menu_recursive;
			}
			
		}
	
		return menu;
	
	}
	
	function role_menu_child_checked(seq) {
	
		var qty = $('#menu_qty'+seq).val();
		for(var i = 1; i <= qty; i++) {
			if($('#menu'+seq).attr('checked')) {
				$('#menu' + seq + i).attr('checked', 'checked');
			} else {
				$('#menu' + seq + i).removeAttr('checked');
			}
			
			var qty_child = $('#menu_qty' + seq + i).val();
			if(qty_child > 0) {
				role_menu_child_checked(seq + '' + i);
			}
		}
	
	}
	
	
	function role_menu_parent_checked(seq) {
		
		var seqs = seq+'';
		len = seqs.length;
		if(len <= 0) return false;
		
		var checked = true;
		seqs = seqs.substr(0, len - 1);
		var qty = $('#menu_qty'+seqs).val();
		for(var i = 1; i <= qty; i++) {
			if(!$('#menu' + seqs + i).attr('checked')) {
				checked = false;
			}
		}
	
		if(checked) {
			$('#menu' + seqs).attr('checked', 'checked');
		} else {
			$('#menu' + seqs).removeAttr('checked');
		}
	
		role_menu_parent_checked(seqs);
		
	}

</script>
							