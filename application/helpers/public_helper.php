<?php

function is_logged_in($profile) {
		
	$is_logged_in = $profile->session->userdata("is_logged_in");

	if(!isset($is_logged_in) || $is_logged_in != true) {
		echo 'Mohon maaf, Anda tidak diperkenankan masuk secara langsung,<br />silahkan melakukan <a href="login">login</a> terlebih dahulu!';
		die();
	}
	
}

function now($profile, $format) {

	$sql = "select date_format(now(), '$format') now";
	$query = $profile->db->query($sql);
	$data = $query->row_array();
			
	return $data['now'];
	
}

function write_log($profile, $method, $str) {
		
	// get paramater profile
	$user_id = $profile->session->userdata("id");

	// write file
	$file = "logs/" . date("Ymd") . ".log";
	write_file($file, date("H:i:s")." | $user_id => $method -> " . $str . "\n", 'a');
	
}

function romanic_number($integer, $upcase = true) {
		
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
        foreach($table as $rom=>$arb)
        {
            if($integer >= $arb)
            {
                $integer -= $arb;
                $return .= $rom;
                break;
            }
        }
    }

    return $return;
	
}