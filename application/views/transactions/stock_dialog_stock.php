<div id="stock_dialog_stock" style="display:none; font-size: 90%">
	<table id="list_stock_dialog_stock"></table><div id='pager_stock_dialog_stock'></div>
</div>

<script type="text/javascript">
	
	function stock_dialog_stock_search(title) {
		
		// validasi field sebelumnya
		var stockholder_id_from = $('#stockholder_id_from').val();
		var stockholder_id_to = $('#stockholder_id_to').val();
		if(stockholder_id_from == '' || stockholder_id_to == '') {
			alert('Mohon diisi terlebih dahulu nama pemegang saham yang kosong!');
			return false;
		}
		
		$("#stock_dialog_stock").dialog({
			resizable: false
			, modal: true
			, width: 243
			, height: 260
			, title: title
			, open: function(event, ui) {

        		list_stock_dialog_stock();

	        }
	        , close: function() {

				var toggle_flag = $('#gview_list_stock_dialog_stock .ui-search-toolbar').css('display');
				if(toggle_flag == 'table-row') {
					jQuery("#list_stock_dialog_stock")[0].toggleToolbar();
					$("#list_stock_dialog_stock").setGridHeight(150, true);
				}

	        }
		});
	
		return false;
	
	}

	function list_stock_dialog_stock() {

		jQuery("#list_stock_dialog_stock").jqGrid({
			url: 'transactions/stock_dialog/get_list_stock_dialog_stock'
			, datatype: "json"
			, mtype: "POST"
			, postData: {
			    form_id: function() {return $('#form_id_act').val(); }
			    , stockholder_id_from: function() { return $("#stockholder_id_from").val(); }
			}
			, colNames:['ID', 'Nama', 'Harga']
			, colModel:[
				{name:'id', index:'id', width:80, align:'left', sorttype:'text'}
				, {name:'name', index:'name', width:150, align:'left', sorttype:'text'}
				, {name:'price', index:'price', width:100, align:'left', sorttype:'text'}
			]
			, sortname: 'id'
			, sortorder: 'asc'
			, width: 220
			, height: 150
			, multiselect: true			, pager: '#pager_stock_dialog_stock'
			, pgbuttons: false
			, recordtext: ''
			, pgtext: ''
			, ondblClickRow: function(id) {
				set_list_stock_dialog_stock(); // set selected stock to object form
			}
			, beforeSelectRow: function (rowid, e) {
			    var $this = $(this), rows = this.rows,
			        // get id of the previous selected row
			        startId = $this.jqGrid('getGridParam', 'selrow'),
			        startRow, endRow, iStart, iEnd, i, rowidIndex;
			
			    if (!e.ctrlKey && !e.shiftKey) {
			        $this.jqGrid('resetSelection');
			    } else if (startId && e.shiftKey) {
			        $this.jqGrid('resetSelection');
			
			        // get DOM elements of the previous selected and the currect selected rows
			        startRow = rows.namedItem(startId);
			        endRow = rows.namedItem(rowid);
			        if (startRow && endRow) {
			            // get min and max from the indexes of the previous selected
			            // and the currect selected rows 
			            iStart = Math.min(startRow.rowIndex, endRow.rowIndex);
			            rowidIndex = endRow.rowIndex;
			            iEnd = Math.max(startRow.rowIndex, rowidIndex);
			            for (i = iStart; i <= iEnd; i++) {
			                // the row with rowid will be selected by jqGrid, so:
			                if (i != rowidIndex) {
			                    $this.jqGrid('setSelection', rows[i].id, false);
			                }
			            }
			        }
			
			        // clear text selection
			        if(document.selection && document.selection.empty) {
			            document.selection.empty();
			        } else if(window.getSelection) {
			            window.getSelection().removeAllRanges();
			        }
			    }
			    return true;
			}
		});
		$('#pager_stock_dialog_stock_center').remove();
		$('#pager_stock_dialog_stock_right').remove();
		jQuery("#list_stock_dialog_stock").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
		jQuery("#list_stock_dialog_stock").jqGrid('navGrid','#pager_stock_dialog_stock',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});

		if($('#pager_stock_dialog_stock_left .ui-pg-button').css('display') == null) { // pager flag show
			
			jQuery("#list_stock_dialog_stock").jqGrid('navButtonAdd',"#pager_stock_dialog_stock",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-search',
				onClickButton:function(){ 
					
					this.toggleToolbar();

					var toggle_flag = $('#gview_list_stock_dialog_stock .ui-search-toolbar').css('display');
					if(toggle_flag == 'none') {
						$("#list_stock_dialog_stock").setGridHeight(150, true);
					} else {
						$("#list_stock_dialog_stock").setGridHeight(127, true);
					}
					
				} 
			});
			
			jQuery("#list_stock_dialog_stock").jqGrid('navButtonAdd',"#pager_stock_dialog_stock",{caption:"Refresh",title:"Refresh Search",buttonicon :'ui-icon-refresh',
				onClickButton:function(){ 
					this.clearToolbar();
				} 
			});
	
			jQuery("#list_stock_dialog_stock").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
			jQuery("#list_stock_dialog_stock")[0].toggleToolbar();
	
			jQuery("#list_stock_dialog_stock").jqGrid(
				'navButtonAdd'
				, '#pager_stock_dialog_stock'
				, {
					caption:"Choose"
					, buttonicon:"ui-icon-check"
					, position:"last"
					, onClickButton: function() { 
						
						set_list_stock_dialog_stock();
						
					}
				}
			);
			
		}
		
	}
	
	function set_list_stock_dialog_stock() {

		var selectedrows = $("#list_stock_dialog_stock").jqGrid('getGridParam','selarrrow');
		if(selectedrows.length) {
			for(var j = 0; j < selectedrows.length; j++) {
			
				// get grid parameter
				var selecteddatails = $("#list_stock_dialog_stock").jqGrid('getRowData',selectedrows[j]);
				var stock_id = selecteddatails.id;
				var stock_name = selecteddatails.name;
				
				// get field value
				var form_id = $('#form_id_act').val();
				var stock_qty = Number($('#stock_qty').val());
				var stock_total_price = Number($('#stock_total_price').val());
				var stock_qty_from_before = Number($('#stock_qty_from_before').val());
				
				// jika ada field yang kosong maka diisi dulu di sini
				var is_empty = false;
				for(var i = 1; i <= stock_qty; i++) {
					
					if($("#stock_name_"+i).val() == '') {
		
						$("#stock_id_" + i).val(stock_id);
						$("#stock_name_" + i).val(stock_name);
						is_empty = true;
						break;
						
					}
					
				}				
				// jika tidak ada field yang kosong maka dibuatkan dulu baru diisi di sini
				if(
					(!is_empty && form_id == 1) // buy
					|| (!is_empty && (form_id == 2 || form_id == 3) && stock_qty < stock_qty_from_before) // mutation & sell
				) {
					
					var stock_no = stock_qty + 1;
					var total_price = stock_total_price + 25000;
					
					if(form_id == 1) { // buy
						stock_buy_detail_append(stock_no, total_price, 0, 0);
					} else if(form_id == 2) { // mutation
						stock_mutation_detail_append(stock_no, total_price, 0, 0);
					} else if (form_id == 3) { // sell
						stock_sell_detail_append(stock_no, total_price, 0, 0);
					}
					
					// set stock value
					$("#stock_id_" + stock_no).val(stock_id);
					$("#stock_name_" + stock_no).val(stock_name);
				
				}			}
		}
		
	}
	
</script>


