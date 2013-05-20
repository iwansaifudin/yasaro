<?php if($flag == 'list_1'): ?>

	<table id="list_1"></table>

	<script type="text/javascript">
	
		var list_1 = function () {
			
			jQuery("#list_1").jqGrid({
				datatype: 'local'
				, colNames:['ID','Nama','Kelompok','Alamat','']
				, colModel:[
					{name:'id', index:'id', width:40, align:'center', sorttype:'int', formatter:'text', fixed:true} 
					, {name:'name', index:'name', width:120, align:'left', sorttype:'text', fixed:true}
					, {name:'cluster', index:'cluster', width:120, align:'left', sorttype:'text', fixed:true}
					, {name:'address', index:'address', width:500, align:'left', sorttype:'text', fixed:true}
					, {name:'blank', index:'blank'}				]
				, rowNum: 100
				, sortname: 'name'
				, sortorder: 'asc'
				, altRows: true
				, width: $(window).width() - 200 - 320 - 8 - 50 - 50
				, height: 146
				, onSelectRow: function(id) {
		
					$('#id_act').val(id);
					get_list_2(id);
					get_form(id);					
				}
			});
			
		}
		
		function get_list_1(key) {
			
			jQuery("#list_1").clearGridData();
		
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON('admins/user_layout/get_list_1'
				, {key: key}
				, function(data) {
					for (i = 0; i < data.length; i++) {
						jQuery("#list_1").addRowData(
							data[i]['id']
							, {
								id: data[i]['id']
								, name: data[i]['name']
								, cluster: data[i]['cluster']
								, address: data[i]['address']
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
				, colNames:['ID','Nama','Status Keluarga','Saham','']
				, colModel:[
					{name:'id', index:'id', width:40, align:'center', sorttype:'int', formatter:'text', fixed:true} 
					, {name:'name', index:'name', width:120, align:'left', sorttype:'text', fixed:true}
					, {name:'family', index:'family', width:100, align:'left', sorttype:'text', fixed:true}
					, {name:'stock_qty', index:'stock_qty', width:50, align:'right', sorttype:'text', fixed:true}
					, {name:'blank', index:'blank'}
				]
				, rowNum: 100
				, sortname: 'name'
				, sortorder: 'asc'
				, altRows: true
				, width: 369 + 50
				, height: 146
				, onSelectRow: function(id) {
		
					get_form(id);
					
				}
			});
			
		}
		
		function get_list_2(id) {
		
			jQuery("#list_2").clearGridData();
		
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON('admins/user_layout/get_list_2'
				, {id: id}
				, function(data) {
					for (i = 0; i < data.length; i++) {
						jQuery("#list_2").addRowData(
							data[i]['id']
							, {
								id: data[i]['id']
								, name: data[i]['name']
								, family: data[i]['family']
								, stock_qty: data[i]['stock_qty']
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
		<input type='text' id='key' style="font-size: 90%; width: 120px" maxlength="20" placeholder="Search"
			onkeypress="(window.event?(event.keyCode==13?get_list_1($('#key').val()):null):(event.which==13?get_list_1($('#key').val()):null))" 
		/>
		<input type='button' value='Search' style="font-size: 90%" onclick="get_list_1($('#key').val());" />
		<input type='button' value='Clear' style="font-size: 90%" onclick="$('#key').val(''); get_list_1($('#key').val());" />
		|
		<input type='button' id='new' value='New' style="font-size: 90%" onclick="get_form(null)"/>
		<input type='button' id='save' value='Save' style="font-size: 90%" onclick="save()"/>
		<input type='button' id='reset' value='Reset Pass' style="font-size: 90%" onclick="reset_pass();" />
		<input type='hidden' id='id_act' style="font-size: 90%" />
	</td></tr></table>
	
	<script type="text/javascript">

		function get_form(id) {
			
			// reload form
			$('.center-center-center').load('admins/user'
				, {id: id}
				, function(text, status) {
		
					if($('#id').val() == '') {
						$('#reset').attr('disabled', 'disabled');
					} else {
						$('#reset').removeAttr('disabled');
					}
					
					var birth_date = $("#birth_date").val();
					
					// birth date
					$("#birth_date").datepicker({
						changeMonth: true
						, changeYear: true
						//, showButtonPanel: true
						, yearRange: "-100:+0"
						, dateFormat: 'dd M yy'
						, create: function (input, inst) {
						}
						, onClose: function(dateText, inst) { 
							
							var birth_date = $("#birth_date").val();
							if(birth_date == '') {
								$('#age').html('0');
							} else {
								$('#age').html(get_age($("#birth_date").val()));
							}
		
						}
					});
					
					$("#birth_date").val(birth_date);
					
				}
			);
		
		}
	
		function save() {
		
			// code validation
			var code = $('#code').val();
			var code_temp = $('#code_temp').val();
			if(code != code_temp) { // jika ada penginputan code baru, maka dicek dulu sudah pernah terdaftar atau belum
				
				$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
				$.ajaxSetup({ cache: false });
				$.getJSON('admins/user_layout/check_code'
					, {code: code}
					, function(data) {
						
						if(data['exist'] == 1) {
							
							alert('ID Login "' + code + '" sudah ada yang menggunakan, silahkan menggunakan ID Login yang lain!');
							$('#loading').html("");
							
						} else {
							
							send_data();
							
						}
						
						$.ajaxSetup({ cache: true });
						
					}
				);
				
			} else {
				
				send_data();
				
			}
		
		}
		
		function send_data() {
			
			// declare variable
			var parm = {};
			parm['id'] = $('#id').val();
			parm['code'] = $('#code').val();
			parm['name'] = $('#name').val();
			parm['status'] = $('select#status option:selected').val();
			parm['birth_place'] = $('#birth_place').val();
			parm['birth_date'] = $('#birth_date').val();
			parm['gender'] = $('select#gender option:selected').val();
			parm['address1'] = $('#address1').val();
			parm['address2'] = $('#address2').val();
			parm['telephone'] = $('#telephone').val();
			parm['handphone'] = $('#handphone').val();
			parm['patriarch'] = $('#patriarch_id').val();
			parm['family'] = $('select#family option:selected').val();
			parm['cluster'] = $('select#cluster option:selected').val();
			parm['nationality'] = $('select#nationality option:selected').val();
			parm['information'] = $('#information').val();
			
			if(parm['name'] == '') {
				alert('Mohon diisi terlebih dahulu nama anggota!');
				return false;
			}
			
			if(!confirm('Anda yakin ingin menyimpan data anggota "'+parm['name']+'"?')) return false;
		
			$.ajaxSetup({ cache: false });
			$.getJSON(
				'admins/user/save' 
				, parm
				, function(data) {
					
					var id = data['id'];
					var result = data['result'];
					var result_msg = (result == 1?'berhasil':'gagal');
					alert('Data anggota atas nama "' + parm['name'] + '" ' + result_msg + ' disimpan!');
		
					get_list_1($('#key').val()); // load list user data
					get_list_2($('#id_act').val());
					get_form(id); // load form					
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
					
				}
			);
			
		}
		
		function check_code(code) {
		
			if(code == '') {
				
				alert('Mohon diisi terlebih dahulu data ID Login!');
				return false;
				
			}
			
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON('admins/user_layout/check_code'
				, {code: code}
				, function(data) {
					
					if(data['exist'] == 1) {
						alert('ID Login "' + code + '" sudah ada yang menggunakan, silahkan menggunakan ID Login yang lain!');
					} else {
						alert('ID Login "' + code + '" belum ada yang menggunakan!');
					}
					
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
					
				}
			);
		
		}
		
		function reset_pass() {
		
			var id = $('#id').val();

			if(id == '' || id == null || id == 'undefined') {
				
				alert('Silahkan dipilih terlebih dahulu data anggota!');
				return false;
				
			}
			
			if(!confirm('Anda yakin ingin mereset password anggota "' + $('#name').val() + '"!')) return false;
			
			// update data
			$('#loading').html("<img src='libs/css/jquery/layout/images/loading.gif' height='15' />");
			$.ajaxSetup({ cache: false });
			$.getJSON(
				'admins/user/reset_pass' 
				, {id: id}
				, function(data) {

					var status = data['status'];
					if(status == 1) {
						alert('Reset password telah berhasil diproses!');
					} else {
						alert('Reset password gagal diproses!');
					}
		
					$('#loading').html("");
					$.ajaxSetup({ cache: true });
					
				}
			);

		}

	</script>

<?php endif; ?>

