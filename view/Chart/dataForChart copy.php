<?php
require_once("../../dbConnect.php");
connectDB();
session_start(); 
require_once("../../set-log-login.php");
include_once("./../../query/query.php");

if(isset($_POST['request'])){
    $request = $_POST['request'];

    switch($request){
        case 'dist' :
            $id = $_POST['id'];
            print_r(json_encode(getDistrinctInProvince($id)));

        break;
        case 'subdist' :
            $id = $_POST['id'];
            print_r(json_encode(getSubDistrinctInDistrinct($id)));
        break;
        case 'farm' :
            $id = $_POST['id'];
            $sql = "SELECT `log-farm`.`ID`, `dim-farm`.`Name`,`dim-farm`.`dbID`,`dim-address`.`dbsubDID` FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE `log-farm`.`ID` IN (
            SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
            WHERE `log-farm`.`DIMSubfID` IS NULL
            GROUP BY `dim-farm`.`dbID`)
            AND `dim-address`.`dbsubDID`= '$id'";
            print_r(json_encode(selectData($sql)));

        break;
        case 'subfarm' :
            $id = $_POST['id'];
            $sql = "SELECT `log-farm`.`ID`, subfarm.`Name`,subfarm.`dbID` FROM `log-farm`
            JOIN `dim-farm` AS farm ON `log-farm`.`DIMfarmID` = farm.`ID`
             JOIN `dim-farm`AS subfarm ON `log-farm`.`DIMSubfID` = subfarm.`ID`
            WHERE `log-farm`.`ID` IN (
            SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMSubfID` = `dim-farm`.`ID`
            WHERE `log-farm`.`DIMSubfID` IS NOT NULL
            GROUP BY `dim-farm`.`dbID`)
            AND farm.`dbID` = '$id'";
            print_r(json_encode(selectData($sql)));

        break;
        case 'selectyear' :
            print_r(json_encode(getYearAgriMap()));
        break;
        case 'chart':
            $chose_label1 = $_POST['chose_label1'];
            $chose_label2 = $_POST['chose_label2'];
            $chose_type = $_POST['chose_type'];
            $chose_cal = $_POST['chose_cal'];
            $chose_cond = $_POST['chose_cond'];
            $SET1 = $_POST['SET1'];
            $SET2 = $_POST['SET2'];
            $SET3 = $_POST['SET3'];

            $label2 = "";
            $label2Add = "";
            $groupBy1 = "";
            $groupBy2 = "";

            $WHERE = "";
            if($SET1 != null){
                $WHERE = " AND t7.".$SET1[0]. "= \"".$SET1[1]."\"";
                for($i=2;$i<count($SET1);$i++){
                    $WHERE = $WHERE." OR t7.".$SET1[0]." = \"".$SET1[$i]."\"";
                }
            }
            if($SET2 != null){
                $WHERE =$WHERE." AND t7.".$SET2[0]. "= \"".$SET2[1]."\"";
                for($i=2;$i<count($SET2);$i++){
                    $WHERE = $WHERE." OR t7.".$SET2[0]." = \"".$SET2[$i]."\"";
                }
            }
            if($SET3 != null){
                if($SET3[0] == "Year2"){
                    $WHERE = $WHERE." AND t7.".$SET3[0]." BETWEEN ".$SET3[1]." AND ".$SET3[2];
                }
                if($SET3[3] == "Month"){
                    $WHERE = $WHERE." AND t7.".$SET3[0]." = ".$SET3[1];
                    $WHERE = $WHERE." AND t7.".$SET3[3]." BETWEEN ".$SET3[4]." AND ".$SET3[5];
                }
                if($SET3[6] == "dd"){
                    $WHERE = $WHERE." AND t7.".$SET3[3]." = ".$SET3[4];
                    $WHERE = $WHERE." AND t7.".$SET3[6]." BETWEEN ".$SET3[7]." AND ".$SET3[8];
                }
            }

            if($chose_label2 != ""){
                if($chose_type == "water2"){
                    $label2 = "t10.".$chose_label2." AS label2 ,";
                    $label2Add = "t9.".$chose_label2.",";
                    $groupBy1 = ",t9.".$chose_label2;
                    $groupBy2 = ",t10.".$chose_label2;
                }else{
                    $label2 = "t9.".$chose_label2." AS label2 ,";
                    $groupBy1 = ",t7.".$chose_label2;
                    $groupBy2 = ",t9.".$chose_label2;
                }
                
            }
            //sql 
            if($chose_type == "cutbranch" || $chose_type == "pestcontrol" || $chose_type == "pest" || $chose_type == "fertilize1"){
                if($chose_type == "cutbranch"){
                    $log = "`log-activity`";
                    $DBactID = "AND ".$log.".`DBactID` = 1";
                }else if($chose_type == "pestcontrol"){
                    $DBactID = 2;
                    $log = "`log-activity`";
                    $DBactID = "AND ".$log.".`DBactID` = 2";
                }else if($chose_type == "pest"){
                    $log = "`log-pestalarm`";
                    $DBactID = "";
                }else if($chose_type == "fertilize1"){
                    $log = "`log-fertilising`";
                    $DBactID = "";
                }

                $sql = "SELECT t9.".$chose_label1." AS label1 ,".$label2." ".$chose_cal."(t9.times) AS data FROM (
                    SELECT t7.SF_dbID,COUNT(t7.SF_dbID) AS times,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.FM_name  FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName` AS FM_name FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                        SELECT t00.F_dbID,t00.F_name,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMdateID`,t00.`DIMownerID` FROM (
                            SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                            SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                            GROUP BY `dim-farm`.`dbID`) AS t1
                            JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                            JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`
                            WHERE ".$log.".`isDelete` =0 AND ".$log.".`DBactID` = 1)AS t00
                            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                            JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                            GROUP BY `dim-farm`.`dbID`)AS t4 
                            ON t4.dbID =  t2.dbID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1 ".$WHERE." 
                    GROUP BY t7.SF_dbID,t7.".$chose_label1." ".$groupBy1.")AS t9
                    GROUP BY t9.".$chose_label1." ".$groupBy2;
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "ครั้ง";
                
            }else if($chose_type == "water1"){
                $log = "`fact-watering`";
                $sql = "SELECT t9.".$chose_label1." AS label1 ,".$label2." ".$chose_cal."(t9.days) AS data FROM (
                    SELECT t7.SF_dbID,COUNT(DISTINCT(t7.Date)) AS days,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName` AS FM_name FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.Date,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                        SELECT t00.F_dbID,t00.F_name,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMdateID`,t00.`DIMownerID` FROM (
                            SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                            SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                            GROUP BY `dim-farm`.`dbID`) AS t1
                            JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                            JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`)AS t00
                            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                            JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                            JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                            GROUP BY `dim-farm`.`dbID`)AS t4 
                            ON t4.dbID =  t2.dbID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1 ".$WHERE." 
                    GROUP BY t7.SF_dbID,t7.".$chose_label1." ".$groupBy1.")AS t9
                    GROUP BY t9.".$chose_label1." ".$groupBy2;
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "วัน";
            }else if($chose_type == "water2"){
                $log = "`fact-drying`";
                $sql = "SELECT t10.".$chose_label1." AS label1 ,".$label2." ".$chose_cal."(t10.days) AS data FROM (
                    SELECT t9.".$chose_label1.",".$label2Add."t9.SF_dbID,SUM(t9.days) AS days FROM (
                    SELECT t7.SF_dbID,t7.`Period` AS days,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.Date,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName` AS FM_name,t6.`Period` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.`Date`,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID`,t5.`Period` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMstopDID`,t2.`DIMownerID`,t2.`Period`  FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMstopDID`,".$log.".`DIMownerID`,".$log.".`Period` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    GROUP BY `dim-farm`.`dbID`) AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`)AS t2
                    JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS t4 
                    ON t4.DIMSubfID =  t2.DIMsubFID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMstopDID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1 ".$WHERE." )AS t9
                    GROUP BY t9.SF_dbID,t9.".$chose_label1." ".$groupBy1.") AS t10
                    GROUP BY t10.".$chose_label1." ".$groupBy2;
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "วัน";
            }else if($chose_type == "fertilize2"){
                $sql = "SELECT t9.".$chose_label1." AS label1 ,".$label2." ".",t9.`Type`,".$chose_cal."(t9.sumAll) AS data FROM (
                    SELECT t8.SF_dbID,t8.F_name,t8.`Type`,ROUND(SUM( IF(t8.`Unit`=1,t8.`Vol`,t8.`Vol`/1000) ),4)AS sumAll FROM (
                    SELECT `log-fertilisingdetail`.`Vol`,`log-fertilisingdetail`.`Unit`,`log-nutrient`.`Type`,t7.LID,t7.SF_dbID,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.FM_name FROM (
                    SELECT t6.LID,t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName`  AS FM_name  FROM (
                    SELECT t5.LID,t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.LID,t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                    SELECT `log-fertilising`.`ID` AS LID,t1.dbID AS F_dbID,t1.Name AS F_name,`log-fertilising`.`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,`log-fertilising`.`DIMdateID`,`log-fertilising`.`DIMownerID` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    GROUP BY `dim-farm`.`dbID`) AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN `log-fertilising` ON  `log-fertilising`.`DIMfarmID` =  `dim-farm`.`ID`
                    WHERE `log-fertilising`.`isDelete` =0 )AS t2
                    JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS t4 
                    ON t4.DIMSubfID =  t2.DIMsubFID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    JOIN `log-fertilisingdetail` ON `log-fertilisingdetail`.`fertilisingID` = t7.LID
                    JOIN `log-nutrient` ON `log-nutrient`.`ID` = `log-fertilisingdetail`.`logNID`
                    WHERE 1 ".$WHERE." )AS t8
                    GROUP BY t8.SF_dbID,t8.`Type`,t8.".$chose_label1." ".$groupBy1.")AS t9
                    GROUP BY t9.".$chose_label1." ".$groupBy2;
                    // print_r($sql);
                    $DATA = selectData($sql);
                    // print_r($DATA);
                    $DATA[0]['unit'] = "กิโลกรัม";
                    if($label2 == ""){
                        for($i=1;$i<=$DATA[0]['numrow'];$i++){
                            $DATA[$i]['label1'] = $DATA[$i]['label1']." ".$DATA[$i]['Type'];
                        }
                    }else{
                        for($i=1;$i<=$DATA[0]['numrow'];$i++){
                            $DATA[$i]['label2'] = $DATA[$i]['label2']." ".$DATA[$i]['Type'];
                        }
                    }
            }         


            if($chose_label1 == "Month"){
                for($i=1;$i<=$DATA[0]['numrow'];$i++){
                    $DATA[$i]['label1'] = numberToMonth($DATA[$i]['label1']);
                }
            }
            
            print_r(json_encode($DATA));

        break;
    }
}
function numberToMonth($number){
    $monthArr = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
    return $monthArr[$number-1];
}
function convertToHoursMins($time, $format = '%d:%d') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}    
?>
