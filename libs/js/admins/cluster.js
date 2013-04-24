var list_cluster = function () {

	$('#footer_information').html('Pengaturan Kelompok');

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
		'admins/cluster/get_combobox' 
		, {}
		, function(data) {
			
			var parent_list = "0:Jakarta Selatan 1";
			for(i = 0; i < data.length; i++) {
				parent_list += ";"+data[i]['id']+":"+data[i]['name'];
			}

			jQuery("#list_3").jqGrid({
				url: 'admins/cluster'
				, datatype: "json"
				, height: 250
				, colNames:['Aksi', 'ID', 'Nama', 'Status', 'Tingkat', 'Hirarki']
				, colModel:[
					{name:'myac', width:55, fixed:true, sortable:false, resize:false, search:false, formatter:'actions', formatoptions:{keys:true}}
					, {name:'id', index:'id', width:30}
					, {name:'name', index:'name', width:150, editable: true}
					, {name:'status', index:'status', width:70, align:'center', editable: true, edittype:"select", formatter:"select", editoptions:{value:"1:Aktif;0:Tidak Aktif", defaultValue: "1"}}
					, {name:'level', index:'level', width:70, editable: true, edittype:"select", formatter:"select", editoptions:{value:"2:Kelompok;1:Desa", defaultValue: "2"}}
					, {name:'parent', index:'parent', width:150, editable: true, edittype:"select", formatter:"select", editoptions:{value:parent_list, defaultValue: "All"}}
				]
				, rowNum: 25
				, mtype: "POST"
				, rowList: [25,50,75,100]
				, pager: '#pager_3'
				, sortname: 'id'
				, viewrecords: true
				, sortorder: "asc"
				, gridview : true
				, editurl: "admins/cluster/change"
				, caption: "Pengaturan Kelompok"
			});
		
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
		
			setSearchSelect('list_3', 'status', 'All:Semua;1:Aktif;0:Tidak Aktif');
			setSearchSelect('list_3', 'level', 'All:Semua;1:Desa;2:Kelompok');
			setSearchSelect('list_3', 'parent', 'All:Semua;' + parent_list);
		
			jQuery("#list_3").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
			jQuery("#list_3")[0].toggleToolbar();

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}
