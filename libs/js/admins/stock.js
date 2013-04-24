var list_stock = function () {

	$('#footer_information').html('Pengaturan Saham');

	$('.center-center-center').html(
		"<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>"
		+ "<table id='list_3'></table><div id='pager_3'>"
		+ "</td></tr></table>"
	);	// minimize layout
	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');
	jQuery("#list_3").jqGrid({
		url: 'admins/stock/get_data'
		, datatype: "json"
		, colNames:['Aksi', 'ID', 'Nama', 'Status', 'Harga', 'Pemilik', 'Tgl Beli']
		, colModel:[
			{name:'myac', width:55, fixed:true, sortable:false, resize:false, search:false, formatter:'actions', formatoptions:{keys:true
				, delOptions:{
					afterSubmit: function (response, postdata) {
				
				        idToSelect = response.responseText;
				        var result = $.parseJSON(response.responseText).result;
				        if(result == 0) {
				        	alert('Pengaturan saham gagal!');
				        } else if(result == 2) {
				        	alert('Saham sudah ada yang memiliki sehingga tidak bisa di hapus!');
				        }
				        return [true, '', response.responseText];
				        
				    }
				}
			}}
			, {name:'id', index:'id', width:50, align:'right'}
			, {name:'name', index:'name', width:100, editable: false}
			, {name:'status', index:'status', width:70, align:'center', editable: true, edittype:"select", formatter:"select", editoptions:{value:"1:Aktif;0:Tidak Aktif", defaultValue: "1"}}
			, {name:'price', index:'price', width:90, editable: false, align:'right', formatter:'currency', summaryType:'sum', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
			, {name:'stockholder', index:'stockholder', width:150, editable: false}
			, {name:'buy_date', index:'buy_date', width:90, editable: false, align:'center'}
		]
		, height: 250
		, rowNum: 25
		, mtype: "POST"
		, rowList: [25,50,75,100]
		, pager: '#pager_3'
		, sortname: 'id'
		, viewrecords: true
		, sortorder: "asc"
		, gridview : true
		, editurl: "admins/stock/change"
		, caption: "Pengaturan Saham"
		, footerrow: true		, userDataOnFooter: true	});
	jQuery("#list_3").jqGrid('navGrid','#pager_3',{edit:false,add:false,del:false,search:false,refresh:false,addtext:'Add'});
	
	jQuery("#list_3").jqGrid(
		'navButtonAdd'
		, '#pager_3'
		, {
			caption:"Generate"
			, buttonicon:"ui-icon-locked"
			, position:"first"
			, onClickButton: function() { 
				
				load_generate();
				
			}
		}
	);

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
