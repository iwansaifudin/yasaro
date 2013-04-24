/* Modified from Visit http://www.yaldex.com/ for full source code
and get more free JavaScript, CSS and DHTML scripts! */
ie = (document.all) ? 1 : 0;
n = !ie;

number = true;
character = true;
metachar = true;
other = true;

function allowed_character() {
	document.onkeypress = keyDown;
	if (n) {
		document.captureEvents(Event.KEYPRESS);
	}
}

function keyDown(e) {
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	// number 0 .. 9
	if(number && (keycode >= 48 && keycode <= 57)) {
		number_flag = true;
	} else {
		number_flag = false;
	}
	
	// character A .. Z or a .. z
	if(character && ((keycode >= 65 && keycode <= 90) || (keycode >= 97 && keycode <= 122))) {
		character_flag = true;
	} else {
		character_flag = false;
	}
	
	// metacharacter !@#$%^&*().. etc
	if(metachar && ((keycode >= 33 && keycode <= 47) || (keycode >= 58 && keycode <= 64) || (keycode >= 91 && keycode <= 96) || (keycode >= 123 && keycode <= 126))) {
		metachar_flag = true;
	} else {
		metachar_flag = false;
	}
	
	// other delete, backspace, enter etc
	if(other && (keycode <= 32 || keycode >= 127)) {
		other_flag = true;
	} else {
		other_flag = false;
	}

	// refresh variable
	number = true;
	character = true;
	metachar = true;
	other = true;
	
	// return value
	if(number_flag || character_flag || metachar_flag || other_flag) {
		return true;
	} else {
		return false;
	}
	
}