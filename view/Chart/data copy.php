<?php
set_time_limit(7200);
require_once("../../dbConnect.php");
include_once("./../../query/query.php");
$PROVINCE = getProvince();
$FARMER = getFarmerAll();
$YEAR = getYearAgriMap();

$set_chose_label1 = array("Province","Distrinct","SubDistrinct","F_name","SF_name","FM_name","Year2","Month","dd");
$set_chose_label2 = array("","Province","Distrinct","SubDistrinct","F_name","SF_name","FM_name","Year2","Month","dd");
$set_chose_type = array("water1","water2","fertilize1","fertilize2","cutbranch","pestcontrol","pest");
$set_chose_cal = array("MAX","MIN","AVG","SUM","STDDEV");
$set_chose_cond = array("max","min");
// $SET1 = $_POST['SET1'];
// $SET2 = $_POST['SET2'];
// $SET3 = $_POST['SET3'];

echo "<br>";

// for($m=1;$m<$PROVINCE[0]['numrow'];$m++){
//         print("Province = ");
//         for($n=1;$n<$PROVINCE[0]['numrow'];$n++){
                        
//     } 
// }
print_r($set_chose_label1);
for($i=0;$i<count($set_chose_label1);$i++){
    $chose_label1 = $set_chose_label1[$i];
    for($j=0;$j<count($set_chose_label2);$j++){
        $chose_label2 = $set_chose_label2[$j];
        for($k=0;$k<count($set_chose_type);$k++){
            $chose_type = $set_chose_type[$k];
            for($l=0;$l<count($set_chose_cal);$l++){
                $chose_cal = $set_chose_cal[$l];

                
                print("chose_label1 = ".$chose_label1);
                echo "<br>";
                print("chose_label2 = ".$chose_label2);
                echo "<br>";
                print("chose_type = ".$chose_type);
                echo "<br>";
                print("chose_cal = ".$chose_cal);
                echo "<br>";

                $dmy1 = "";
                $dmy2 = "";

                $label2 = "";
                $label2Add = "";
                $groupBy1 = "";
                $groupBy2 = "";

                $WHERE = "";

                if($chose_label2 != ""){
                    $label2 = ",t10.".$chose_label2." AS label2";
                    $groupBy1 = ",t9.".$chose_label2;
                    $groupBy2 = ",t10.".$chose_label2;
                    if($chose_type == "water2" || $chose_type == "fertilize2"){
                        if($chose_label1 != $chose_label2)
                            $label2Add = ",t9.".$chose_label2;
                    }
                }
                if($chose_label1 == "dd" || $chose_label2 == "dd"){
                    $dmy1 = ",t10.Month,t10.Year2";
                    $dmy2 = ",t9.Month,t9.Year2";
                }else if($chose_label1 == "Month" || $chose_label2 == "Month"){
                    $dmy1 = ",t10.Year2";
                    $dmy2 = ",t9.Year2";
                }
                $label2 = $label2." ".$dmy1;
                $label2Add = $label2Add." ".$dmy2;
                $groupBy1 = $groupBy1." ".$dmy2;
                $groupBy2 = $groupBy2." ".$dmy1;
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

                    $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2.", ROUND(".$chose_cal."(t10.times),2) AS data FROM (
                        SELECT t9.SF_dbID,COUNT(t9.SF_dbID) AS times,t9.F_dbID,t9.F_name,t9.SF_name ,t9.SubDistrinct,t9.Distrinct,t9.Province,t9.dd,t9.Month,t9.Year2,t9.FM_dbID,t9.FM_name  FROM (
                        SELECT t7.SF_dbID,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t8.FullName AS FM_name  FROM (
                        SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID` FROM (
                        SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                        SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                        SELECT t00.F_dbID,t00.F_name,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMdateID`,t00.`DIMownerID` FROM (
                        SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                        SELECT t0.max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM (
                        SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                        GROUP BY `dim-farm`.`dbID`)AS t0
                        JOIN `log-farm` ON `log-farm`.`ID` = t0.max_lfID
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                        JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`) AS t1        
                        JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                        JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`
                        WHERE ".$log.".`isDelete` = 0  ".$DBactID.")AS t00
                        JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                        JOIN (SELECT SF.max_lfID,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM (
                        SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                        GROUP BY `dim-farm`.`dbID`)AS SF
                        JOIN `log-farm` ON `log-farm`.`ID` = SF.max_lfID
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`)AS t4 
                        ON t4.dbID =  t2.dbID)AS t5
                        JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                        JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                        JOIN (SELECT `dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                        SELECT MAX(`log-farmer`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-farmer`
                        JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`
                        GROUP BY `dim-user`.`dbID`)AS FM
                        JOIN `log-farmer` ON `log-farmer`.`ID`= FM.max_lfID
                        JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`) AS t8
                        ON t7.`dbID` = t8.`dbID`)AS t9
                        WHERE 1 ".$WHERE." 
                        GROUP BY t9.SF_dbID,t9.".$chose_label1." ".$groupBy1.")AS t10
                        GROUP BY t10.".$chose_label1." ".$groupBy2."
                        ORDER BY `data` ASC";
                        // print_r($sql);
                        $DATA = selectData($sql);
                        // print_r($DATA);
                        $DATA[0]['unit'] = "ครั้ง";
                
                }else if($chose_type == "water1"){
                    $log = "`fact-watering`";
                    $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2.", ROUND(".$chose_cal."(t10.days),2) AS data FROM (
                    SELECT t9.SF_dbID,COUNT(DISTINCT(t9.Date)) AS days,t9.F_dbID,t9.F_name,t9.SF_name ,t9.SubDistrinct,t9.Distrinct,t9.Province,t9.dd,t9.Month,t9.Year2,t9.FM_dbID,t9.FM_name  FROM (
                    SELECT t7.SF_dbID,t7.Date,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t8.FullName AS FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.Date,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                    SELECT t00.F_dbID,t00.F_name,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMdateID`,t00.`DIMownerID` FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                    SELECT t0.max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    GROUP BY `dim-farm`.`dbID`)AS t0
                    JOIN `log-farm` ON `log-farm`.`ID` = t0.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`) AS t1        
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`)AS t00
                    JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                    JOIN (SELECT SF.max_lfID,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS SF
                    JOIN `log-farm` ON `log-farm`.`ID` = SF.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`)AS t4 
                    ON t4.dbID =  t2.dbID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT `dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                    SELECT MAX(`log-farmer`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-farmer`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`)AS FM
                    JOIN `log-farmer` ON `log-farmer`.`ID`= FM.max_lfID
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`) AS t8
                    ON t7.`dbID` = t8.`dbID`)AS t9
                    WHERE 1 ".$WHERE." 
                    GROUP BY t9.SF_dbID,t9.".$chose_label1." ".$groupBy1.")AS t10
                    GROUP BY t10.".$chose_label1." ".$groupBy2."
                    ORDER BY `data` ASC";
                    // print_r($sql);
                    $DATA = selectData($sql);
                    // print_r($DATA);
                    $DATA[0]['unit'] = "วัน";
                }else if($chose_type == "water2"){
                    $log = "`fact-drying`";
                    $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2.", ROUND(".$chose_cal."(t10.days),2) AS data FROM (
                    SELECT t9.".$chose_label1." ".$label2Add.",t9.SF_dbID,SUM(t9.days) AS days FROM (
                    SELECT t7.SF_dbID,t7.`Period` AS days,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.Date,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t8.FullName AS FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,t6.`Period` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.`Date`,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID`,t5.`Period` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMstopDID`,t2.`DIMownerID`,t2.`Period`  FROM (
                    SELECT t00.F_dbID,t00.F_name,t00.`DIMsubFID`,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMstopDID`,t00.`DIMownerID`,t00.`Period` FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,`fact-drying`.`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,`fact-drying`.`DIMstopDID`,`fact-drying`.`DIMownerID`,`fact-drying`.`Period` FROM (
                    SELECT t0.max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    GROUP BY `dim-farm`.`dbID`)AS t0
                    JOIN `log-farm` ON `log-farm`.`ID` = t0.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`) AS t1        
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN `fact-drying` ON  `fact-drying`.`DIMfarmID` =  `dim-farm`.`ID`)AS t00
                    JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                    JOIN (SELECT SF.max_lfID,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS SF
                    JOIN `log-farm` ON `log-farm`.`ID` = SF.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`)AS t4 
                    ON t4.dbID =  t2.dbID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMstopDID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT `dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                    SELECT MAX(`log-farmer`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-farmer`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`)AS FM
                    JOIN `log-farmer` ON `log-farmer`.`ID`= FM.max_lfID
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`) AS t8
                    ON t7.`dbID` = t8.`dbID`)AS t9
                    WHERE 1 ".$WHERE." 
                    GROUP BY t9.SF_dbID,t9.".$chose_label1." ".$groupBy1.") AS t10
                    GROUP BY t10.".$chose_label1." ".$groupBy2."
                    ORDER BY `data` ASC";
                    // print_r($sql);
                    $DATA = selectData($sql);
                    // print_r($DATA);
                    $DATA[0]['unit'] = "วัน";
                }else if($chose_type == "fertilize2"){
                    $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2." ".",t10.`Type`,".$chose_cal."(t10.sumAll) AS data FROM (
                    SELECT t9.SF_dbID,t9.".$chose_label1." ".$label2Add.",t9.`Type`,ROUND(SUM( IF(t9.`Unit`=1,t9.`Vol`,t9.`Vol`/1000) ),2)AS sumAll FROM (
                    SELECT t8.`Vol`,t8.`Unit`,t8.`Type`,t8.LID,t8.SF_dbID,t8.F_dbID,t8.F_name,t8.SF_name ,t8.SubDistrinct,t8.Distrinct,t8.Province,t8.dd,t8.Month,t8.Year2,t8.FM_dbID,t8.FM_name FROM (
                    SELECT `log-fertilisingdetail`.`Vol`,`log-fertilisingdetail`.`Unit`,`log-nutrient`.`Type`,t7.LID,t7.SF_dbID,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t8f.FullName AS FM_name FROM (
                    SELECT t6.LID,t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID` FROM (
                    SELECT t5.LID,t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.LID,t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                    SELECT t00.LID,t00.F_dbID,t00.F_name,`dim-farm`.`dbID`,t00.SubDistrinct,t00.Distrinct,t00.Province,t00.`DIMdateID`,t00.`DIMownerID` FROM (
                    SELECT `log-fertilising`.`ID` AS LID,t1.dbID AS F_dbID,t1.Name AS F_name,`log-fertilising`.`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,`log-fertilising`.`DIMdateID`,`log-fertilising`.`DIMownerID` FROM (
                    SELECT t0.max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    GROUP BY `dim-farm`.`dbID`)AS t0
                    JOIN `log-farm` ON `log-farm`.`ID` = t0.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`) AS t1        
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN `log-fertilising` ON  `log-fertilising`.`DIMfarmID` =  `dim-farm`.`ID`
                    WHERE `log-fertilising`.`isDelete` =0 )AS t00
                    JOIN `dim-farm` ON `dim-farm`.`ID` = t00.DIMsubFID)AS t2
                    JOIN (SELECT SF.max_lfID,`dim-farm`.`dbID`,`dim-farm`.`Name` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS SF
                    JOIN `log-farm` ON `log-farm`.`ID` = SF.max_lfID
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`)AS t4 
                    ON t4.dbID =  t2.dbID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT `dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                    SELECT MAX(`log-farmer`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-farmer`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`)AS FM
                    JOIN `log-farmer` ON `log-farmer`.`ID`= FM.max_lfID
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-farmer`.`DIMuserID`) AS t8f
                    ON t7.`dbID` = t8f.`dbID`
                    JOIN `log-fertilisingdetail` ON `log-fertilisingdetail`.`fertilisingID` = t7.LID
                    JOIN `log-nutrient` ON `log-nutrient`.`ID` = `log-fertilisingdetail`.`logNID`
                    )AS t8
                    )AS t9
                    WHERE 1 ".$WHERE." 
                    GROUP BY t9.SF_dbID,t9.`Type`,t9.".$chose_label1." ".$groupBy1.")AS t10
                    GROUP BY t10.Type,t10.".$chose_label1." ".$groupBy2."
                    ORDER BY `data` ASC";
                    // print_r($sql);
                    $DATA = selectData($sql);
                    // print_r($DATA);
                    $DATA[0]['unit'] = "กิโลกรัม";
                }      
                $label = "label1";
            
                if($chose_label1 == "dd" || $chose_label2 == "dd"){
                    if($chose_label2 == "dd"){
                        $label = "label2";
                    }   
                    for($i=1;$i<=$DATA[0]['numrow'];$i++){
                        $DATA[$i]['Month'] = numberToMonth($DATA[$i]['Month']);
                        $DATA[$i][$label] = $DATA[$i][$label]." ".$DATA[$i]['Month']." ".$DATA[$i]['Year2'];
                    }
                }
                if($chose_label1 == "Month" || $chose_label2 == "Month"){
                    if($chose_label2 == "Month"){
                        $label = "label2";
                    }  
                    for($i=1;$i<=$DATA[0]['numrow'];$i++){
                        $DATA[$i][$label] = numberToMonth($DATA[$i][$label])." ".$DATA[$i]['Year2'];

                    }
                }
                
                if($chose_type == "fertilize2"){
                    $label = "label1";
                    if(strpos($label2,"AS")){
                        $label = "label2";
                        // print("YES");
                    }else{
                        // print("NO");
                    }
                    // print("label = ".$label);

                    for($i=1;$i<=$DATA[0]['numrow'];$i++){
                        $DATA[$i][$label] = $DATA[$i][$label]." (".$DATA[$i]['Type'].")";
                    }
                }
                echo "<br>";
                echo "data = ";
                print_r($DATA);
                echo "<br>";

                // print_r(json_encode($DATA));

            }
            print("********************ENDTYPE**********************");
            echo "<br>";
        }
        print("---------------------------------ENDLABEL2-------------------------------------");
        echo "<br>";
    }
    echo "<br>";
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

//WRITE FILE
// $myfile = fopen("./filedata/newfile.txt", "w") or die("Unable to open file!");
// $txt = "John Doe\n";
// fwrite($myfile, $txt);
// $txt = "Jane Doe\n";
// fwrite($myfile, $txt);
// fclose($myfile);



?>