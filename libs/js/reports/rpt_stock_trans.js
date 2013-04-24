var rpt_stock_trans = function () {

	$('#footer_information').html('Laporan Transaksi Saham');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_stock_trans'
		, function(text, status) {
			
			$("#trans_date1").datepicker();
			$("#trans_date1").datepicker("option", "dateFormat", "dd M yy");
			$("#trans_date2").datepicker();
			$("#trans_date2").datepicker("option", "dateFormat", "dd M yy");
			
			var today = new Date();
			var prev_week = new Date(new Date().setDate(today.getDate() - 7));
			$("#trans_date1").val(get_date(prev_week));
			$("#trans_date2").val(get_date(today));

			jQuery("#list_3").jqGrid({
				datatype: "local"
				, height: 250
				, colNames:[
					'ID', 'Form', 'No. Ref', 'Tanggal', 'User'
					, 'Jml Saham', 'Total Harga', 'Daftar Saham'
					, 'Pemegang Saham 1', 'Kelompok 1', 'Jml Sblm 1', 'Jml Ssdh 1'
					, 'Pemegang Saham 2', 'Kelompok 2', 'Jml Sblm 2', 'Jml Ssdh 2'
					, 'Keterangan'
				]
				, colModel:[
					{name:'id',index:'id', width:60, sorttype:"int", align:'center'}
					, {name:'form_name',index:'form_name', width:120}
					, {name:'ref_no',index:'ref_no', width:110}
					, {name:'trans_date',index:'trans_date', width:130, align:'center'}
					, {name:'user_name',index:'user_name', width:100}
					, {name:'qty',index:'qty', width:65, align:'right'}
					, {name:'total_price',index:'total_price', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'list_stock',index:'list_stock', width:200}
					, {name:'stockholder_name_from',index:'stockholder_name_from', width:120}
					, {name:'stockholder_cluster_from',index:'stockholder_cluster_from', width:160}
					, {name:'qty_from_before',index:'qty_from_before', width:65, align:'right'}
					, {name:'qty_from_after',index:'qty_from_after', width:65, align:'right'}
					, {name:'stockholder_name_to',index:'stockholder_name_to', width:120}
					, {name:'stockholder_cluster_to',index:'stockholder_cluster_to', width:160}
					, {name:'qty_to_before',index:'qty_to_before', width:65, align:'right'}
					, {name:'qty_to_after',index:'qty_to_after', width:65, align:'right'}
					, {name:'message',index:'message', width:200}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
		}
	);

}

function get_rpt_stock_trans() {

	// declare variable
	var form_id = $('select#form option:selected').val();
	var trans_date1 = $('#trans_date1').val();
	var trans_date2 = $('#trans_date2').val();
	
	var parm = {};
	parm['form_id'] = form_id;
	parm['trans_date1'] = trans_date1;
	parm['trans_date2'] = trans_date2;

	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_stock_trans/get_rpt_stock_trans' 
		, parm
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var qty = 0, total_price = 0
				, qty_from_before = 0, qty_from_after = 0
				, qty_to_before = 0, qty_to_after = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['id']
					, {
						id: data[i]['id']
						, form_name: data[i]['form_name']
						, ref_no: data[i]['ref_no']
						, trans_date: data[i]['trans_date']
						, user_name: data[i]['user_name']
						, qty: data[i]['qty']
						, total_price: data[i]['total_price']
						, list_stock: data[i]['list_stock']
						, stockholder_name_from: data[i]['stockholder_name_from']
						, stockholder_cluster_from: data[i]['stockholder_cluster_from']
						, qty_from_before: data[i]['qty_from_before']
						, qty_from_after: data[i]['qty_from_after']
						, stockholder_name_to: data[i]['stockholder_name_to']
						, stockholder_cluster_to: data[i]['stockholder_cluster_to']
						, qty_to_before: data[i]['qty_to_before']
						, qty_to_after: data[i]['qty_to_after']
						, message: data[i]['message']
					}
				);
				
				qty += Number(data[i]['qty']);
				total_price += Number(data[i]['total_price']);
				qty_from_before += Number(data[i]['qty_from_before']);
				qty_from_after += Number(data[i]['qty_from_after']);
				qty_to_before += Number(data[i]['qty_to_before']);
				qty_to_after += Number(data[i]['qty_to_after']);

			}
			
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_qty]').html(qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_total_price]').html('Rp. ' + formatCurrency(total_price));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_qty_from_before]').html(qty_from_before);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_qty_from_after]').html(qty_from_after);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_qty_to_before]').html('Rp. ' + formatCurrency(qty_to_before));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_qty_to_after]').html('Rp. ' + formatCurrency(qty_to_after));

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_stock_trans_excel() {

	// declare variable
	var form_id = $('select#form option:selected').val();
	var form_name = $('select#form option:selected').html();;
	var trans_date1 = $('#trans_date1').val();
	var trans_date2 = $('#trans_date2').val();

	// url encode
	form_name = escape(form_name);
	trans_date1 = escape(trans_date1);
	trans_date2 = escape(trans_date2);

	window.location='reports/rpt_stock_trans/get_rpt_stock_trans_excel?form_id='+form_id+'&form_name='+form_name+'&trans_date1='+trans_date1+'&trans_date2='+trans_date2;

}
