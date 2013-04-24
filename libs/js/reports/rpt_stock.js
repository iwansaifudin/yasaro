var rpt_stock = function () {

	$('#footer_information').html('Laporan Saham');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_stock'
		, function(text, status) {
			
			jQuery("#list_3").jqGrid({
				datatype: "local"
				, height: 250
				, colNames:[
					'ID', 'Saham', 'Harga', 'Pemegang Saham', 'Kelompok', 'Tgl Pembelian'
				]
				, colModel:[
					{name:'stock_id',index:'stock_id', width:50, sorttype:"int", align:'right'}
					, {name:'stock_name',index:'stock_name', width:80}
					, {name:'price',index:'price', width:70, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'stockholder_name',index:'stockholder_name', width:150}
					, {name:'stockholder_cluster',index:'stockholder_cluster', width:150}
					, {name:'buy_date',index:'buy_date', width:90, align:'center'}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
			
		}
	);

}

function get_rpt_stock() {

	// declare variable
	var stock_name = $('#stock_name').val();
	var stockholder_name = $('#stockholder_name').val();
	
	if(stock_name == '') stock_name = '%%';
	if(stockholder_name == '') stockholder_name = '%%';
	
	var parm = {};
	parm['stock_name'] = stock_name;
	parm['stockholder_name'] = stockholder_name;
	
	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_stock/get_rpt_stock' 
		, parm
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var stock_name = 0, price = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['stock_id']
					, {
						stock_id: data[i]['stock_id']
						, stock_name: data[i]['stock_name']
						, price: data[i]['price']
						, stockholder_name: data[i]['stockholder_name']
						, stockholder_cluster: data[i]['stockholder_cluster']
						, buy_date: data[i]['buy_date']
					}
				);
				
				stock_name ++;
				price += Number(data[i]['price']);

			}

			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_name]').html(stock_name);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_price]').html('Rp. ' + formatCurrency(price));

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_stock_excel() {

	// declare variable
	var stock_name = $('#stock_name').val();
	var stockholder_name = $('#stockholder_name').val();
	
	if(stock_name == '') stock_name = '%%';
	if(stockholder_name == '') stockholder_name = '%%';

	// url encode
	stock_name = escape(stock_name);
	stockholder_name = escape(stockholder_name);

	window.location='reports/rpt_stock/get_rpt_stock_excel?stock_name='+stock_name+'&stockholder_name='+stockholder_name;

}
