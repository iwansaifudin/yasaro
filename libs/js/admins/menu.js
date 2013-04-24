var list_menu = function () {

	$('#footer_information').html('Pengaturan Menu');

	$('.center-center-center').html(
		"<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>"
		+ "<table id='list_3'></table><div id='pager_3'>"
		+ "</td></tr></table>"
	);

	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');
	
	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON(
		'admins/menu/get_combobox' 
		, {}
		, function(data) {
			
			var parent_list = "0:Menu Utama";
			for(i = 0; i < data.length; i++) {
				parent_list += ";"+data[i]['id']+":"+data[i]['name'];
			}

			jQuery("#list_3").jqGrid({
				url: 'admins/menu'
				, datatype: "json"
				, height: 250
				, colNames:['Aksi', 'ID', 'Nama', 'Hirarki', 'Urutan', 'Aliran Urutan', 'Status', 'Folder', 'Link']
				, colModel:[
					{name:'myac', width:55, fixed:true, sortable:false, resize:false, search:false, formatter:'actions', formatoptions:{keys:true}}
					, {name:'id', index:'id', width:30}
					, {name:'name', index:'name', width:180, editable: true}					, {name:'parent', index:'parent', width:100, editable: true, edittype:"select", formatter:"select", editoptions:{value:parent_list, defaultValue: "All"}}
					, {name:'seq', index:'seq', width:30, align:'center', editable: true}
					, {name:'seq_flow', index:'seq_flow', width:60, align:'left', editable: false}
					, {name:'status', index:'status', width:60, align:'center', editable: true, edittype:"select", formatter:"select", editoptions:{value:"1:Aktif;0:Tidak Aktif", defaultValue: "1"}}
					, {name:'is_folder', index:'is_folder', width:60, align:'center', editable: true, edittype:"select", formatter:"select", editoptions:{value:"1:Ya;0:Tidak", defaultValue: "0"}}
					, {name:'link', index:'link', width:110, editable: true}
				]
				, rowNum: 25
				, mtype: "POST"
				, rowList: [25,50,75,100]
				, pager: '#pager_3'
				, sortname: 'id'
				, viewrecords: true
				, sortorder: "asc"
				, gridview : true
				// , multiselect: true				, editurl: "admins/menu/change"
				, caption: "Pengaturan Menu"
				// , beforeSelectRow: function (rowid, e) {
				    // var $this = $(this), rows = this.rows,
				        // // get id of the previous selected row
				        // startId = $this.jqGrid('getGridParam', 'selrow'),
				        // startRow, endRow, iStart, iEnd, i, rowidIndex;
// 				
				    // if (!e.ctrlKey && !e.shiftKey) {
				        // $this.jqGrid('resetSelection');
				    // } else if (startId && e.shiftKey) {
				        // $this.jqGrid('resetSelection');
// 				
				        // // get DOM elements of the previous selected and the currect selected rows
				        // startRow = rows.namedItem(startId);
				        // endRow = rows.namedItem(rowid);
				        // if (startRow && endRow) {
				            // // get min and max from the indexes of the previous selected
				            // // and the currect selected rows 
				            // iStart = Math.min(startRow.rowIndex, endRow.rowIndex);
				            // rowidIndex = endRow.rowIndex;
				            // iEnd = Math.max(startRow.rowIndex, rowidIndex);
				            // for (i = iStart; i <= iEnd; i++) {
				                // // the row with rowid will be selected by jqGrid, so:
				                // if (i != rowidIndex) {
				                    // $this.jqGrid('setSelection', rows[i].id, false);
				                // }
				            // }
				        // }
// 				
				        // // clear text selection
				        // if(document.selection && document.selection.empty) {
				            // document.selection.empty();
				        // } else if(window.getSelection) {
				            // window.getSelection().removeAllRanges();
				        // }
				    // }
				    // return true;
				// }
			// }).hideCol('cb');			});		
			jQuery("#list_3").jqGrid('navGrid','#pager_3',{edit:false,add:true,del:false,search:false,refresh:false,addtext:'Add'});
			
			jQuery("#list_3").jqGrid('navButtonAdd',"#pager_3",{caption:"Search",title:"Toggle Search Toolbar", buttonicon :'ui-icon-pin-s', 
				onClickButton:function(){
					 
					this.toggleToolbar();
					
					var toggle_flag = $('#gview_list_3 .ui-search-toolbar').css('display');
					if(toggle_flag == 'none') {
						$("#list_3").setGridHeight(250, true);
					} else {
						$("#list_3").setGridHeight(227, true);
					}

				} 
			});
			
			jQuery("#list_3").jqGrid('navButtonAdd',"#pager_3",{caption:"Clear",title:"Clear Search",buttonicon :'ui-icon-refresh', 
				onClickButton:function(){ 
					this.clearToolbar();
				} 
			});
			
			// jQuery("#list_3").jqGrid('navButtonAdd',"#pager_3",{caption:"Select All",title:"Select All",buttonicon :'ui-icon-refresh', 
				// onClickButton:function(){ 
					// var grid = $("#list_3"); 
					// grid.resetSelection(); 
// 					
					// var ids = grid.getDataIDs();
					// for (var i = 0; i < ids.length; i++ )
						// grid.setSelection(ids[i], false);
				// } 
			// });
// 
			// jQuery("#list_3").jqGrid('navButtonAdd',"#pager_3",{caption:"Deselect All",title:"Deselect All",buttonicon :'ui-icon-refresh', 
				// onClickButton:function(){ 
					// var grid = $("#list_3"); 
					// grid.resetSelection(); 
				// } 
			// });
// 
			// jQuery("#list_3").jqGrid('navButtonAdd',"#pager_3",{caption:"Choose",title:"Choose",buttonicon :'ui-icon-refresh', 
				// onClickButton:function(){ 
					// var selectedrows = $("#list_3").jqGrid('getGridParam','selarrrow');
					// if(selectedrows.length) {
						// for(var i = 0; i < selectedrows.length; i++) {
// 						
							// var selecteddatails = $("#list_3").jqGrid('getRowData',selectedrows[i]);
							// // do what you want here
							// alert(selecteddatails.name);
// 						
						// }
					// }
				// } 
			// });			
			setSearchSelect('list_3', 'parent', 'All:Semua;' + parent_list);
			setSearchSelect('list_3', 'status', 'All:Semua;1:Aktif;0:Tidak Aktif');
			setSearchSelect('list_3', 'is_folder', 'All:Semua;1:Ya;0:Tidak');
		
			jQuery("#list_3").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
			jQuery("#list_3")[0].toggleToolbar();
			
			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);

}
