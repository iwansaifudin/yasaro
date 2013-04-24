<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Yasaro</title>
	<meta name="author" content="Iwan Saifudin" />
	<!-- Date: 2012-12-27 -->
	
	<!-- css -->
	
	<!-- css jquery -->
	<link href="<?=site_url('libs/css/jquery/layout/layout-default-latest.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/tree/jquery.treeview.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/tree/screen.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/grid/jquery-ui-1.8.2.custom.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/grid/ui.jqgrid.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/grid/grid.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/jquery/placeholder/placeholder.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">

	<!-- css others -->
	<link href="<?=site_url('libs/css/others/login.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/others/content.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">
	<link href="<?=site_url('libs/css/others/public.css');?>" rel="stylesheet" type="text/css" media="Screen" charset="utf-8">

	<!-- javascript -->

	<!-- javascript jquery -->
	<script src="<?=site_url('libs/js/jquery/jquery-1.7.2.min.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/placeholder/jquery.placeholder.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/grid/i18n/grid.locale-en.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/grid/jquery.jqGrid.min.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/layout/jquery.layout-latest.js');?>" type="text/javascript"></script> 
	<script src="<?=site_url('libs/js/jquery/layout/jquery-ui-latest.js');?>" type="text/javascript"></script> 
	<script src="<?=site_url('libs/js/jquery/tree/jquery.cookie.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/tree/jquery.treeview.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/tree/jquery.treeview.edit.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/jquery/tree/tree.js');?>" type="text/javascript"></script>

	<!-- javascript others -->
	<script src="<?=site_url('libs/js/others/public.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/others/allowed_character.js');?>" type="text/javascript"></script>

	<!-- javascript content admin -->
	<script src="<?=site_url('libs/js/admins/menu.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/role.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/role_menu.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/role_user.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/cluster.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/user.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/family.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/admins/stock.js');?>" type="text/javascript"></script>
	
	<!-- javascript content transaction -->
	<script src="<?=site_url('libs/js/transactions/stock.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/transactions/shu_generate.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/transactions/shu.js');?>" type="text/javascript"></script>

	<!-- javascript content report -->
	<script src="<?=site_url('libs/js/reports/rpt_stock.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/reports/rpt_user.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/reports/rpt_stock_trans.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/reports/rpt_shu.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/reports/rpt_shu_stock.js');?>" type="text/javascript"></script>
	<script src="<?=site_url('libs/js/reports/rpt_shu_trans.js');?>" type="text/javascript"></script>

</head>

<body onload="first_load();">
