var list_family = function () {

	$('#footer_information').html('Pengaturan Susunan Keluarga');

	$('.center-center-center').html(
		"<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>"
		+ "<table id='list_3'></table><div id='pager_3'>"
		+ "</td></tr></table>"
	);

	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');

	jQuery("#list_3").jqGrid({
		url: 'admins/family'
		, datatype: "json"
		, height: 250
		, colNames:['Aksi', 'ID', 'Nama', 'Status']
		, colModel:[
			{name:'myac', width:55, fixed:true, sortable:false, resize:false, search:false, formatter:'actions', formatoptions:{keys:true}}
			, {name:'id', index:'id', width:50}
			, {name:'name', index:'name', width:350, editable: true}
			, {name:'status', index:'status', width:90, align:'center', editable: true, edittype:"select", formatter:"select", editoptions:{value:"1:Aktif;0:Tidak Aktif", defaultValue: "1"}}
		]
		, rowNum: 25
		, mtype: "POST"
		, rowList: [25,50,75,100]
		, pager: '#pager_3'
		, sortname: 'id'
		, viewrecords: true
		, sortorder: "asc"
		, gridview : true
		, editurl: "admins/family/change"
		, caption: "Pengaturan Susunan Keluarga"
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

	jQuery("#list_3").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
	jQuery("#list_3")[0].toggleToolbar();
	
}
