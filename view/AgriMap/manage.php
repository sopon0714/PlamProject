<?php
require_once("../../dbConnect.php");
include_once("./../../query/query.php");
if (isset($_POST['request'])) {
    $request = $_POST['request'];

    switch ($request) {
        case 'search':

            $AVERAGE = 80;
            $year = $_POST['year'];
            $province = $_POST['province'];
            $distrinct = $_POST['distrinct'];
            $farmer = $_POST['farmer'];
            $harvest = $_POST['harvest'];
            $Nutr = $_POST['Nutr'];
            $NutrList = $_POST['NutrList'];
            $fertilizer = $_POST['fertilizer'];
            $minwater = $_POST['minwater'];
            $maxwater = $_POST['maxwater'];
            $minlack = $_POST['minlack'];
            $maxlack = $_POST['maxlack'];
            $mincutbranch = $_POST['mincutbranch'];
            $maxcutbranch = $_POST['maxcutbranch'];
            $pesttype = $_POST['pesttype'];

            $lack = $_POST['lack'];
            $water = $_POST['water'];
            $cutbranch = $_POST['cutbranch'];

            $sqlAllSubfarm = " SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT DISTINCT(t1.DIMSubfID) AS DIMsubFID FROM (
                    SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    WHERE `dim-farm`.`IsFarm` = 0
                    GROUP BY `dim-farm`.`dbID` )AS t1 
                )AS t2
                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";

            $sqlDataAllSubfarm = "SELECT  `db-subfarm`.`FSID`,`db-subfarm`.`FMID`,table_data.`ID`, table_data.`dbID`,table_data.`Latitude`,table_data.`Longitude`,
            table_data.`Name`,table_data.`Distrinct`,table_data.`Province`,table_data.EndT FROM (
            SELECT `log-farm`.`ID`, `dim-farm`.`dbID`,`log-farm`.`Latitude`,`log-farm`.`Longitude`,
                        `dim-farm`.`Name`,`dim-address`.`Distrinct`,`dim-address`.`Province`,
                        if(`log-farm`.`EndT` IS  NULL,0,`log-farm`.`EndT` )AS EndT
                        FROM `log-farm`
                        JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                        JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                        WHERE `log-farm`.`ID` IN 
                        (SELECT MAX(`log-farm`.`ID`)AS ID FROM `log-farm` 
                        JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                        GROUP BY `dim-farm`.`dbID` ) ) AS table_data
            JOIN `db-subfarm` ON `db-subfarm`.`FSID` =  table_data.`dbID`
            WHERE table_data.`dbID` IN (".$sqlAllSubfarm.")";
            
            $dataAllSubfarm = selectData($sqlDataAllSubfarm);

            if (
                $year != 0 && $province == 0 && $distrinct == 0 && $farmer == "" && $harvest == 0
                && $lack == 0 && $water == 0 && $cutbranch == 0 && $pesttype == 0
            ) {
                $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                    SELECT DISTINCT(t0.DIMsubFID)AS DIMsubFID FROM(
                    SELECT DISTINCT(Year2),`log-fertilising`.`DIMsubFID` FROM `log-fertilising` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
                    WHERE `log-fertilising`.`isDelete` = 0 
                    UNION
                    SELECT DISTINCT(Year2),`log-harvest`.`DIMsubFID` FROM `log-harvest` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
                    WHERE `log-harvest`.`isDelete` = 0 
                    UNION
                    SELECT DISTINCT(Year2),`log-watering`.`DIMsubFID` FROM `log-watering` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
                    WHERE `log-watering`.`isDelete` = 0 
                    UNION
                    SELECT DISTINCT(Year2),`log-raining`.`DIMsubFID` FROM `log-raining` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-raining`.`DIMdateID`
                    WHERE `log-raining`.`isDelete` = 0 
                    UNION
                    SELECT DISTINCT(Year2), `log-pestalarm`.`DIMsubFID` FROM `log-pestalarm` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
                    WHERE `log-pestalarm`.`isDelete` = 0 
                    UNION
                    SELECT DISTINCT(Year2), `log-activity`.`DIMsubFID` FROM `log-activity` 
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                    WHERE `log-activity`.`isDelete` = 0 
                    )AS t0 
                    WHERE 1  AND t0.Year2 = $year )AS t2
                    JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            } else {
                $sql = $sqlAllSubfarm;
            }
            // print_r($sql);

            $data_year = selectData($sql);
            // print_r($data_year);
            $data_year = toArr($data_year);
            // print_r($data_year);

            $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
            SELECT DISTINCT(t1.DIMSubfID) AS DIMsubFID FROM (
                SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                JOIN  `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                WHERE `dim-farm`.`IsFarm` = 0 ";
            if ($province != 0) $sql = $sql . " AND `dim-address`.dbprovID = $province";
            if ($distrinct != 0) $sql = $sql . " AND `dim-address`.dbDistID = $distrinct";

            $sql = $sql . " GROUP BY `dim-farm`.`ID` )AS t1
                            )AS t2 
                            JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            // print_r($sql);

            $data_pro_dist = selectData($sql);
            // print_r($data_pro_dist);
            $data_pro_dist = toArr($data_pro_dist);

            // print_r($sql);
            // print_r($data_pro_dist);

            $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT t1.DIMSubfID AS DIMsubFID FROM (
                SELECT MAX(`log-farm`.`ID`)AS lid, `log-farm`.`DIMSubfID` FROM `log-farm` 
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
                WHERE `dim-user`.`Type` = 'F' ";
            if (trim($farmer) != '') $sql = $sql . " AND `dim-user`.`FullName` LIKE '%" . $farmer . "%'";
            $sql = $sql . " GROUP BY `dim-farm`.`ID`)AS t1
                            )AS t2 
                            JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            if (trim($farmer) == '')  $sql = $sqlAllSubfarm;
            $data_farmer = selectData($sql);
            // print_r($data_farmer);
            $data_farmer = toArr($data_farmer);

            // print_r($sql);
            // print_r($data_farmer);

            if ($pesttype == 0) {
                $sql = $sqlAllSubfarm;
            } else {
                $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT DISTINCT(`log-pestalarm`.`DIMsubFID`) FROM `log-pestalarm`
                JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
                JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
                WHERE `log-pestalarm`.`isDelete` = 0 ";
                if ($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
                if ($pesttype != 0) $sql = $sql . " AND  `dim-pest`.`dbpestTID` = '$pesttype'";
                $sql = $sql . ")AS t2 
                                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            }
            $data_pesttype = selectData($sql);
            // print_r($data_pesttype);
            $data_pesttype = toArr($data_pesttype);
            // print_r($sql);
            // print_r($data_pesttype);

            if ($cutbranch == 0) {
                $data_minmax_cutbranch = selectData($sqlAllSubfarm);
                $data_minmax_cutbranch = toArr($data_minmax_cutbranch);
            } else if ($mincutbranch == 0 && $maxcutbranch == 0) {
                $data1 = selectData($sqlAllSubfarm);
                $data1 = toArr($data1);

                $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT `log-activity`.`DIMsubFID`FROM `log-activity`
                JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1 ";
                if ($year != 0) $sql = $sql . " AND `dim-time`.`Year2` = '$year'";
                $sql = $sql . " GROUP BY `dim-farm`.`dbID`
                                )AS t2 
                                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
                $data2 = selectData($sql);
                $data2 = toArr($data2);
                
                $data_minmax_cutbranch = array_diff($data1, $data2);
            } else {
                $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT t1.DIMsubFID FROM (
                    SELECT COUNT(`dim-farm`.`dbID`) AS times,`log-activity`.`DIMsubFID`
                    FROM `log-activity`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                    JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                    WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1 ";
                if ($year != 0) $sql = $sql . " AND `dim-time`.`Year2`='$year'";
                $sql = $sql . " GROUP BY `dim-farm`.`dbID`)AS t1
                                WHERE t1.times >= '$mincutbranch' AND t1.times <= '$maxcutbranch'
                                )AS t2 
                                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
                // print_r($sql);

                $data_minmax_cutbranch = selectData($sql);
                // print_r($data_minmax_cutbranch);
                $data_minmax_cutbranch = toArr($data_minmax_cutbranch);
                if ($mincutbranch == 0) {
                    $data1 = selectData($sqlAllSubfarm);
                    $data1 = toArr($data1);

                    $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                        SELECT `log-activity`.`DIMsubFID`FROM `log-activity`
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
                        JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
                        WHERE `log-activity`.`isDelete` = 0 AND `log-activity`.`DBactID` = 1 ";
                    if ($year != 0) $sql = $sql . " AND `dim-time`.`Year2` = '$year'";
                    $sql = $sql . " GROUP BY `dim-farm`.`dbID`
                                    )AS t2 
                                    JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
                    $data2 = selectData($sql);
                    $data2 = toArr($data2);

                    $datanocut = array_diff($data1, $data2);
                    $data_minmax_cutbranch = array_merge($data_minmax_cutbranch, $datanocut);
                }
            }
            // print_r($sql);
            // print_r($data_minmax_cutbranch);

            if ($harvest == 0) {
                $sql = $sqlAllSubfarm;
            } else {
                if ($year == 0) {
                    $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                        SELECT DISTINCT(t1.`DIMsubFID`) FROM (
                        SELECT `log-harvest`.`DIMsubFID`,AVG(`log-harvest`.`Weight`)AS avg_harvest FROM `log-harvest` 
                        JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubFID`
                        WHERE `log-harvest`.`isDelete` = 0 
                        GROUP BY `dim-farm`.`dbID`)AS t1
                        WHERE 1 ";
                } else {
                    $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                        SELECT DISTINCT(t1.`DIMsubFID`) FROM (
                        SELECT `log-harvest`.`DIMsubFID`,`dim-time`.`Year2`,AVG(`log-harvest`.`Weight`)AS avg_harvest FROM `log-harvest` 
                        JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
                        JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubFID`
                        WHERE `log-harvest`.`isDelete` = 0 
                        GROUP BY `dim-farm`.`dbID`,`dim-time`.`Year2`)AS t1
                        WHERE 1 AND t1.`Year2`='$year' ";
                }
                if ($harvest == 1) $sql = $sql . " AND t1.`avg_harvest` >= $AVERAGE";
                if ($harvest == 2) $sql = $sql . " AND t1.`avg_harvest` <= $AVERAGE";
                if ($harvest == 3) $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                    SELECT t1.DIMSubfID AS DIMsubFID FROM (
                    SELECT MAX(`log-farm`.`ID`),`log-farm`.`DIMSubfID` FROM `log-farm` 
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    WHERE `dim-farm`.`IsFarm` = 0
                    GROUP BY `dim-farm`.`dbID` )AS t1
                    WHERE t1.DIMsubFID
                    NOT IN 
                    (SELECT DISTINCT(`log-harvest`.`DIMsubFID`) FROM `log-harvest` 
                    WHERE `log-harvest`.`isDelete` = 0)";
                $sql = $sql . ")AS t2 
                                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            }

            // print_r($sql);
            $data_harvest = selectData($sql);
            // print_r($data_harvest);
            $data_harvest = toArr($data_harvest);

            // print_r($sql);
            // print_r($data_harvest);

            $data_lack_ok = array();
            $data_lack_ok2 = array();

            $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
            SELECT `dim-farm`.`dbID`,`dim-time`.`Date` ,`fact-drying`.`DIMstopDID`, 
            `dim-time`.`Year2` ,`fact-drying`.`Period` FROM `fact-drying`  
            JOIN `dim-time` ON  `dim-time`.`ID` = `fact-drying`.`DIMstartDID`  
            JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID` ";
            if ($year != 0) $sql = $sql . " WHERE `dim-time`.`Year2` = '$year'";
            $sql = $sql . " ORDER BY `dim-time`.`Date` DESC
                            )AS t2 
                            JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
            // print_r($sql);
            $data_lack = selectData($sql);

            if ($lack == 0) {
                $data_lack_ok = selectData($sqlAllSubfarm);
                $data_lack_ok = toArr($data_lack_ok);
            } else if ($minlack == 0 && $maxlack == 0) {
                $data_lack_ok = selectData($sqlAllSubfarm);
                $data_lack_ok = toArr($data_lack_ok);

                for ($i = 1; $i <= $data_lack[0]['numrow']; $i++) {
                    array_push($data_lack_ok2, $data_lack[$i]['dbID']);
                }
                $data_lack_ok2 = array_unique($data_lack_ok2);
                $data_lack_ok = array_diff($data_lack_ok, $data_lack_ok2);
            } else {
                $today = date("Y-m-d");
                for ($i = 1; $i <= $data_lack[0]['numrow']; $i++) {
                    if ($data_lack[$i]['DIMstopDID'] == NULL) {
                        $data_lack[$i]['Period'] = datediff($today, $data_lack[$i]['Date']);
                    }
                    if ($data_lack[$i]['Period'] >= $minlack && $data_lack[$i]['Period'] <= $maxlack) {
                        array_push($data_lack_ok, $data_lack[$i]['dbID']);
                    }
                    if ($minlack == 0) {
                        $data1 = selectData($sqlAllSubfarm);
                        $data1 = toArr($data1);

                        for ($i = 1; $i <= $data_lack[0]['numrow']; $i++) {
                            array_push($data_lack_ok2, $data_lack[$i]['dbID']);
                        }
                        $data_lack_ok2 = array_unique($data_lack_ok2);
                        $data2 = array_diff($data1, $data_lack_ok2);

                        $data_lack_ok = array_merge($data2, $data_lack_ok);
                    }
                }
                // print_r("data lack = ");
                // print_r($data_lack);
                $data_lack_ok = array_unique($data_lack_ok);
            }
            // print_r("data lack ok = ");
            // print_r($data_lack_ok);

            $data_water = array();
            if ($water == 0) {
                $data_water = selectData($sqlAllSubfarm);
                $data_water = toArr($data_water);
            } else if ($minwater == 0 && $maxwater == 0) {
                $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                SELECT DISTINCT(`fact-watering`.`DIMsubFID`) FROM `fact-watering`
                JOIN `dim-time` ON `dim-time`.`ID` = `fact-watering`.`DIMdateID` ";
                if ($year != 0) $sql = $sql . " WHERE `dim-time`.`Year2` = '$year'";
                $sql = $sql . " ORDER BY `fact-watering`.`DIMsubFID` ASC
                                )AS t2 
                                JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
                $db = selectData($sql);
                $db = toArr($db);
                $allsub = selectData($sqlAllSubfarm);
                $allsub = toArr($allsub);
                $data_water = array_diff($allsub, $db);
            } else {
                $sql = "SELECT `dim-time`.`Date`,`dim-time`.`Year2`,`dim-farm`.`dbID` FROM `fact-watering`
                JOIN `dim-time` ON `fact-watering`.`DIMdateID` = `dim-time`.`ID`  
                JOIN  `dim-farm` ON  `dim-farm`.`ID` =  `fact-watering`.`DIMsubFID`";
                if ($year != 0) $sql = $sql . " WHERE `dim-time`.`Year2` = '$year'";
                $sql = $sql . "  ORDER BY `dim-farm`.`dbID`,`dim-time`.`Date` ASC";
                $fact_watering = selectData($sql);
                $watering_subfarm = array();
                $day = 1;
                for ($i = 1; $i <= $fact_watering[0]['numrow']; $i++) {
                    if ($i != $fact_watering[0]['numrow']) {
                        if ($fact_watering[$i]['dbID'] == $fact_watering[$i + 1]['dbID']) {
                            $datetomorrow = date('Y-m-d', strtotime('+1 day', strtotime($fact_watering[$i]['Date'])));
                            if ($fact_watering[$i + 1]['Date'] == $datetomorrow) {
                                $day++;
                            } else {
                                $arr = array();
                                $arr['dbID'] = $fact_watering[$i]['dbID'];
                                $arr['day'] = $day;

                                $watering_subfarm[] = $arr;
                                $day = 1;
                            }
                        } else {
                            $arr = array();
                            $arr['dbID'] = $fact_watering[$i]['dbID'];
                            $arr['day'] = $day;

                            $watering_subfarm[] = $arr;
                            $day = 1;
                        }
                    } else {
                        $arr = array();
                        $arr['dbID'] = $fact_watering[$i]['dbID'];
                        $arr['day'] = $day;

                        $watering_subfarm[] = $arr;
                        $day = 1;
                    }
                }
                // print_r("data watering_subfarm = ");
                // print_r($watering_subfarm);

                for ($i = 0; $i < sizeof($watering_subfarm); $i++) {
                    if ($watering_subfarm[$i]['day'] >= $minwater && $watering_subfarm[$i]['day'] <= $maxwater) {
                        array_push($data_water, $watering_subfarm[$i]['dbID']);
                    }
                }

                if ($minwater == 0 && $maxwater != 0) {
                    $sql = "SELECT DISTINCT `dim-farm`.`dbID` FROM (
                    SELECT DISTINCT(`fact-watering`.`DIMsubFID`) FROM `fact-watering`
                    JOIN `dim-time` ON `dim-time`.`ID` = `fact-watering`.`DIMdateID` ";
                    if ($year != 0) $sql = $sql . " WHERE `dim-time`.`Year2` = '$year'";
                    $sql = $sql . " ORDER BY `fact-watering`.`DIMsubFID` ASC
                                    )AS t2 
                                    JOIN `dim-farm` ON `dim-farm`.`ID` = t2.DIMsubFID";
                    $db = selectData($sql);
                    $db = toArr($db);
                    $allsub = selectData($sqlAllSubfarm);
                    $allsub = toArr($allsub);
                    $data_not_water = array_diff($allsub, $db);

                    $data_water = array_merge($data_not_water, $data_water);
                }
                // print_r("data water = ");
                // print_r($data_water);
                $data_water = array_unique($data_water);
            }

            // print_r("data water = ");
            // print_r($data_water);

            $result = array_intersect($data_pro_dist, $data_farmer);
            // print_r("data data_pro_dist,data_farmer = ");
            // print_r($result);
            $result = array_intersect($result, $data_pesttype);
            // print_r($result);
            $result = array_intersect($result, $data_minmax_cutbranch);
            // print_r($result);
            $result = array_intersect($result, $data_year);
            // print_r($result);
            $result = array_intersect($result, $data_harvest);
            // print_r($result);
            $result = array_intersect($result, $data_lack_ok);
            // print_r($result);
            $result = array_intersect($result, $data_water);
            // print_r("data result,data_water = ");
            // print_r($result);
            //ใส่ปุ๋ย
            $resultNew = array();

            if ($Nutr == 1) {
                $size = count($result);

                for ($i = 0; $i < $size; $i++) {
                    $dbID = $result[$i];
                    if ($year != 0) {
                        $DATAVOL = getSumVolFertilising($dbID, $year, $NutrList);
                        if ($DATAVOL[0]['numrow'] != 0) {
                            $Vol = $DATAVOL[1]['sumVol'];
                        } else {
                            $Vol = 0;
                        }
                        $Voluse = getVolUseFertilising($dbID, $NutrList, $year);
                        if ($fertilizer == 0) {
                            if ($Voluse <= $Vol) {

                                $resultNew[] = $result[$i];
                            }
                        } else if ($fertilizer == 1) {
                            if ($Voluse > $Vol) {
                                $resultNew[] = $result[$i];
                            }
                        } else {
                            if ($Vol == 0) {
                                $resultNew[] = $result[$i];
                            }
                        }
                    } else {
                        $YEAR = getYear($dbID, false);
                        for ($j = 1; $j <= $YEAR[0]['numrow']; $j++) {
                            $DATAVOL = getSumVolFertilising($dbID, $YEAR[$j]['Year2'], $NutrList);
                            if ($DATAVOL[0]['numrow'] != 0) {
                                $Vol = $DATAVOL[1]['sumVol'];
                            } else {
                                $Vol = 0;
                            }
                            $Voluse = getVolUseFertilising($dbID, $NutrList, $YEAR[$j]['Year2']);
                            if ($fertilizer == 0) {
                                if ($Voluse > $Vol) {

                                    break;
                                }
                            } else if ($fertilizer == 1) {
                                if ($Voluse <= $Vol) {
                                    break;
                                }
                            } else {
                                if ($Vol != 0) {
                                    break;
                                }
                            }
                        }
                        if ($j > $YEAR[0]['numrow']) {
                            $resultNew[] = $result[$i];
                        }
                    }
                }
            } else {
                $resultNew = $result;
            }
            // $datamap = dataForMap($resultNew);
            // echo "resultNew = ";
            // print_r($resultNew);

            $datamap = dbIDToData($resultNew,$dataAllSubfarm);
            // print_r($datamap);
            print_r(json_encode($datamap));
            break;
    }
}
function dbIDToData($array,$dataAllSubfarm){
    // echo "array = ";
    // print_r($array);
    // echo "dataAllSubfarm = ";
    // print_r($dataAllSubfarm);

    $arr = array();
    $size = key(array_slice($array, -1, 1, true));

    for ($i = 0; $i <= $size; $i++) {
        for($j = 1; $j <= $dataAllSubfarm[0]['numrow']; $j++){
            if (isset($array[$i])) {
                $dbID = $array[$i];
                $dbID2 = $dataAllSubfarm[$j]['dbID'];    
                if($dbID == $dbID2){
                    // echo "dbID = ".$dbID." dbID2 = ".$dbID2;
                    $arr[] = $dataAllSubfarm[$j];
                    break;
                }
            }
        }
    }
    return $arr;
}
function dataForMap($array)
{
    $datamap = array();
    $size = key(array_slice($array, -1, 1, true));

    for ($i = 0; $i <= $size; $i++) {

        if (isset($array[$i])) {
            $dbID = $array[$i];
            $sql = "SELECT `log-farm`.`ID`, `dim-farm`.`dbID`,`log-farm`.`Latitude`,`log-farm`.`Longitude`,
            `dim-farm`.`Name`,`dim-address`.`Distrinct`,`dim-address`.`Province`,
            if(`log-farm`.`EndT`IS  NULL,0,`log-farm`.`EndT` )AS EndT
            FROM `log-farm`
            JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE `log-farm`.`ID` IN 
            (SELECT MAX(`log-farm`.`ID`)AS ID FROM `log-farm` 
            JOIN `dim-farm` ON   `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
            WHERE `dim-farm`.`dbID`= '$dbID' )";

            $data = selectData($sql);

            $sql = "SELECT `db-subfarm`.`FSID`,`db-subfarm`.`FMID` FROM `db-subfarm` 
            WHERE `db-subfarm`.`FSID` = '$dbID'";
            $data1 = selectData($sql);
            if ($data1[0]['numrow'] != 0) {
                $data[1]['FSID'] = $data1[1]['FSID'];
                $data[1]['FMID'] = $data1[1]['FMID'];
            }

            $datamap[] = $data[1];
        }
    }
    return $datamap;
}
function checkDateMINMAX($arr, $min, $max)
{
    // echo "min ".$min;
    // echo "/max ".$max;
    $k = 1;
    $array = array();
    $array[0]['numrow'] = 0;
    $size = key(array_slice($arr, -1, 1, true));
    for ($i = 1; $i <= $size; $i++) {
        if (isset($arr[$i])) {
            // echo "arr ".$arr[$i];

            if ($arr[$i] >= $min && $arr[$i] <= $max) {
                $array[$k]['DIMsubFID'] = $i;
                $array[0]['numrow']++;
                $k++;
            }
        }
    }
    return $array;
}
function countDay($arr)
{
    $array = array();
    $today = date("Y-m-d");
    $size = key(array_slice($arr, -1, 1, true));
    for ($i = 1; $i <= $size; $i++) {
        if (isset($arr[$i])) {
            $array[$i] = datediff($today, $arr[$i]);
        }
    }
    return $array;
}
function datediff($start, $end)
{
    // echo 'start = '.$start.'//';
    // echo 'end = '.$end.'//';

    $datediff = strtotime($start) - strtotime($end);
    return floor($datediff / (60 * 60 * 24));
}
function toArray($array1)
{
    $array = array();
    for ($i = 1; $i <= $array1[0]['numrow']; $i++) {
        $array[$array1[$i]['DIMsubFID']] = $array1[$i]['max_date'];
    }
    return $array;
}
function compare($array1, $array2)
{
    $array = array();
    $size = key(array_slice($array1, -1, 1, true));
    // echo 'size = '.$size.'//';
    for ($i = 1; $i <= $size; $i++) {
        if (isset($array1[$i]) && isset($array2[$i])) {
            // echo $array1[$i]."+++";
            // echo $array2[$i]."+++";

            $max = max($array1[$i], $array2[$i]);
            $array[$i] = $max;
        }

        if (isset($array1[$i]) && !isset($array2[$i])) {
            $array[$i] = $array1[$i];
        }
    }
    return $array;
}
function toDBID($array)
{
    $arr = array();
    // print_r("**************numrow = ".$array[0]['numrow']."****************<br>");

    for ($i = 1; $i <= $array[0]['numrow']; $i++) {
        $subFID = $array[$i]['DIMsubFID'];
        $sql = "SELECT  `dim-farm`.`dbID` FROM  `dim-farm` WHERE  `dim-farm`.`ID` = '$subFID'";
        // print_r("i = ".$i."<br>");
        // print_r($sql);
        $data = selectData($sql);
        // print_r("data = <br>");
        // print_r($data);
        $arr[] = $data[1]['dbID'];
    }
    // print_r("---------------end i = ".($i-1)."----------------<br>");
    // print_r($arr);
    return array_unique($arr);
}
function toArr($array){
    $arr = array();
    for ($i = 1; $i <= $array[0]['numrow']; $i++) {
        $arr[] = $array[$i]['dbID'];
    }
    return $arr;
}
