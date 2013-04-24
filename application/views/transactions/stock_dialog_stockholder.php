<div id="stock_dialog_stockholder" style="display:none; font-size: 90%">
	<input type='hidden' id='type' style="font-size: 90%" />
	<table id="list_stock_dialog_stockholder"></table><table id="pager_stock_dialog_stockholder"></table>
</div>

<script type="text/javascript">
	
	function stock_dialog_stockholder_search(title, type) {
		
		// set parameter
		$('#type').val(type);
		
		$("#stock_dialog_stockholder").dialog({
			resizable: false
			, modal: true
			, width: 395
			, height: 255			, title: title
			, open: function(event, ui) {
        		list_stock_dialog_stockholder();
	        }
	        , close: function() {
				var toggle_flag = $('#gview_list_stock_dialog_stockholder .ui-search-toolbar').css('display');
				if(toggle_flag == 'table-row') {
					jQuery("#list_stock_dialog_stockholder")[0].toggleToolbar();
					$("#list_stock_dialog_stockholder").setGridHeight(150, true);
				}
	        }
		});

		return false;
	
	}
	
	
	function list_stock_dialog_stockholder() {

		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'transactions/stock_dialog/get_cluster' 
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

				jQuery("#list_stock_dialog_stockholder").jqGrid({
					url: 'transactions/stock_dialog/get_list_stock_dialog_stockholder'
					, datatype: 'json'
					, mtype: "POST"
					, colNames:['ID', 'Nama', 'Kelompok', '', 'Saham']
					, colModel:[
						{name:'id', index:'id', width:20, align:'left', sorttype:'text'}
						, {name:'name', index:'name', width:60, align:'left', sorttype:'text'}
						, {name:'cluster_id', index:'cluster_id', width:75, align:'left', edittype:"select", formatter:"select", editoptions:{value:cluster_list, defaultValue: cluster_default}}
						, {name:'cluster_name', index:'cluster_name', width:75, align:'left', sorttype:'text', hidden: true}						, {name:'qty_before', index:'qty_before', width:30, align:'right', sorttype:'text'}
					]
					, rowNum: 100
					, sortname: 'id'
					, sortorder: 'asc'
					, altRows: true
					, width: 370
					, height: 150
					, pager: '#pager_stock_dialog_stockholder'
					, pgbuttons: false
					, recordtext: ''
					, pgtext: ''
					, ondblClickRow: function(id) {
						set_list_stock_dialog_stockholder(); // set selected item to object form
					}
				});
				
				$('#pager_stock_dialog_stockholder_center').remove();
				$('#pager_stock_dialog_stockholder_right').remove();
				jQuery("#list_stock_dialog_stockholder").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				jQuery("#list_stock_dialog_stockholder").jqGrid('navGrid','#pager_stock_dialog_stockholder',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});
		
				if($('#pager_stock_dialog_stockholder_left .ui-pg-button').css('display') == null) { // pager flag show
					
					jQuery("#list_stock_dialog_stockholder").jqGrid('navButtonAdd',"#pager_stock_dialog_stockholder",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-search',
						onClickButton:function(){ 
							
							this.toggleToolbar();
		
							var toggle_flag = $('#gview_list_stock_dialog_stockholder .ui-search-toolbar').css('display');
							if(toggle_flag == 'none') {
								$("#list_stock_dialog_stockholder").setGridHeight(150, true);
							} else {
								$("#list_stock_dialog_stockholder").setGridHeight(127, true);
							}
							
						} 
					});
					
					jQuery("#list_stock_dialog_stockholder").jqGrid('navButtonAdd',"#pager_stock_dialog_stockholder",{caption:"Refresh",title:"Refresh Search",buttonicon :'ui-icon-refresh',
						onClickButton:function(){ 
							this.clearToolbar();
						} 
					});
			
					setSearchSelect('list_stock_dialog_stockholder', 'cluster_id', 'All:Semua;' + cluster_list);
			
					jQuery("#list_stock_dialog_stockholder").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
					jQuery("#list_stock_dialog_stockholder")[0].toggleToolbar();
			
					jQuery("#list_stock_dialog_stockholder").jqGrid(
						'navButtonAdd'
						, '#pager_stock_dialog_stockholder'
						, {
							caption:"Choose"
							, buttonicon:"ui-icon-check"
							, position:"last"
							, onClickButton: function() { 
								
								set_list_stock_dialog_stockholder();
								
							}
						}
					);
					
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
		
			}
		);
		
	}
	
	function set_list_stock_dialog_stockholder() {
		
		// get grid parameter
		var grid = jQuery('#list_stock_dialog_stockholder');
		var sel_id = grid.jqGrid('getGridParam', 'selrow');
		var stock_dialog_stockholder_id = grid.jqGrid('getCell', sel_id, 'id');
		var stock_dialog_stockholder_name = grid.jqGrid('getCell', sel_id, 'name');
		var stock_dialog_stockholder_cluster = grid.jqGrid('getCell', sel_id, 'cluster_name');
		var stock_dialog_stock_qty_before = grid.jqGrid('getCell', sel_id, 'qty_before');

		// get field value
		var type = $('#type').val();
		var form_id = $('#form_id_act').val();
		var stock_qty = $('#stock_qty').val();

		$("#stockholder_id_" + type).val(stock_dialog_stockholder_id);
		$("#stockholder_name_" + type).val(stock_dialog_stockholder_name);
		$("#stockholder_cluster_" + type).val(stock_dialog_stockholder_cluster);
		$("#stock_qty_"+type+"_before").val(stock_dialog_stock_qty_before);
		if(type == 'from') {
			if(form_id == 1) { // buy
				$("#stock_qty_"+type+"_after").val(Number(stock_dialog_stock_qty_before) + Number($("#stock_qty").val()));
			} else { // mutation & sell
				$("#stock_qty_"+type+"_after").val(Number(stock_dialog_stock_qty_before) - Number($("#stock_qty").val()));
			}
		} else {
			$("#stock_qty_"+type+"_after").val(Number(stock_dialog_stock_qty_before) + Number($("#stock_qty").val()));
		}
		
		// clear stock data
		for(var i = 1; i <= stock_qty; i++) {
			$('#stock_id_' + i).val('');
			$('#stock_name_' + i).val('');
		}
		
		$("#stock_dialog_stockholder").dialog("close");

		
	}
	
</script>


