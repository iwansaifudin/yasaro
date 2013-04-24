<?php if($flag == 'list_1'): ?>

	<input type='hidden' id='form_id_act' style="width: 25px; font-size: 70%" />
	<input type='hidden' id='user_id_act' style="width: 25px; font-size: 70%" />

	<table id="list_1"></table>

	<script type="text/javascript">
	
		var list_1 = function () {
			
			jQuery("#list_1").jqGrid({
				datatype: 'local'
				, colNames:['ID', 'Nama', 'Kelompok', 'Periode', 'SHU Total', 'SHU Terima', 'SHU Sisa']
				, colModel:[
					{name:'id', index:'id', width:20, align:'center', sorttype:'int', formatter:'text'} 
					, {name:'name', index:'name', width:90, align:'left', sorttype:'text'}
					, {name:'cluster', index:'cluster', width:100, align:'left', sorttype:'text'}
					, {name:'period', index:'period', width:40, align:'center', sorttype:'text'}					, {name:'shu_qty', index:'shu_qty', width:50, align:'right', sorttype:'text', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'shu_received', index:'shu_received', width:50, align:'right', sorttype:'text', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
					, {name:'shu_remain', index:'shu_remain', width:50, align:'right', sorttype:'text', formatter:'currency', formatoptions: {prefix:'Rp. ', suffix:'', thousandsSeparator:'.', decimalSeparator:',', decimalPlaces:0}}
				]
				, rowNum: 100
				, sortname: 'name'
				, sortorder: 'asc'
				, altRows: true
				, width: $(window).width() - 200 - 320 - 8 - 50 - 50
				, height: 146
				, onSelectRow: function(id) {
		
					$('#user_id_act').val(id);
					get_list_2(id); // set list 2					load($('#form_id_act').val(), id, 0, 0); // load form transaction					
				}
			});
			
		}
		
		function get_list_1(key) {
			
			jQuery("#list_1").clearGridData();
			jQuery("#list_2").clearGridData();
		
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON('transactions/shu_layout/get_list_1'
				, {key: key}
				, function(data) {
					for (i = 0; i < data.length; i++) {
						jQuery("#list_1").addRowData(
							data[i]['id']
							, {
								id: data[i]['id']
								, name: data[i]['name']
								, cluster: data[i]['cluster']
								, period: data[i]['period']								, shu_qty: data[i]['shu_qty']
								, shu_received: data[i]['shu_received']
								, shu_remain: data[i]['shu_remain']
							}
						);
					}
					
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
				}
			);

		}
	</script>


<?php elseif($flag == 'list_2'): ?>

	<table id="list_2"></table>

	<script type="text/javascript">
		
		var list_2 = function () {
			
			jQuery("#list_2").jqGrid({
				datatype: 'local'
				, colNames:['ID', '', 'Form', 'Periode', 'No Ref', 'Tanggal', 'Status']
				, colModel:[
					{name:'id', index:'id', width:30, align:'right', sorttype:'text'} 
					, {name:'form_id', index:'form_id', width:0, hidden:true}
					, {name:'form_name', index:'form_name', width:60, align:'left', sorttype:'text'} 
					, {name:'period', index:'period', width:30, align:'center', sorttype:'text'}
					, {name:'ref_no', index:'ref_no', width:65, align:'left', sorttype:'text'} 
					, {name:'trans_date', index:'trans_date', width:45, align:'center', sorttype:'text'}
					, {name:'status', index:'status', width:0, hidden:true}
				]
				, sortname: 'id'
				, sortorder: 'desc'
				, altRows: true
				, width: 369 + 50
				, height: 146
				, onSelectRow: function(id) {
		
					// declare variable
					var row_data = jQuery("#list_2").getRowData(id);
					var trans_id = id;
					var form_id = row_data.form_id;
					var status = row_data.status;
					
					// load form transaction
					load(form_id, 0, trans_id, status);					
				}
			});
			
		}

		function get_list_2(id) {
		
			jQuery("#list_2").clearGridData();
		
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON('transactions/shu_layout/get_list_2'
				, {id: id}
				, function(data) {
					for (i = 0; i < data.length; i++) {
						jQuery("#list_2").addRowData(
							data[i]['id']
							, {
								id: data[i]['id']
								, form_id: data[i]['form_id']
								, form_name: data[i]['form_name']
								, period: data[i]['period']
								, ref_no: data[i]['ref_no']
								, trans_date: data[i]['trans_date']
								, status: data[i]['status']
							}
						);
					}					
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
				}
			);
		
		}
	</script>

<?php elseif($flag == 'button'): ?>

	<table border='0' cellspacing='0' cellpadding='3'><tr><td>
		<input type='text' id='key' style="font-size: 90%; width: 150px" maxlength="50" placeholder="Search"
			onkeypress="(window.event?(event.keyCode==13?get_list_1($('#key').val()):null):(event.which==13?get_list_1($('#key').val()):null))" 
		/>
		<input type='button' value='Search' style="font-size: 90%" onclick="get_list_1($('#key').val());" />
		<input type='button' value='Clear' style="font-size: 90%" onclick="$('#key').val(''); get_list_1('');" />
		|
		<input type='button' id='new' value='New' style="font-size: 90%" onclick="load($('#form_id_act').val(), 0, 0, 0);" />
		<input type='button' id='approve' value='Approve' style="font-size: 90%" onclick="approve();" />
	</td></tr></table>

	<script type="text/javascript">

		$('input, textarea').placeholder();		
		function load(form_id, user_id, trans_id, trans_status) {
			
			if(trans_status == 1) { // status close
				$('#approve').attr('disabled', 'disabled');
			} else { // status new
				$('#approve').removeAttr('disabled');
			}			
			// set url
			var url = '';
			if(form_id == 1) {
				url = 'transactions/shu/division';
			} else if(form_id == 2) {
				url = 'transactions/shu/cancellation';
			}
			
			// set parameter
			var parm = {};
			parm['user_id'] = user_id;
			parm['trans_id'] = trans_id;

			$('.center-center-center').load(
				url 
				, parm
				, function() {
					
					var stock_trans = $('#stock_trans').val();
					if(stock_trans == 0) {

						if(form_id == 1) {
							shu_division_detail_append(1, trans_status, 0);
						} else if(form_id == 2) {
							shu_cancellation_detail_append(1, trans_status, 0);
						}
					
					} else {						$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
						$.ajaxSetup({ cache: false });
						$.getJSON(
							'transactions/shu/get_stock_detail'
							, parm
							, function(data) {

								var k = 0;
								for(var j = 0; j < data.length; j++) {
								
									k++;
									var stock_id = data[j]['stock_id'];
									var stock_name = data[j]['stock_name'];
									
									if(form_id == 1) {
										shu_division_detail_append(k, trans_status);
									} else if(form_id == 2) {
										shu_cancellation_detail_append(k, trans_status);
									}
									
									$('#stock_id_' + k).val(stock_id);
									$('#stock_name_' + k).val(stock_name);
										
								}
									
								$('#loading').html("");
								$.ajaxSetup({ cache: true });
								
							}
						);
								
					}				}
			);
		
		}
		
		function approve() {
		
			// declare variable
			var parm = {};
			parm['form_id'] = $('#form_id_act').val();
			parm['shu_id'] = $('select#period option:selected').val();
			parm['stockholder_id'] = $('#stockholder_id').val();
			if(parm['stockholder_id'] == '') {
				alert('Mohon diisi terlebih dahulu data pemegang saham!');
				return false;
			}
			parm['stock_qty'] = $('#stock_qty').val();
			parm['stock_trans'] = $('#stock_trans').val();
			parm['stock_before'] = $('#stock_before').val();
			parm['stock_after'] = $('#stock_after').val();
			parm['shu_qty'] = $('#shu_qty').val();			parm['shu_trans'] = $('#shu_trans').val();
			parm['shu_before'] = $('#shu_before').val();
			parm['shu_after'] = $('#shu_after').val();
			parm['message'] = $('#message').val();

			for(var j = 1; j <= parm['stock_trans']; j++) {
				
				// check blank stock
				parm['stock_id_' + j] = $('#stock_id_' + j).val();
				
				if(parm['stock_id_' + j] == '') {
					alert('Mohon diisi terlebih dahulu data saham no ' + j + '!');
					return false;
				}
				
				// check duplicate stock
				if(j < parm['stock_qty']) {

					for(var k = (j + 1); k <= parm['stock_qty']; k++) {
						
						stock_id1 = $('#stock_id_' + j).val();
						stock_id2 = $('#stock_id_' + k).val();
						if(stock_id1 == stock_id2) {
							alert('Mohon maaf, terdapat kesamaan data saham pada no ' + j  + ' & ' + k + '!');
							return false;
						}
						
					}

				}

			}
			
			send_data(parm);
				
		}
		
		function send_data(parm) {
		
			if(!confirm('Anda yakin ingin melakukan persetujuan transaksi ini?')) return false;
			
			// update data
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON(
				'transactions/shu/approve'
				, parm
				, function(data) {
		
					var trans_id = data['trans_id'];
					var ref_no = data['ref_no'];
					var trans_status = data['trans_status'];
					
					var status_msg = (trans_status == 1?'berhasil':'gagal');
		
					if(parm['form_id'] == 1) { // division
						form = 'pembagian';
					} else if(parm['form_id'] == 2) { // cancellation
						form = 'pembatalan';
					}
					alert('Transaksi ' + form + ' shu dengan no referensi "' + ref_no + '" ' + status_msg + '!');
					
					get_list_1($('#key').val()); // refresh list 1
					get_list_2($('#user_id_act').val()); // refresh list 2
					load(parm['form_id'], 0, trans_id, trans_status); // refresh form
					
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
					
				}
			);
			
		}		
	</script>

<?php endif; ?>

