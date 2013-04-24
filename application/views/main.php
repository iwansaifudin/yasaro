<link href="<?=site_url('libs/css/jquery/layout/layout.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
<script src="<?=site_url('libs/js/jquery/layout/layout.js');?>" type="text/javascript"></script>

<ul id="nav">
	<li><a href="#" class="selected">v</a>
		<ul>
			<li><a href="#" id='nav_change_pass' onclick="nav_change_pass();">Change Password</a></li>
		</ul>
		<div class="clear"></div>
	</li>
</ul>
<div class="clear"></div>
<link href="<?=site_url('libs/css/others/nav.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
<script src="<?=site_url('libs/js/others/nav.js');?>" type="text/javascript"></script>

<div class="ui-layout-north hide noPadding noScroll" style="background-image: url(<?=site_url('libs/css/jquery/layout/images/header.jpg');?>);">

	<div style="float:left; margin-top: 5px; margin-left: 5px;" align="left">
		<a href="<?=site_url('main');?>" style="text-decoration: none;">Yasaro</a>
	</div>
	<div style="float:right; margin-top: 25px; margin-right: 20px;">
		
		<?php echo $this->session->userdata("name");?> |
		<?php echo $this->session->userdata("cluster");?> |
		<a href="#" onclick="$('#logout').click()" style="text-decoration: none;">Logout</a> |
		<form action="logout" method="post" accept-charset="utf-8">
			<input type='hidden' id='menu_expand' name='menu_expand' value="<?=trim($this->session->userdata("menu_expand"));?>" style="width: 170px; font-size: 90%" />
			<input type="submit" id="logout" value="Logout" style="display: none;" />
		</form>
	</div>

</div>

<div class="ui-layout-south hide noPadding noScroll" style="background-color: #e4e4e0;">
	
	<table border='0' cellpadding="0" cellspacing="0" width="100%"><tr valign="middle"><td width="35%">
		Powered by JQuery - This Application Best for Firefox
	</td><td align="center">
		<div id='loading' style="margin-top: 1px;"></div>
	</td><td width="35%">
		<div id="footer_information" style="float:right; margin-top: 0px; margin-right: 5px;"></div>
	</td></tr></table>
</div>

<div class="ui-layout-west hide noPadding" style="background-image: url(<?=site_url('libs/css/jquery/layout/images/menu.jpg');?>); background-size: 1px 100%;">

	<input type='hidden' id='menu_id_act' style="width: 10px; font-size: 70%" />
	<input type='hidden' id='menu_name_act' style="width: 10px; font-size: 70%" />
	<script type="text/javascript">
	
		function set_menu_act(menu_id, menu_name) {
			
			// set menu before
			var menu_id_act = $('#menu_id_act').val();
			var menu_name_act = $('#menu_name_act').val();
			$('#menu_' + menu_id_act).html(menu_name_act);
			
			// set menu after
			$('#menu_' + menu_id).html('<font color="blue">' + menu_name + '</font>');
			
			// set variable
			$('#menu_id_act').val(menu_id);
			$('#menu_name_act').val(menu_name);
			
		}
		
		function set_menu_expand(menu_id) {
			
			var equal = 0;
			var menu_expand_old = $('#menu_expand').val();
			var menu_expand_new = '';
			
			var menu_expand = menu_expand_old.split(",");
			for(i = 0; i < menu_expand.length; i++) {
				if(menu_expand[i] == menu_id) {
					equal = 1;
				} else {
					if(menu_expand_new.length == 0) {
						menu_expand_new += menu_expand[i];
					} else {
						menu_expand_new += ',' + menu_expand[i];
					}
				}
			}
			
			if(equal == 0) {
				if(menu_expand_new.length == 0) {
					menu_expand_new += menu_id;
				} else {
					menu_expand_new += ',' + menu_id;
				}
			}
			
			$('#menu_expand').val(menu_expand_new);
					
		}
		
	</script>
	
	<strong>Menu Utama</strong>
	<?php 
		// data diambil dari controller->main->index->$data['menu']
		echo $menu;
	?>
	
</div>

<div class="ui-layout-center hide noPadding noScroll">

	<div class="center-north hide noPadding noScroll">

		<div class="center-north-center hide noPadding noScroll">
		</div>
		
		<div class="center-north-east hide noPadding noScroll">
		</div>
		
	</div>
	
	<div class="center-center hide noPadding">

		<div class="center-center-north hide noPadding noScroll" style='vertical-align: middle; background-image: url(<?=site_url('libs/css/jquery/layout/images/menu.jpg');?>); background-size: 1px 100%;'>
		</div>

		<div class="center-center-east hide noPadding">
			<?php include 'admins/stock_dialog.php'; ?>
			<?php include 'admins/user_dialog.php'; ?>
			<?php include 'transactions/stock_dialog_stock.php'; ?>
			<?php include 'transactions/stock_dialog_stockholder.php'; ?>
			<?php include 'transactions/shu_dialog_stock.php'; ?>
			<?php include 'transactions/shu_dialog_stockholder.php'; ?>
		</div>

		<div class="center-center-center hide noPadding">
		</div>

	</div>

</div>
