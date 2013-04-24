function first_load() {

	$('input, textarea').placeholder();

	if($('#menu_acc').val() == 1) {
		
		// load first page
		stock(1);
		
		// set active menu
		var menu_id = $('#menu_id_acc').val();
		var menu_name = $('#menu_name_acc').val();
		$('#menu_id_act').val(menu_id);
		$('#menu_name_act').val(menu_name);
		$('#menu_' + menu_id).html('<font color="blue">' + menu_name + '</font>');
		// $('#menu_5').css('color', 'blue');
		
	}	
	$('.file').bind("contextmenu",function(e){
		return false;
	});

	$('.folder').bind("contextmenu",function(e){
		return false;
	});
}

function setSearchSelect(list, columnName, value) {
	jQuery("#" + list).jqGrid('setColProp', columnName,
		{
			stype: 'select'
			, searchoptions: {
				value: value
				, sopt: ['eq']
			}
		}
	);
};

function get_date(date) {

	var dd = date.getDate();
	
	var month = new Array();
	month[0]="Jan";
	month[1]="Feb";
	month[2]="Mar";
	month[3]="Apr";
	month[4]="May";
	month[5]="Jun";
	month[6]="Jul";
	month[7]="Aug";
	month[8]="Sep";
	month[9]="Oct";
	month[10]="Nov";
	month[11]="Dec";
	var Mon = month[date.getMonth()]; 

	var yyyy = date.getFullYear();
	
	return dd + ' ' + Mon + ' ' + yyyy;
	
}

function get_age(date) {

	var sub_date = date.split(' ');
	var dd = sub_date[0];
	var Mon = sub_date[1];
	var yyyy = sub_date[2];
	
	var mm = '';
	if(Mon == 'Jan') { mm = '01'; }
	else if(Mon == 'Feb') {mm = '02'; }
	else if(Mon == 'Mar') {mm = '03'; }
	else if(Mon == 'Apr') {mm = '04'; }
	else if(Mon == 'May') {mm = '05'; }
	else if(Mon == 'Jun') {mm = '06'; }
	else if(Mon == 'Jul') {mm = '07'; }
	else if(Mon == 'Aug') {mm = '08'; }
	else if(Mon == 'Sep') {mm = '09'; }
	else if(Mon == 'Oct') {mm = '10'; }
	else if(Mon == 'Nov') {mm = '11'; }
	else if(Mon == 'Dec') {mm = '12'; }
	
	var age = Math.floor( ( (new Date() - new Date(yyyy+'-'+mm+'-'+dd)) / 1000 / (60 * 60 * 24) ) / 365.25 );
	
	return age;
	
}

function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
		num = "0";
		
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	num = Math.floor(num/100).toString();
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	
	return (((sign)?'':'-') + num);
}

function formatNumber(num) {
	num = num.toString().replace(/\./g,'');
	return num;
}