$('#nav li').hover(
	function () {
		//show its submenu
		$('ul', this).slideDown(100);

	}, 
	function () {
		//hide its submenu
		$('ul', this).slideUp(100);			
	}
);

function nav_change_pass() {
	
	$('.ui-layout-center').load(
		'change_pass/nav_change_pass'
		, function(text, status) {
		}
	);
	
}