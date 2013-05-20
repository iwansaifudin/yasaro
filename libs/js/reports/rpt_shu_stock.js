var rpt_shu_stock = function () {

	$('#footer_information').html('Laporan Saham SHU');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_shu_stock'
		, function(text, status) {
			
			jQuery("#list_3").jqGrid({
				datatype: "local"
				, width: 800
				, height: 250
				, colNames:[
					'ID', 'Periode', 'Nominal', 'Pemegang Saham', 'Kelompok'
					, 'Jml Shm', 'Shm diterima', 'Sisa Shm'
					, 'Jml SHU', 'SHU diterima', 'Sisa SHU'
					, 'Daftar Shm diterima', 'Daftar Sisa Shm'
				]
				, colModel:[
					{name:'id',index:'id', width:40, sorttype:"int", align:'right', fixed: true}
					, {name:'period',index:'period', width:50, align:'center', fixed: true}
					, {name:'nominal',index:'nominal', width:70, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'stockholder',index:'stockholder', width:100, fixed: true}
					, {name:'cluster',index:'cluster', width:120, fixed: true}
					, {name:'stock_qty',index:'stock_qty', width:65, align:'right', fixed: true}
					, {name:'stock_received',index:'stock_received', width:65, align:'right', fixed: true}
					, {name:'stock_remain',index:'stock_remain', width:65, align:'right', fixed: true}
					, {name:'shu_qty',index:'shu_qty', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'shu_received',index:'shu_received', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'shu_remain',index:'shu_remain', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}, fixed: true}
					, {name:'list_stock_received',index:'list_stock_received', width:200, fixed: true}
					, {name:'list_stock_remain',index:'list_stock_remain', width:200, fixed: true}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
		}
	);

}

function get_rpt_shu_stock() {

	// declare variable
	var shu_id = $('select#period option:selected').val();
	var cluster_id = $('select#cluster option:selected').val();
	var user_name = $('#user_name').val();
	
	if(user_name == '') user_name = '%%';
	
	var parm = {};
	parm['shu_id'] = shu_id;
	parm['cluster_id'] = cluster_id;
	parm['user_name'] = user_name;
	
	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_shu_stock/get_rpt_shu_stock' 
		, parm
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var stock_qty = 0, stock_received = 0, stock_remain = 0
				, shu_qty = 0, shu_received = 0, shu_remain = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['id']
					, {
						id: data[i]['id']
						, period: data[i]['period']
						, nominal: data[i]['nominal']
						, stockholder: data[i]['stockholder']
						, cluster: data[i]['cluster']
						, stock_qty: data[i]['stock_qty']
						, stock_received: data[i]['stock_received']
						, stock_remain: data[i]['stock_remain']
						, shu_qty: data[i]['shu_qty']
						, shu_received: data[i]['shu_received']
						, shu_remain: data[i]['shu_remain']
						, list_stock_received: data[i]['list_stock_received']
						, list_stock_remain: data[i]['list_stock_remain']
					}
				);
				
				stock_qty += Number(data[i]['stock_qty']);
				stock_received += Number(data[i]['stock_received']);
				stock_remain += Number(data[i]['stock_remain']);
				shu_qty += Number(data[i]['shu_qty']);
				shu_received += Number(data[i]['shu_received']);
				shu_remain += Number(data[i]['shu_remain']);
				
			}
			
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_qty]').html(stock_qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_received]').html(stock_received);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_remain]').html(stock_remain);			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_qty]').html('Rp. ' + formatCurrency(shu_qty));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_received]').html('Rp. ' + formatCurrency(shu_received));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_remain]').html('Rp. ' + formatCurrency(shu_remain));
			
			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_shu_stock_excel() {

	// declare variable
	var shu_id = $('select#period option:selected').val();
	var shu_name = $('select#period option:selected').html();
	var cluster_id = $('select#cluster option:selected').val();
	var cluster_name = $('select#cluster option:selected').html();;
	var user_name = $('#user_name').val();

	if(user_name == '') user_name = '%%';

	// url encode
	shu_name = escape(shu_name);
	cluster_name = escape(cluster_name);
	user_name = escape(user_name);

	window.location='reports/rpt_shu_stock/get_rpt_shu_stock_excel?shu_id='+shu_id+'&shu_name='+shu_name+'&cluster_id='+cluster_id+'&cluster_name='+cluster_name+'&user_name='+user_name;

}
