var rpt_shu_trans = function () {

	$('#footer_information').html('Laporan Transaksi SHU');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_shu_trans'
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
				, width: 800
				, height: 250
				, colNames:[
					'ID', 'Form', 'No. Ref', 'Tanggal', 'Pemegang Saham'
					, 'Periode', 'Nominal'
					, 'Jml Shm', 'Shm Trans', 'Shm Sebelum', 'Shm Sesudah'
					, 'Jml SHU', 'SHU Trans', 'SHU Sebelum', 'SHU Sesudah'
					, 'Daftar Saham', 'Keterangan'
				]
				, colModel:[
					{name:'id',index:'id', width:60, sorttype:"int", align:'center', fixed: true}
					, {name:'form',index:'form', width:120, fixed: true}
					, {name:'ref_no',index:'ref_no', width:110, fixed: true}
					, {name:'trans_date',index:'trans_date', width:130, align:'center', fixed: true}
					, {name:'stockholder',index:'stockholder', width:100, fixed: true}
					, {name:'period',index:'period', width:65, align:'right', fixed: true}
					, {name:'nominal',index:'nominal', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'stock_qty',index:'stock_qty', width:65, align:'right', fixed: true}
					, {name:'stock_trans',index:'stock_trans', width:65, align:'right', fixed: true}
					, {name:'stock_before',index:'stock_before', width:65, align:'right', fixed: true}
					, {name:'stock_after',index:'stock_after', width:65, align:'right', fixed: true}
					, {name:'shu_qty',index:'shu_qty', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'shu_trans',index:'shu_trans', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'shu_before',index:'shu_before', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'shu_after',index:'shu_after', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'list_stock',index:'list_stock', width:200, fixed: true}					, {name:'message',index:'message', width:200, fixed: true}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
		}
	);

}

function get_rpt_shu_trans() {

	// declare variable
	var shu_id = $('select#period option:selected').val();
	var form_id = $('select#form option:selected').val();
	var trans_date1 = $('#trans_date1').val();
	var trans_date2 = $('#trans_date2').val();
	
	var parm = {};
	parm['shu_id'] = shu_id;
	parm['form_id'] = form_id;
	parm['trans_date1'] = trans_date1;
	parm['trans_date2'] = trans_date2;

	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_shu_trans/get_rpt_shu_trans' 
		, parm
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var form = 0
				, stock_qty = 0, stock_trans = 0, stock_before = 0, stock_after = 0
				, shu_qty = 0, shu_trans = 0, shu_before = 0, shu_after = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['id']
					, {
						id: data[i]['id']
						, form: data[i]['form']
						, ref_no: data[i]['ref_no']
						, trans_date: data[i]['trans_date']
						, stockholder: data[i]['stockholder']
						, period: data[i]['period']
						, nominal: data[i]['nominal']
						, stock_qty: data[i]['stock_qty']
						, stock_trans: data[i]['stock_trans']
						, stock_before: data[i]['stock_before']
						, stock_after: data[i]['stock_after']
						, shu_qty: data[i]['shu_qty']
						, shu_trans: data[i]['shu_trans']
						, shu_before: data[i]['shu_before']
						, shu_after: data[i]['shu_after']
						, list_stock: data[i]['list_stock']
						, message: data[i]['message']
					}
				);

				form ++;
				stock_qty += Number(data[i]['stock_qty']);
				stock_trans += Number(data[i]['stock_trans']);
				stock_before += Number(data[i]['stock_before']);
				stock_after += Number(data[i]['stock_after']);
				shu_qty += Number(data[i]['shu_qty']);
				shu_trans += Number(data[i]['shu_trans']);
				shu_before += Number(data[i]['shu_before']);
				shu_after += Number(data[i]['shu_after']);
				
			}

			$('.ui-jqgrid-ftable td[aria-describedby=list_3_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_form]').html(form);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_qty]').html(stock_qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_trans]').html(stock_trans);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_before]').html(stock_before);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_after]').html(stock_after);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_qty]').html('Rp. ' + formatCurrency(shu_qty));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_trans]').html('Rp. ' + formatCurrency(shu_trans));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_before]').html('Rp. ' + formatCurrency(shu_before));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_after]').html('Rp. ' + formatCurrency(shu_after));

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_shu_trans_excel() {

	// declare variable
	var shu_id = $('select#period option:selected').val();
	var shu_name = $('select#period option:selected').html();
	var form_id = $('select#form option:selected').val();
	var form_name = $('select#form option:selected').html();;
	var trans_date1 = $('#trans_date1').val();
	var trans_date2 = $('#trans_date2').val();

	// url encode
	shu_name = escape(shu_name);
	form_name = escape(form_name);
	trans_date1 = escape(trans_date1);
	trans_date2 = escape(trans_date2);

	window.location='reports/rpt_shu_trans/get_rpt_shu_trans_excel?shu_id='+shu_id+'&shu_name='+shu_name+'&form_id='+form_id+'&form_name='+form_name+'&trans_date1='+trans_date1+'&trans_date2='+trans_date2;

}
