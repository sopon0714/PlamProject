<?php
set_time_limit(0);
$start = time();
require_once(dirname(__FILE__) . "/../../dbConnect.php");
date_default_timezone_set("Asia/Bangkok");
$sql = "SELECT `dim-farm`.`dbID`, StartT.`Date` AS StartT,  EndT.`Date` AS  EndT,`fact-drying`.`Period`  FROM `fact-drying` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID`=`fact-drying`.`DIMsubFID`
        INNER JOIN `dim-time`  AS StartT ON StartT.`ID` = `fact-drying`.`DIMstartDID`
        LEFT JOIN `dim-time`  AS EndT  ON EndT.`ID` = `fact-drying`.`DIMstopDID`
        ORDER BY  StartT.`Date`";
$DATA = selectData($sql);
$INFO  = array();
for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
    $Period = $DATA[$i]['Period'];
    if ($Period == 0) {
        $Period = date_diff(date_create($DATA[$i]['StartT']), date_create(date("Y-m-d")))->format("%a");
    }
    $num = 0;
    $StartDate = $DATA[$i]['StartT'];
    while ($num != $Period) {
        $INFO[$StartDate][$DATA[$i]['dbID']] = false;
        $StartDate = date('Y-m-d', strtotime('+1 day', strtotime($StartDate)));
        $num++;
    }
}
$myfile = fopen(dirname(__FILE__) . "/infoDrying.json", "w");
fwrite($myfile, json_encode($INFO));
fclose($myfile);
$end = time();
$diff = $end - $start;
$sql = "INSERT INTO `test` (`text`, `time`) VALUES (autoRunDrying run procress '$diff Sec', CURRENT_TIMESTAMP)";
addinsertData($sql);
