<fieldset style='height: 270px; margin: <?=$top;?> auto 0;'>

	<legend><b>Yasaro</b></legend>
	
	<div id="login">
		<h1><?php if($first_change_pass==1){echo 'First ';} ?>Change Password!</h1>
		<?php
			if($first_change_pass == 1) {
				echo form_open('change_pass/validate');
			}
			echo form_hidden('user', $user, 'id="login_user"');
			echo form_hidden('first_change_pass', $first_change_pass, 'id="login_user"');
			echo '<input type="password" id="login_pass" name="old_pass" placeholder="Old Password" '.($first_change_pass==0?'onkeypress="(window.event?(event.keyCode==13?nav_change_pass_approve():null):(event.which==13?nav_change_pass_approve():null))"':null).'>';
			echo '<input type="password" id="login_pass" name="new_pass1" placeholder="New Password" '.($first_change_pass==0?'onkeypress="(window.event?(event.keyCode==13?nav_change_pass_approve():null):(event.which==13?nav_change_pass_approve():null))"':null).'>';
			echo '<input type="password" id="login_pass" name="new_pass2" placeholder="Confirm New Password" '.($first_change_pass==0?'onkeypress="(window.event?(event.keyCode==13?nav_change_pass_approve():null):(event.which==13?nav_change_pass_approve():null))"':null).'>';
			echo "<table border='0' cellpadding='0' cellspacing='0'><tr><td>";
			if($first_change_pass == 1) {

				echo form_submit('submit', 'Submit');

				echo "</td><td>";
				if($old_pass_flag == 0) { // 0:show error; 1:not show error
					echo "&nbsp;<font color='red'>Password lama salah!</font>";
				}
				if($new_pass_flag == 0) { // 0:show error; 1:not show error
					if($old_pass_flag == 0) {
						echo "<br />";
					}
					echo "&nbsp;<font color='red'>Password baru berbeda!</font>";
				}

			} else {

				echo form_button('submit', 'Sumit', "onclick='nav_change_pass_approve()'");

			}
			echo "</td></tr></table>";
			if($first_change_pass == 1) {
				echo form_close();
			}
		?>
	</div>

</fieldset>

<script type="text/javascript">
	
	function nav_change_pass_approve() {

		// declare variable
		var parm = {};
		parm['user'] = $('input:[name=user]').val();
		parm['first_change_pass'] = $('input:[name=first_change_pass]').val();
		parm['old_pass'] = $('input:[name=old_pass]').val();
		parm['new_pass1'] = $('input:[name=new_pass1]').val();
		parm['new_pass2'] = $('input:[name=new_pass2]').val();
		//alert('userid : ' + userid + '\nfirst_change_pass : ' + first_change_pass + '\nold_pass : ' + old_pass + '\nnew_pass1 : ' + new_pass1 + '\nnew_pass2 : ' + new_pass2);
	
		// update data
		$.ajaxSetup({ cache: false });
		$.getJSON(
			'change_pass/nav_change_pass_approve'
			, parm
			, function(data) {
	
				var status = data['status'];
				var old_pass_flag = data['old_pass_flag'];
				var new_pass_flag = data['new_pass_flag'];
				
				var message = '';
				if(status == 1) {
					message += 'Perubahan password berhasil!';
				} else {
	
					if(old_pass_flag == 0) { // 0:show error; 1:not show error
						message += 'Password lama salah!';
					}
					
					if(new_pass_flag == 0) { // 0:show error; 1:not show error
						if(old_pass_flag == 0) {
							message += '\n';
						}
						message += 'Password baru berbeda!';
					}
				}
				alert(message);
				
				$.ajaxSetup({ cache: true });
				
			}
		);
		
	}

</script>
