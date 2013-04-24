var rpt_user = function () {

	$('#footer_information').html('Laporan Pemegang Saham');

	centerNorthLayout.hide('east');
	centerCenterLayout.hide('north');
	centerLayout.hide('north');

	$('.center-center-center').load(
		'reports/rpt_user'
		, function(text, status) {
			
			jQuery("#list_3").jqGrid({
				datatype: "local"
				, height: 250
				, colNames:[
					'ID', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'Telepon'
					, 'Kepala Keluarga', 'Status dlm Keluarga', 'Kelompok', 'Jml Saham', 'Total Harga', 'Daftar Saham'
				]
				, colModel:[
					{name:'id',index:'id', width:40, sorttype:"int", align:'right'}
					, {name:'name',index:'name', width:100}
					, {name:'birth_place',index:'birth_place', width:90}
					, {name:'birth_date',index:'birth_date', width:90, align:'center'}
					, {name:'gender',index:'gender', width:90}
					, {name:'address',index:'address', width:250}
					, {name:'telephone',index:'telephone', width:90, align:'right'}
					, {name:'patriarch',index:'patriarch', width:100}
					, {name:'family',index:'family', width:100}
					, {name:'cluster',index:'cluster', width:120}
					, {name:'stock_qty',index:'stock_qty', width:65, align:'right'}
					, {name:'stock_total_price',index:'stock_total_price', width:80, align:'right', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'list_stock',index:'list_stock', width:200}
				]
				, loadComplete: function() {
				}
				, footerrow: true
				, userDataOnFooter: true
			});
		}
	);

}

function get_rpt_user() {

	// declare variable
	var cluster_id = $('select#cluster option:selected').val();
	var user_name = $('#user_name').val();
	
	if(user_name == '') user_name = '%%';
	
	var parm = {};
	parm['cluster_id'] = cluster_id;
	parm['user_name'] = user_name;
	
	$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
	$.ajaxSetup({ cache: false });
	$.getJSON('reports/rpt_user/get_rpt_user' 
		, parm
		, function(data) {
			
			jQuery("#list_3").clearGridData();
			
			var name = 0, stock_qty = 0, stock_total_price = 0;
			for(i = 0; i < data.length; i++) {
				
				jQuery("#list_3").addRowData(
					data[i]['id']
					, {
						id: data[i]['id']
						, name: data[i]['name']
						, birth_place: data[i]['birth_place']
						, birth_date: data[i]['birth_date']
						, gender: data[i]['gender']
						, address: data[i]['address']
						, telephone: data[i]['telephone']
						, patriarch: data[i]['patriarch']
						, family: data[i]['family']
						, cluster: data[i]['cluster']
						, stock_qty: data[i]['stock_qty']
						, stock_total_price: data[i]['stock_total_price']
						, list_stock: data[i]['list_stock']
					}
				);
				
				name ++;
				stock_qty += Number(data[i]['stock_qty']);
				stock_total_price += Number(data[i]['stock_total_price']);

			}
			
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_id]').html('Total');
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_name]').html(name);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_qty]').html(stock_qty);
			$('.ui-jqgrid-ftable td[aria-describedby=list_3_stock_total_price]').html('Rp. ' + formatCurrency(stock_total_price));

			$('#loading').html("");
			$.ajaxSetup({ cache: true });

		}
	);
	
}

function get_rpt_user_excel() {

	// declare variable
	var cluster_id = $('select#cluster option:selected').val();
	var cluster_name = $('select#cluster option:selected').html();;
	var user_name = $('#user_name').val();

	if(user_name == '') user_name = '%%';

	// url encode
	cluster_name = escape(cluster_name);
	user_name = escape(user_name);

	window.location='reports/rpt_user/get_rpt_user_excel?cluster_id='+cluster_id+'&cluster_name='+cluster_name+'&user_name='+user_name;

}
