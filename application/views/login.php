<fieldset>

	<legend><b>Yasaro</b></legend>
	
	<div id="login">
		<h1>Login!</h1>
		<?php

			echo form_open('login/validate');
			echo '<input type="text" id="login_user" name="user" placeholder="User">';
			echo '<input type="password" id="login_pass" name="pass" placeholder="Password">';
			echo form_submit('submit', 'Login');

			// -1: error register; 0:error userid; 2:error password; 1:not show error
			if($user_pass_flag == -1) {
				echo "&nbsp;<font color='red'>User belum terdaftar!</font>";
			} else if($user_pass_flag == 0) {
				echo "&nbsp;<font color='red'>User tidak aktif!</font>";
			} else if($user_pass_flag == 2) {
				echo "&nbsp;<font color='red'>Password salah!</font>";
			}
			
			// 0:not show message succeed; 1:show message succeed
			if($change_pass_flag == 1) { 
				echo "&nbsp;<font color='blue'>Perubahan password berhasil!</font>";
			}
			
			echo form_close();
			
		?>
	</div>

</fieldset>

