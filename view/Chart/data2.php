<?php
// $time_start = microtime(true);

ini_set('memory_limit', '-1');
set_time_limit(0);
require_once("../../dbConnect.php");
include_once("./../../query/query.php");

$myfile = fopen(getcwd()."/JsonData/test.txt", "w");
fwrite($myfile, json_encode($DATA,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
fclose($myfile);

$myConDB = connectDB();
$sql = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
$myConDB->exec($sql);

$PROVINCE = getProvince();
$FARMER = getFarmerAll();
$YEAR = getYearAgriMap();

//for name of file
$name_label1 = array("","Province","Distrinct");
$name_label2 = array("","Province","Distrinct");
$name_type = array("","fertilize3","fertilize4","cutbranch");
$name_cal = array("","MAX","SUM");


$set_chose_label1 = array("Province","Distrinct");
$set_chose_label2 = array("","Province","Distrinct");
$set_chose_type = array("fertilize3","fertilize4","cutbranch");
$set_chose_cal = array("MAX","SUM");
$set_chose_cond = array("max","min");

$YEAR2= getYearAgriMap();
$MONTH = array(1,2,3,4);
$MONTH_ARR = array("");
$DAY = array (1,2,3,4,5,6,7,8,9,10,11);
$DAY_ARR = array("");

$FARMER = getFarmerAll_Chart();
    
$SET1_TITLE = array("Province"=>array(),"Distrinct"=>array());

$PROVINCE = getProvince();
$PROVINCE_ALL = array();
$DISTINCT = getDistrinct();
$DISTINCT_ALL = array();
$SUBDISTINCT = getSubDistrinct();
$SUBDISTINCT_ALL = array();

$FARM = getFarmAll();
$FARM_ALL = array();
$SUBFARM = getSubfarmAll();
$SUBFARM_ALL = array();

$SET2_TITLE = array("FM_name"=>array());


$SET1_ALL = array();
$SET1_ARR = array();
$SET2_ALL = array();
$SET3_ALL = array();
$SET1 = array();
$SET2 = array();
$SET3 = array();

$SET1_ID = array();
$SET2_ID = array();
$SET3_ID = array();
// echo "<br><br><br>";
// print_r($SET1_TITLE);
// echo "<br>";
$PROVINCE_ALL["PROV"] = $PROVINCE;
$DISTINCT_ALL = eachID($DISTINCT,"AD1ID");
$SUBDISTINCT_ALL = eachID($SUBDISTINCT,"AD2ID");
$FARM_ALL = eachID($FARM,"dbsubDID");
$SUBFARM_ALL = eachID($SUBFARM,"f_dbID");

$SET1_TITLE["Province"] = $PROVINCE_ALL;    
$SET1_TITLE["Distrinct"] = $DISTINCT_ALL;
$SET1_TITLE["subDistrinct"] = $SUBDISTINCT_ALL;
$SET1_TITLE["F_name"] = $FARM_ALL;
$SET1_TITLE["SF_name"] = $SUBFARM_ALL;

// $FARMER_ALL["FARMER"] = $FARMER;
// $SET2_TITLE["FM_name"] = $FARMER;

foreach ($SET1_TITLE as $tt1 => $array1) {
    array_push($SET1_ARR,everyPossible1($tt1,$array1));
}
// print_r($SET1_ARR);
// echo "<br>";
foreach ($SET1_ARR as $set1_a) {
    foreach ($set1_a as $array1) {
        array_push($SET1_ALL,$array1);
    }
}
$SET2_ALL = everyPossible("FM_name",$FARMER);
// foreach ($SET2_TITLE as $tt2 => $array2) {
//     array_push($SET2_ALL,everyPossible($tt2,$array2));
// }   

// print_r($SET2_ALL);
// echo "<br>*****************************<br>";

$SET3[0] = "";
$SET3[1] = "";
$SET3[2] = "";
$SET3[3] = "";
$SET3[4] = "";
$SET3[5] = "";
$SET3[6] = "";
$SET3[7] = "";
$SET3[8] = "";
array_push($SET3_ALL,$SET3); 


$SET3[0]="Year2";
for($y1=1;$y1<count($YEAR2);$y1++){
    $SET3[1] = $YEAR2[$y1]["Year2"];
    $SET3[2] = $YEAR2[$y1]["Year2"];
    $SET3[3] = "";
    $SET3[4] = "";
    $SET3[5] = "";
    $SET3[6] = "";
    $SET3[7] = "";
    $SET3[8] = "";
    array_push($SET3_ALL,$SET3); 
}
for($y1=1;$y1<count($YEAR2);$y1++){
    $SET3[1] = $YEAR2[$y1]["Year2"];
    $SET3[2] = $YEAR2[$y1]["Year2"];
    $SET3[3] = "Month";
    $MONTH_FOR = $MONTH;
    for($m1=0;$m1<count($MONTH_FOR);$m1++){
        $SET3[4] = $MONTH_FOR[$m1];
        $SET3[5] = $MONTH_FOR[$m1];
        $SET3[6] = "";
        $SET3[7] = "";
        $SET3[8] = "";
    }
    array_push($SET3_ALL,$SET3); 
}

// $SET3[0]="Year2";
for($y1=1;$y1<count($YEAR2);$y1++){
    for($y2=$y1;$y2<count($YEAR2);$y2++){
        $SET3[1] = $YEAR2[$y1]["Year2"];
        $SET3[2] = $YEAR2[$y2]["Year2"];
        if($SET3[1] == $SET3[2]){
            $MONTH_FOR = $MONTH;
            $SET3[3] = "Month";
        }else{
            $MONTH_FOR = $MONTH_ARR;
            $SET3[3] = "";
        }
            for($m1=0;$m1<count($MONTH_FOR);$m1++){
                for($m2=$m1;$m2<count($MONTH_FOR);$m2++){
                        $SET3[4] = $MONTH_FOR[$m1];
                        $SET3[5] = $MONTH_FOR[$m2];
                        if($SET3[4] == $SET3[5] && $SET3[4] != ""){
                            $DAY_FOR = $DAY;
                            $SET3[6] = "dd";
                        }else{
                            $DAY_FOR = $DAY_ARR;
                            $SET3[6] = "";
                        }
                        for($d1=0;$d1<count($DAY_FOR);$d1++){
                            for($d2=$d1;$d2<count($DAY_FOR);$d2++){
                                $SET3[7] = $DAY_FOR[$d1];
                                $SET3[8] = $DAY_FOR[$d2];
                                // print_r($SET3);
                                // echo "<br>";
                                array_push($SET3_ALL,$SET3); 
                            }
                        }
                }
            }
    }
}

echo "<br>";




// for($m=1;$m<$PROVINCE[0]['numrow'];$m++){
//         print("Province = ");
//         for($n=1;$n<$PROVINCE[0]['numrow'];$n++){
                        
//     } 
// }

// print_r($set_chose_label1);
for($i=0;$i<count($set_chose_label1);$i++){
    $chose_label1 = $set_chose_label1[$i];
    for($j=0;$j<count($set_chose_label2);$j++){
        $chose_label2 = $set_chose_label2[$j];
        for($k=0;$k<count($set_chose_type);$k++){
            $chose_type = $set_chose_type[$k];
            for($l=0;$l<count($set_chose_cal);$l++){
                $chose_cal = $set_chose_cal[$l];

                foreach ($SET1_ALL as $SET1){
                    foreach ($SET2_ALL as $SET2){
                        foreach($SET3_ALL as $SET3){

                            echo "<br>SET1 = ";
                            print_r($SET1);
                            echo "<br>SET2 = ";
                            print_r($SET2);
                            echo "<br>SET3 =";
                            print_r($SET3);
                            echo "<br>";

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
                            if($SET1 != null){
                                $WHERE = " AND t9.".$SET1[0]. "= \"".$SET1[1]."\"";
                                for($i=2;$i<count($SET1);$i++){
                                    $WHERE = $WHERE." OR t9.".$SET1[0]." = \"".$SET1[$i]."\"";
                                }
                            }
                            if($SET2 != null){
                                $WHERE =$WHERE." AND t9.".$SET2[0]. "= \"".$SET2[1]."\"";
                                for($i=2;$i<count($SET2);$i++){
                                    $WHERE = $WHERE." OR t9.".$SET2[0]." = \"".$SET2[$i]."\"";
                                }
                            }
                            if($SET3 != null){
                                if($SET3[0] == "Year2"){
                                    $WHERE = $WHERE." AND t9.".$SET3[0]." BETWEEN ".$SET3[1]." AND ".$SET3[2];
                                }
                                if($SET3[3] == "Month"){
                                    $WHERE = $WHERE." AND t9.".$SET3[0]." = ".$SET3[1];
                                    $WHERE = $WHERE." AND t9.".$SET3[3]." BETWEEN ".$SET3[4]." AND ".$SET3[5];
                                }
                                if($SET3[6] == "dd"){
                                    $WHERE = $WHERE." AND t9.".$SET3[3]." = ".$SET3[4];
                                    $WHERE = $WHERE." AND t9.".$SET3[6]." BETWEEN ".$SET3[7]." AND ".$SET3[8];
                                }
                            }

                            if($chose_label2 != ""){
                                $label2 = ",t10.".$chose_label2." AS label2";
                                $groupBy1 = ",t9.".$chose_label2;
                                $groupBy2 = ",t10.".$chose_label2;
                                if($chose_type == "water2" || $chose_type == "fertilize2" || $chose_type == "fertilize3" || $chose_type == "fertilize4"){
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
                                $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2." ".",t10.`Type`,ROUND(".$chose_cal."(t10.sumAll),2) AS data FROM (
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
                            }else if($chose_type == "fertilize3" || $chose_type == "fertilize4"){
                                if($chose_type == "fertilize3"){
                                    $typeNutrient = "ธาตุอาหารหลัก";
                                }else{
                                    $typeNutrient = "ธาตุอาหารรอง";
                                }
                                $sql = "SELECT t10.".$chose_label1." AS label1 ".$label2." ".",t10.`Nutrient`,ROUND(".$chose_cal."(t10.sumAll),2) AS data FROM (
                                SELECT t9.SF_dbID,t9.".$chose_label1." ".$label2Add.",t9.`Nutrient`,ROUND(SUM( IF(t9.`Unit`=1,t9.`Vol`,t9.`Vol`/1000) ),2)AS sumAll FROM (
                                SELECT t8.`Vol`,t8.`Unit`,t8.`Type`,t8.Nutrient,t8.LID,t8.SF_dbID,t8.F_dbID,t8.F_name,t8.SF_name ,t8.SubDistrinct,t8.Distrinct,t8.Province,t8.dd,t8.Month,t8.Year2,t8.FM_dbID,t8.FM_name FROM (
                                SELECT `log-fertilisingdetail`.`Vol`,`log-fertilisingdetail`.`Unit`,`log-nutrient`.`Type`,`dim-nutrient`.`Name` AS Nutrient,t7.LID,t7.SF_dbID,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t8f.FullName AS FM_name FROM (
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
                                JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
                                WHERE `log-nutrient`.`Type` = '".$typeNutrient."'
                                )AS t8
                                )AS t9
                                WHERE 1 ".$WHERE." 
                                GROUP BY t9.SF_dbID,t9.`Nutrient`,t9.".$chose_label1." ".$groupBy1.")AS t10
                                GROUP BY t10.Nutrient,t10.".$chose_label1." ".$groupBy2."
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
                            
                            if($chose_type == "fertilize2" || $chose_type == "fertilize3" || $chose_type == "fertilize4"){
                                if($chose_type == "fertilize2"){
                                    $typeN = "Type";
                                }else{
                                    $typeN = "Nutrient";
                                }
                                $label = "label1";
                                if(strpos($label2,"AS")){
                                    $label = "label2";
                                    // print("YES");
                                }else{
                                    // print("NO");
                                }
                                // print("label = ".$label);

                                for($i=1;$i<=$DATA[0]['numrow'];$i++){
                                    $DATA[$i][$label] = $DATA[$i][$label]." (".$DATA[$i][$typeN].")";
                                }
                            }
                            // echo "<br>";
                            echo "data = ";
                            print_r($DATA);
                            echo "<br>";

                            // print_r(json_encode($DATA));
                            $lb1 = array_search($chose_label1, $name_label1);
                            $lb2 = array_search($chose_label2, $name_label2);
                            $type = array_search($chose_type, $name_type);
                            $cal = array_search($chose_cal, $name_cal);

                            $SET1_ID = dataToID($SET1);
                            echo "SET1_ID = ";
                            print_r($SET1_ID);
                            $SET2_ID = dataToID($SET2);
                            echo "<br>SET2_ID = ";
                            print_r($SET2_ID);
                            echo "<br>";
                            
                            $filename = "";
                            $filename = $lb1."-".$lb2."-".$type."-".$cal."-";

                            $filename = $filename.$SET1_ID[0];
                            for($i=1;$i<count($SET1_ID);$i++){
                                $filename = $filename.",".$SET1_ID[$i];
                            }

                            $filename = $filename."-".$SET2_ID[0];
                            for($i=1;$i<count($SET2_ID);$i++){
                                $filename = $filename.",".$SET2_ID[$i];
                            }
                            $filename = $filename."-".$SET3[0];
                            if($SET3[0] != ''){ 
                                for($i=1;$i<count($SET3);$i++){
                                    $filename = $filename.",".$SET3[$i];
                                }
                            }

                            echo $filename."<br>";

                            $myfile = fopen(getcwd()."/JsonData/".$filename.".json", "w");
                            fwrite($myfile, json_encode($DATA,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                            fclose($myfile);

                        }
                        echo "********************************<br>";
                    }
                    echo "////////////////////////////////////////////<br>";
                }
                
                

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
function everyPossible1($title,$ARR){
    $SET = array();
    $num_arr = 1;
    $num = 1;
    $SET[0] = "";
    foreach($ARR as $array){
        for($a=1;$a<count($array);$a++){
            for($b=$a;$b<count($array);$b++){
                $arr = array();
                $num_arr=1;
                for($c=$a;$c<=$b;$c++){
                    $arr[0] = $title;
                    $arr[$num_arr++] = $array[$c][$title];
                    // echo $array[$c]." ";
                }
                $SET[$num++] = $arr;
                // echo "<br>";
            }
        }
    }
    
    return $SET;
}
function everyPossible($title,$ARR){
    $SET = array();
    $num_arr = 1;
    $num = 1;
    $SET[0] = "";

    for($a=1;$a<count($ARR);$a++){
        for($b=$a;$b<count($ARR);$b++){
            $arr = array();
            $num_arr=1;
            for($c=$a;$c<=$b;$c++){
                $arr[0] = $title;
                $arr[$num_arr++] = $ARR[$c][$title];
                // echo $ARR[$c]." ";
            }
            $SET[$num++] = $arr;
            // echo "<br>";
        }
    }
    return $SET;
}
function eachID($ARR,$id){
    $ARR_ALL = array();
    for($i=1;$i<=$ARR[0]['numrow'];$i++){
        $ADID = $ARR[$i][$id]; 
        $ARR_ALL[$ADID] = array();
    }
    for($i=1;$i<=$ARR[0]['numrow'];$i++){
        $ADID = $ARR[$i][$id]; 
        array_push($ARR_ALL[$ADID],$ARR[$i]);
    }
    return $ARR_ALL;
}

function dataToID($ARR2){
    $array = array();
    $ARR1 = array();

    if($ARR2 != ''){
        if($ARR2[0] == 'Province'){
            $ARR1 = getProvince(); 
            $id = "AD1ID";
            $title = 1;
        }else if($ARR2[0] == 'Distrinct'){
            $ARR1 = getDistrinct(); 
            $id = "AD2ID";
            $title = 2;
        }else if($ARR2[0] == 'subDistrinct'){
            $ARR1 = getSubDistrinct();
            $id = "AD3ID";
            $title = 3;
        }else if($ARR2[0] == 'F_name'){
            $ARR1 = getFarmAll();
            $id = "dbID";
            $title = 4;
        }else if($ARR2[0] == 'SF_name'){
            $ARR1 = getSubfarmAll();
            $id = "sb_dbID";
            $title = 5;
        }else if($ARR2[0] == 'FM_name'){
            $ARR1 = getFarmerAll_Chart();
            $id = "dbID";
            $title = 1;
        }
        $array[0] = $title;
        for($i=1;$i<count($ARR2);$i++){
            for($j=1;$j<=$ARR1[0]['numrow'];$j++){
                if($ARR2[$i] == $ARR1[$j][$ARR2[0]]){
                    $array[$i] = $ARR1[$j][$id];
                    break;
                }
            }
        }
    }else{
        $array[0] = '';
    }
    
    return $array;

}

// $time_end = microtime(true);
// $time = $time_end - $time_start; #เวลาเริ่มต้น – เวลาท้ายสุด

// echo "เวลาที่ใช้ในการประมวลทั้งหมด $time วินาที\n";
?>