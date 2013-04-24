function user() {

	// load list
	$('.center-north-center').load(
		'admins/user_layout/list_1' 
		, function(text, status) {

			list_1(); // load list 1 
			get_list_1(''); // load list 1 data

			$('.center-north-east').load(
				'admins/user_layout/list_2'
				, function(text, status) {
		
					list_2(); // load list 2
		
					$('.center-center-north').load(
						'admins/user_layout/button'
						, function(text, status) {
				
							get_form(null);
						
							// show layout
							centerLayout.show('north');
							centerNorthLayout.show('east');
							centerCenterLayout.show('north');
						
							$('#footer_information').html('Pendaftaran Jamaah');
				
						} 
					);
				
				}
			);

		}
	);

}
