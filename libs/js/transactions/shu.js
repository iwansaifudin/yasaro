/**
 * @author Iwan Saifudin
 */

function shu(form_id) {

	// load list
	$('.center-north-center').load(
		'transactions/shu_layout/list_1' 
		, function(text, status) {

			$('#form_id_act').val(form_id);
			$('#user_id_act').val('');
			list_1(); // load list 1 
			get_list_1(''); // load list 1 data
			$('.center-north-east').load(
				'transactions/shu_layout/list_2'
				, function(text, status) {
		
					list_2(); // load list 2
					$('.center-center-north').load(
						'transactions/shu_layout/button'
						, function(text, status) {

							load(form_id, 0, 0, 0);
							// show layout
							centerLayout.show('north');							centerNorthLayout.show('east');
							centerCenterLayout.show('north');
						
							if(form_id == 1) {
								$('#footer_information').html('Transaksi Pembagian SHU');
							} else if(form_id == 2) {
								$('#footer_information').html('Transaksi Pembatalan SHU');
							}
							
						} 
					);
				
				}
			);

		}
	);

}
