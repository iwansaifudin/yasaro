<div id="shu_dialog_stockholder" style="display:none; font-size: 90%">
	<table id="list_shu_dialog_stockholder"></table><table id="pager_shu_dialog_stockholder"></table>
</div>

<script type="text/javascript">
	
	function shu_dialog_stockholder(title) {
		
		$("#shu_dialog_stockholder").dialog({
			resizable: false
			, modal: true
			, width: 425
			, height: 255			, title: title
			, open: function(event, ui) {
        		list_shu_dialog_stockholder();
	        }
	        , close: function() {
				var toggle_flag = $('#gview_list_shu_dialog_stockholder .ui-search-toolbar').css('display');
				if(toggle_flag == 'table-row') {
					jQuery("#list_shu_dialog_stockholder")[0].toggleToolbar();
					$("#list_shu_dialog_stockholder").setGridHeight(150, true);
				}
	        }
		});

		return false;
	
	}
	
	
	function list_shu_dialog_stockholder() {

		$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'transactions/shu_dialog/get_cluster' 
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

				jQuery("#list_shu_dialog_stockholder").jqGrid({
					url: 'transactions/shu_dialog/get_list_shu_dialog_stockholder'
					, datatype: 'json'
					, mtype: "POST"
					, postData: {
					    shu_id: function() {return $('select#period option:selected').val(); }
					}
					, colNames:['ID', 'Nama', 'Kelompok', '', '', 'Saham', '', 'SHU', '']					, colModel:[
						{name:'id', index:'id', width:20, align:'right', sorttype:'text'}
						, {name:'name', index:'name', width:60, align:'left', sorttype:'text'}
						, {name:'cluster_id', index:'cluster_id', width:75, align:'left', edittype:"select", formatter:"select", editoptions:{value:cluster_list, defaultValue: cluster_default}}
						, {name:'cluster_name', index:'cluster_name', width:75, align:'left', sorttype:'text', hidden: true}						, {name:'shu_nominal', index:'shu_nominal', width:30, align:'right', sorttype:'text', hidden: true}
						, {name:'stock_qty', index:'stock_qty', width:30, align:'right', sorttype:'text', formatter:'number', formatoptions: {thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
						, {name:'stock_before', index:'stock_before', width:30, align:'right', sorttype:'text', hidden: true}
						, {name:'shu_qty', index:'shu_qty', width:60, align:'right', sorttype:'text', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
						, {name:'shu_before', index:'shu_before', width:60, align:'right', sorttype:'text', hidden: true}
					]
					, rowNum: 100
					, sortname: 'id'
					, sortorder: 'asc'
					, altRows: true
					, width: 400
					, height: 150
					, pager: '#pager_shu_dialog_stockholder'
					, pgbuttons: false
					, recordtext: ''
					, pgtext: ''
					, ondblClickRow: function(id) {
						set_list_shu_dialog_stockholder(); // set selected item to object form
					}
				});
				
				$('#pager_shu_dialog_stockholder_center').remove();
				$('#pager_shu_dialog_stockholder_right').remove();
				jQuery("#list_shu_dialog_stockholder").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
				jQuery("#list_shu_dialog_stockholder").jqGrid('navGrid','#pager_shu_dialog_stockholder',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});
		
				if($('#pager_shu_dialog_stockholder_left .ui-pg-button').css('display') == null) { // pager flag show
					
					jQuery("#list_shu_dialog_stockholder").jqGrid('navButtonAdd',"#pager_shu_dialog_stockholder",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-search',
						onClickButton:function(){ 
							
							this.toggleToolbar();
		
							var toggle_flag = $('#gview_list_shu_dialog_stockholder .ui-search-toolbar').css('display');
							if(toggle_flag == 'none') {
								$("#list_shu_dialog_stockholder").setGridHeight(150, true);
							} else {
								$("#list_shu_dialog_stockholder").setGridHeight(127, true);
							}
							
						} 
					});
					
					jQuery("#list_shu_dialog_stockholder").jqGrid('navButtonAdd',"#pager_shu_dialog_stockholder",{caption:"Refresh",title:"Refresh Search",buttonicon :'ui-icon-refresh',
						onClickButton:function(){ 
							this.clearToolbar();
						} 
					});
			
					setSearchSelect('list_shu_dialog_stockholder', 'cluster_id', 'All:Semua;' + cluster_list);
			
					jQuery("#list_shu_dialog_stockholder").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
					jQuery("#list_shu_dialog_stockholder")[0].toggleToolbar();
			
					jQuery("#list_shu_dialog_stockholder").jqGrid(
						'navButtonAdd'
						, '#pager_shu_dialog_stockholder'
						, {
							caption:"Choose"
							, buttonicon:"ui-icon-check"
							, position:"last"
							, onClickButton: function() { 
								
								set_list_shu_dialog_stockholder();
								
							}
						}
					);
					
				}
				
				$('#loading').html("");
				$.ajaxSetup({ cache: true });
		
			}
		);
		
	}
	
	function set_list_shu_dialog_stockholder() {
		
		// get grid parameter
		var grid = jQuery('#list_shu_dialog_stockholder');
		var sel_id = grid.jqGrid('getGridParam', 'selrow');
		var shu_dialog_stockholder_id = grid.jqGrid('getCell', sel_id, 'id');
		var shu_dialog_stockholder_name = grid.jqGrid('getCell', sel_id, 'name');
		var shu_dialog_stockholder_cluster = grid.jqGrid('getCell', sel_id, 'cluster_name');
		var shu_dialog_stockholder_shu_nominal = Number(grid.jqGrid('getCell', sel_id, 'shu_nominal'));
		var shu_dialog_stockholder_stock_qty = Number(grid.jqGrid('getCell', sel_id, 'stock_qty'));
		var shu_dialog_stockholder_stock_before = Number(grid.jqGrid('getCell', sel_id, 'stock_before'));
		var shu_dialog_stockholder_shu_qty = Number(grid.jqGrid('getCell', sel_id, 'shu_qty').replace('Rp', '').trim());		var shu_dialog_stockholder_shu_before = Number(grid.jqGrid('getCell', sel_id, 'shu_before'));
		
		// get field value
		var form_id = $('#form_id_act').val();
		var stock_trans = Number($("#stock_trans").val());
		var shu_trans = Number($("#shu_trans").val());

		// set field 
		$("#stockholder_id").val(shu_dialog_stockholder_id);
		$("#stockholder_name").val(shu_dialog_stockholder_name);
		$("#stockholder_cluster").val(shu_dialog_stockholder_cluster);
		$("#shu_nominal").val(shu_dialog_stockholder_shu_nominal);
		$("#stock_qty").val(shu_dialog_stockholder_stock_qty);
		if(form_id == 1) { // shu division
			$("#stock_after").val(shu_dialog_stockholder_stock_before + stock_trans);
		} else if(form_id == 2) { // shu cancellation
			$("#stock_after").val(shu_dialog_stockholder_stock_before - stock_trans);
		}
		$("#shu_qty").val(shu_dialog_stockholder_shu_qty);
		if(form_id == 1) { // shu division
			$("#shu_after").val(shu_dialog_stockholder_shu_before + shu_trans);
		} else if(form_id == 2) { // shu cancellation
			$("#shu_after").val(shu_dialog_stockholder_shu_before - shu_trans);
		}

		// clear stock data
		for(var i = 1; i <= stock_trans; i++) {
			$('#stock_id_' + i).val('');
			$('#stock_name_' + i).val('');
		}		$("#shu_dialog_stockholder").dialog("close");

	}
	
</script>


