var shu_generate = function () {

	$('#footer_information').html('Pengadaan Sisa Hasil Usaha');

	$('.center-center-center').html(
		"<table border='0' cellspacing='0' cellpadding='0' width='100%' height='100%'><tr><td align='center'>"
		+ "<table id='list_3'></table><div id='pager_3'>"
		+ "</td></tr></table>"
	);

	centerCenterLayout.hide('north');
	centerNorthLayout.hide('east');
	centerLayout.hide('north');

	jQuery("#list_3").jqGrid({
		url: 'transactions/shu_generate'
		, datatype: "json"
		, height: 250
		, colNames:['Aksi', 'ID', 'Periode', 'Nominal', 'Batas Beli', 'Jamaah', 'Saham', 'SHU Total', 'SHU diterima', 'SHU Sisa']
		, colModel:[
			{name:'myac', width:40, fixed:true, sortable:false, resize:false, search:false, formatter:'actions', formatoptions:{keys:true, editbutton:false
				, delOptions:{
					afterSubmit: function (response, postdata) {
				
				        idToSelect = response.responseText;
				        var result = $.parseJSON(response.responseText).result;
				        if(result == 0) {
				        	alert('Mohon maaf, penghapusan data SHU gagal!');
				        } else if(result == 2) {
				        	alert('Mohon maaf, data SHU sudah ada transaksinya sehingga tidak bisa dihapus!');
				        }
				        return [true, '', response.responseText];
				       
				    }
				}
			}}			, {name:'id', index:'id', width:30, align:'right', search:false}
			, {name:'period', index:'period', width:50, editable: true, align:'center'}
			, {name:'nominal', index:'nominal', width:80, editable: true, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
			, {name:'last_buy_date', index:'last_buy_date', width:80, editable: true, align:'center', sorttype:"date"}			, {name:'stockholder_qty', index:'stockholder_qty', width:50, editable: false, align:'right', formatter:'number', formatoptions: {thousandsSeparator:'.', decimalPlaces:0}}
			, {name:'stock_qty', index:'stock_qty', width:50, editable: false, align:'right', formatter:'number', formatoptions: {thousandsSeparator:'.', decimalPlaces:0}}
			, {name:'shu_qty', index:'shu_qty', width:90, editable: false, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
			, {name:'shu_received', index:'shu_received', width:90, editable: false, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
			, {name:'shu_remain', index:'shu_remain', width:90, editable: false, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
		]
		// , onSelectRow: function(id){
			// jQuery('#list_3').jqGrid('editRow', id, true, pickdates);
		// }		, rowNum: 25
		, mtype: "POST"
		, rowList: [25,50,75,100]
		, pager: '#pager_3'
		, sortname: 'id'
		, viewrecords: true
		, sortorder: "asc"
		, gridview : true
		, editurl: "transactions/shu_generate/change"
		, caption: "Pengadaan Sisa Hasil Usaha"
	});
	
	// function pickdates(id){ jQuery("#"+id+"_last_buy_date","#list_3").datepicker({dateFormat:"yy-mm-dd"}); }
	jQuery("#list_3").jqGrid('navGrid','#pager_3'
		,{edit:false,add:true,del:false,search:false,refresh:false,addtext:'Add'}
		,{
			//Edit optoins
		}
		,{
			//Add options
			afterShowForm: function (formid) {
		    	
		    	$("#last_buy_date").attr('readonly', true);
		    	$("#last_buy_date").datepicker({
					dateFormat: 'dd M yy'
					, create: function (input, inst) {}
					, onClose: function(dateText, inst) {}
				});		    	
		    } 
			, beforeSubmit: function (postdata, formid) {
				
				if(isNaN(postdata.period)) {
					alert('Mohon "Periode" diisi dengan angka!');
					return [false, ''];
				} else if(isNaN(postdata.nominal)) {
					alert('Mohon "Nominal" diisi dengan angka!');
					return [false, ''];
				}
				
				return [true, ''];
				
			}
			, afterSubmit: function (response) {
				
		        // save the id of new row. If the format of the data returned from
		        // the server is different you should change the next row
		        // corresponds to the returned data. For example if the server returns
		        // back JSON data in the form {"myId":"123"} you should use
		        // $.parseJSON(response.responseText).myId
		        // instead of response.responseText below
		        idToSelect = response.responseText;
		        var result = $.parseJSON(response.responseText).result;
		        if(result == 0) {
		        	alert('Mohon maaf, pengadaan SHU gagal!');
		        } else if(result == 2) {
		        	alert('Mohon maaf, periode SHU sudah pernah diadakan!');
		        }
		        return [true, '', response.responseText];
		       
		    }		    
		}
		,{
			//Delete options
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
	
	setSearchSelect('list_3', 'status', 'All:All;1:Aktif;0:Tidak Aktif');

	jQuery("#list_3").jqGrid('filterToolbar',{stringResult:true, searchOnEnter:true, defaultSearch:"cn"});
	jQuery("#list_3")[0].toggleToolbar();
	
}
