<div id="shu_dialog_stock" style="display:none; font-size: 90%">
	<table id="list_shu_dialog_stock"></table><div id='pager_shu_dialog_stock'></div>
</div>

<script type="text/javascript">
	
	function shu_dialog_stock(title) {
		
		// validasi field sebelumnya
		var stockholder_id = $('#stockholder_id').val();
		if(stockholder_id == '') {
			alert('Mohon diisi terlebih dahulu data pemegang saham!');
			return false;
		}
		
		$("#shu_dialog_stock").dialog({
			resizable: false
			, modal: true
			, width: 243
			, height: 260
			, title: title
			, open: function(event, ui) {

        		list_shu_dialog_stock();

	        }
	        , close: function() {

				var toggle_flag = $('#gview_list_shu_dialog_stock .ui-search-toolbar').css('display');
				if(toggle_flag == 'table-row') {
					jQuery("#list_shu_dialog_stock")[0].toggleToolbar();
					$("#list_shu_dialog_stock").setGridHeight(150, true);
				}

	        }
		});
	
		return false;
	
	}

	function list_shu_dialog_stock() {

		jQuery("#list_shu_dialog_stock").jqGrid({
			url: 'transactions/shu_dialog/get_list_shu_dialog_stock'
			, datatype: "json"
			, mtype: "POST"
			, postData: {
			    shu_id: function() {return $('select#period option:selected').val(); }
			    , stockholder_id: function() { return $("#stockholder_id").val(); }
			    , form_id: function() { return $('#form_id_act').val(); }			}
			, colNames:['ID', 'Nama']
			, colModel:[
				{name:'id', index:'id', width:35, align:'left', sorttype:'text'}
				, {name:'name', index:'name', width:150, align:'left', sorttype:'text'}
			]
			, sortname: 'id'
			, sortorder: 'asc'
			, width: 220
			, height: 150
			, multiselect: true
			, pager: '#pager_shu_dialog_stock'
			, pgbuttons: false
			, recordtext: ''
			, pgtext: ''
			, ondblClickRow: function(id) {
				set_list_shu_dialog_stock(); // set selected stock to object form
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

		$('#pager_shu_dialog_stock_center').remove();
		$('#pager_shu_dialog_stock_right').remove();
		jQuery("#list_shu_dialog_stock").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');
		jQuery("#list_shu_dialog_stock").jqGrid('navGrid','#pager_shu_dialog_stock',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});

		if($('#pager_shu_dialog_stock_left .ui-pg-button').css('display') == null) { // pager flag show
			
			jQuery("#list_shu_dialog_stock").jqGrid('navButtonAdd',"#pager_shu_dialog_stock",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-search',
				onClickButton:function(){ 
					
					this.toggleToolbar();

					var toggle_flag = $('#gview_list_shu_dialog_stock .ui-search-toolbar').css('display');
					if(toggle_flag == 'none') {
						$("#list_shu_dialog_stock").setGridHeight(150, true);
					} else {
						$("#list_shu_dialog_stock").setGridHeight(127, true);
					}
					
				} 
			});
			
			jQuery("#list_shu_dialog_stock").jqGrid('navButtonAdd',"#pager_shu_dialog_stock",{caption:"Refresh",title:"Refresh Search",buttonicon :'ui-icon-refresh',
				onClickButton:function(){ 
					this.clearToolbar();
				} 
			});
	
			jQuery("#list_shu_dialog_stock").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
			jQuery("#list_shu_dialog_stock")[0].toggleToolbar();
	
			jQuery("#list_shu_dialog_stock").jqGrid(
				'navButtonAdd'
				, '#pager_shu_dialog_stock'
				, {
					caption:"Choose"
					, buttonicon:"ui-icon-check"
					, position:"last"
					, onClickButton: function() { 
						
						set_list_shu_dialog_stock();
						
					}
				}
			);
			
		}
		
	}
	
	function set_list_shu_dialog_stock() {

		var selectedrows = $("#list_shu_dialog_stock").jqGrid('getGridParam','selarrrow');
		if(selectedrows.length) {
			for(var j = 0; j < selectedrows.length; j++) {
			
				// get grid parameter
				var selecteddatails = $("#list_shu_dialog_stock").jqGrid('getRowData',selectedrows[j]);
				var stock_id = selecteddatails.id;
				var stock_name = selecteddatails.name;

				// get field value
				var form_id = $('#form_id_act').val();
				var shu_nominal = Number($('#shu_nominal').val());
				var stock_qty = Number($('#stock_qty').val());
				var stock_after = Number($('#stock_after').val());
				var shu_after = Number($('#shu_after').val());
				var stock_trans = Number($('#stock_trans').val());
				var shu_trans = Number($('#shu_trans').val());
		
				// jika ada field yang kosong maka diisi dulu di sini
				var is_empty = false;
				for(var i = 1; i <= stock_trans; i++) {
					
					if($("#stock_name_"+i).val() == '') {
		
						$("#stock_id_" + i).val(stock_id);
						$("#stock_name_" + i).val(stock_name);
						is_empty = true;
						break;
						
					}
					
				}
				
				// jika tidak ada field yang kosong maka dibuatkan dulu baru diisi di sini
				if(!is_empty && ((form_id == 1 && (stock_qty - stock_after) > 0) || (form_id == 2 && stock_after > 0))) {		
					var stock_no = stock_trans + 1;
					if(form_id == 1) { // shu division
						shu_division_detail_append(stock_no, 0, 0);
					} else if(form_id == 2) { // shu cancellation
						shu_cancellation_detail_append(stock_no, 0, 0);
					}
					
					// set stock value
					$("#stock_id_" + stock_no).val(stock_id);
					$("#stock_name_" + stock_no).val(stock_name);
					if(form_id == 1) { // shu division
						$('#stock_after').val(stock_after + 1);
						$('#shu_after').val(shu_after + shu_nominal);
					} else if(form_id == 2) { // shu cancellation
						$('#stock_after').val(stock_after - 1);
						$('#shu_after').val(shu_after - shu_nominal);
					}
					$('#stock_trans').val(stock_trans + 1);
					$('#shu_trans').val(shu_trans + shu_nominal);
				
				}
		
			}
		}

	}
	
</script>


