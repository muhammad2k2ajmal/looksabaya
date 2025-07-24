<?php

$visitors = new dbClass();

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
    return 'Other';
}


function selfURL() {
	$s = (empty($_SERVER["HTTPS"]) ? "" : ($_SERVER["HTTPS"] == "on")) ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}


function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}


$visitor_ip = getUserIpAddr();

$visitor_browser = get_browser_name($_SERVER['HTTP_USER_AGENT']);

$visitor_hour = date("h");

$visitor_minute = date("i");

$visitor_day = date("d");

$visitor_month = date("m");

$visitor_year = date("Y");

$visitor_refferer = getUserIpAddr();

$visited_page = selfURL();

$checkVisitorD = $visitors->getRowCount("SELECT id FROM `visitors_history` WHERE `visitor_ip` = '$visitor_ip' AND `visitor_day` = '$visitor_day'");

if($checkVisitorD==0){

	$visitorSqlQuery = $visitors->execute("INSERT INTO visitors_history (visitor_ip, visitor_browser, visitor_hour,visitor_minute, visitor_date, visitor_day, visitor_month, visitor_year, visitor_refferer, visitor_page) VALUES ('$visitor_ip', '$visitor_browser', '$visitor_hour', '$visitor_minute', '$dateTime', '$visitor_day', '$visitor_month', '$visitor_year', '$visitor_refferer', '$visited_page')");

} 

?>