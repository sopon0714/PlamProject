<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
session_start();
$level = $_POST['level'];
if ($level == 3) {
	$province = $_POST['province'];
}
$optradio = $_POST['optradio'];
$optradio2 = $_POST['optradio2'];
if ($optradio2 == "other") {
	$messageother = $_POST['textother'];
}

$ArrayNamefarmer = json_decode($_POST['ArrayNamefarmer'], true);

$message = "\n";
if ($level == 1) {
	$message .= "แจ้งเตือนถึง: `คนทุกคน` \n";
} else if ($level == 2) {
	$message .= "แจ้งเตือนถึง: \n";
	for ($i = 0; $i < count($ArrayNamefarmer); $i++) {
		$message .= " `คุณ " . $ArrayNamefarmer[$i] . "` \n";
	}
} else {
	$message .= "แจ้งเตือนถึง: `ทุกคน ใน จังหวัด $province` \n";
}
$message .= "ระดับการแจ้งเตือน: `$optradio` \n";
$message .= "รายละเอียดเนื้อหา: `";
if ($optradio2 == "other") {
	$message .= $messageother;
} else {
	$message .= $optradio2;
}
$message .= "` \n";
if ($nameframe <> "" || $optradio <> "") {
	sendlinemesg();
	header('Content-Type: text/html; charset=utf-8');
	$res = notify_message($message);
	echo "<script>window.location='Chat.php'</script>";
}
function sendlinemesg()
{
	define('LINE_API', "https://notify-api.line.me/api/notify");
	define('LINE_TOKEN', 'U0U7L5ATAqm8kZSHmeY01HrLLi09U1CObxGxUDsbcXg');
	function notify_message($message)
	{

		$queryData = array('message' => $message);
		$queryData = http_build_query($queryData, '', '&');
		$headerOptions = array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
					. "Authorization: Bearer " . LINE_TOKEN . "\r\n"
					. "Content-Length: " . strlen($queryData) . "\r\n",
				'content' => $queryData
			)
		);
		$context = stream_context_create($headerOptions);
		$result = file_get_contents(LINE_API, FALSE, $context);
		$res = json_decode($result);
		return $res;
	}
}
