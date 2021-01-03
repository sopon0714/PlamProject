<?php
$time_start = microtime(true);


$DATA = array();
$DATA[0] = "1";
$DATA[1] = "Hello";
$myfile = fopen("./JsonData/testttt.txt", "w");
fwrite($myfile, json_encode($DATA,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
fclose($myfile);

// chmod("./JsonData", 755); 
$time_end = microtime(true);
$time = $time_end - $time_start; #เวลาเริ่มต้น – เวลาท้ายสุด

echo "เวลาที่ใช้ในการประมวลทั้งหมด $time วินาที\n";


?>