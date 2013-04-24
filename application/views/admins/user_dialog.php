<div id="user_dialog" style="display:none; font-size: 90%">
	<input type='hidden' id='user_dialog_id' style="font-size: 90%" />
	<input type='hidden' id='user_dialog_name' style="font-size: 90%" />
	<table id="list_user_dialog"></table><table id="pager_user_dialog"></table>
</div>

<script type="text/javascript">
	
	function user_dialog_search(title, id, name) {
		
		// set parameter
		$('#user_dialog_id').val(id);
		$('#user_dialog_name').val(name);
		
		$("#user_dialog").dialog({
			resizable: false
			, modal: true
			, width: 375
			, height: 255			, title: title
			, open: function(event, ui) {
        		list_user_dialog();
	        }
	        , close: function() {
				var toggle_flag = $('#gview_list_user_dialog .ui-search-toolbar').css('display');
				if(toggle_flag == 'table-row') {
					jQuery("#list_user_dialog")[0].toggleToolbar();
					$("#list_user_dialog").setGridHeight(150, true);
				}
	        }
		});

		return false;
	
	}
	
	
	function list_user_dialog() {

		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'admins/user_dialog/get_combobox' 
			, {}
			, function(data) {
				
				var cluster_list = "";
				var cluster_default = "";
				for(i = 0; i < data.length; i++) {
					if(i > 0) {
						cluster_list += ";";
						cluster_default = data[i]['id'];
					}
					cluster_list += data[i]['id']+":"+data[i]['name'];
				}

				jQuery("#list_user_dialog").jqGrid({
					url: 'admins/user_dialog/get_list_user_dialog'
					, datatype: 'json'
					, mtype: "POST"
					, colNames:['ID', 'Nama', 'Kelompok']
					, colModel:[
						{name:'id', index:'id', width:30, align:'left', sorttype:'text'}
						, {name:'name', index:'name', width:75, align:'left', sorttype:'text'}
						, {name:'cluster', index:'cluster', width:75, align:'left', edittype:"select", formatter:"select", editoptions:{value:cluster_list, defaultValue: cluster_default}}
					]
					, rowNum: 100
					, sortname: 'id'
					, sortorder: 'asc'
					, altRows: true
					, width: 350
					, height: 150
					, pager: '#pager_user_dialog'
					, pgbuttons: false
					, recordtext: ''
					, pgtext: ''
					, ondblClickRow: function(id) {
						set_list_user_dialog(); // set selected item to object form
					}
				});
				
				$('#pager_user_dialog_center').remove();
				$('#pager_user_dialog_right').remove();
				jQuery("#list_user_dialog").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				jQuery("#list_user_dialog").jqGrid('navGrid','#pager_user_dialog',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});
		
				if($('#pager_user_dialog_left .ui-pg-button').css('display') == null) { // pager flag show
					
					jQuery("#list_user_dialog").jqGrid('navButtonAdd',"#pager_user_dialog",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-search',
						onClickButton:function(){ 
							
							this.toggleToolbar();
		
							var toggle_flag = $('#gview_list_user_dialog .ui-search-toolbar').css('display');
							if(toggle_flag == 'none') {
								$("#list_user_dialog").setGridHeight(150, true);
							} else {
								$("#list_user_dialog").setGridHeight(127, true);
							}
							
						} 
					});
					
					jQuery("#list_user_dialog").jqGrid('navButtonAdd',"#pager_user_dialog",{caption:"Refresh",title:"Refresh Search",buttonicon :'ui-icon-refresh',
						onClickButton:function(){ 
							this.clearToolbar();
						} 
					});
			
					setSearchSelect('list_user_dialog', 'cluster', 'All:Semua;' + cluster_list);
			
					jQuery("#list_user_dialog").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
					jQuery("#list_user_dialog")[0].toggleToolbar();
			
					jQuery("#list_user_dialog").jqGrid(
						'navButtonAdd'
						, '#pager_user_dialog'
						, {
							caption:"Choose"
							, buttonicon:"ui-icon-check"
							, position:"last"
							, onClickButton: function() { 
								
								set_list_user_dialog();
								
							}
						}
					);
					
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
		
			}
		);
		
	}
	
	function set_list_user_dialog() {
		
		// get grid parameter
		var grid = jQuery('#list_user_dialog');
		var sel_id = grid.jqGrid('getGridParam', 'selrow');
		var user_dialog_id_val = grid.jqGrid('getCell', sel_id, 'id');
		var user_dialog_name_val = grid.jqGrid('getCell', sel_id, 'name');
		// get field value
		var user_dialog_id = $('#user_dialog_id').val();
		var user_dialog_name = $('#user_dialog_name').val();
		$("#" + user_dialog_id).val(user_dialog_id_val);
		$("#" + user_dialog_name).val(user_dialog_name_val);
		
		$("#user_dialog").dialog("close");
		
	}
	
</script>


