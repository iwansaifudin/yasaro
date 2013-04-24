var rpt_shu = function () {

	$('#footer_information').html('Laporan SHU');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_shu'
		, function(text, status) {
			
			jQuery("#list_3").jqGrid({
				datatype: "local"
				, height: 250
				, colNames:[
					'ID', 'Periode', 'Nominal', 'Batas Tgl Beli', 'Jml Jmh'
					, 'Jml Shm', 'Shm diterima', 'Sisa Shm'
					, 'Jml SHU', 'SHU diterima', 'Sisa SHU'
				]
				, colModel:[
					{name:'id',index:'id', width:30, sorttype:"int", align:'right'}
					, {name:'period',index:'period', width:50}
					, {name:'nominal',index:'nominal', width:70, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'last_buy_date',index:'last_buy_date', width:90, align:'center'}
					, {name:'stockholder_qty',index:'stockholder_qty', width:50, align:'right'}
					, {name:'stock_qty',index:'stock_qty', width:50, align:'right'}
					, {name:'stock_received',index:'stock_received', width:50, align:'right'}
					, {name:'stock_remain',index:'stock_remain', width:50, align:'right'}
					, {name:'shu_qty',index:'shu_qty', width:100, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'shu_received',index:'shu_received', width:100, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'shu_remain',index:'shu_remain', width:100, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
			
		}
	);

}

function get_rpt_shu() {

	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_shu/get_rpt_shu' 
		, {}
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var stockholder_qty = 0
				, stock_qty = 0, stock_received = 0, stock_remain = 0
				, shu_qty = 0, shu_received = 0, shu_remain = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['id']
					, {
						id: data[i]['id']
						, period: data[i]['period']
						, nominal: data[i]['nominal']
						, last_buy_date: data[i]['last_buy_date']
						, stockholder_qty: data[i]['stockholder_qty']
						, stock_qty: data[i]['stock_qty']
						, stock_received: data[i]['stock_received']
						, stock_remain: data[i]['stock_remain']
						, shu_qty: data[i]['shu_qty']
						, shu_received: data[i]['shu_received']
						, shu_remain: data[i]['shu_remain']
					}
				);

				stockholder_qty += Number(data[i]['stockholder_qty']);
				stock_qty += Number(data[i]['stock_qty']);
				stock_received += Number(data[i]['stock_received']);
				stock_remain += Number(data[i]['stock_remain']);
				shu_qty += Number(data[i]['shu_qty']);
				shu_received += Number(data[i]['shu_received']);
				shu_remain += Number(data[i]['shu_remain']);
				
			}

			$('.ui-jqgrid-ftable td[aria-describedby=list_3_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stockholder_qty]').html(stockholder_qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_qty]').html(stock_qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_received]').html(stock_received);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_remain]').html(stock_remain);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_qty]').html('Rp. ' + formatCurrency(shu_qty));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_received]').html('Rp. ' + formatCurrency(shu_received));
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_shu_remain]').html('Rp. ' + formatCurrency(shu_remain));

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_shu_excel() {

	window.location='reports/rpt_shu/get_rpt_shu_excel';

}
