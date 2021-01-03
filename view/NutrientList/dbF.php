<?php
session_start();
require "../../dbConnect.php";
require "../../set-log-login.php";
$request = $_POST['request'];
// mkdir("path/to/my/dir", 0700);

switch ($request) {
    case 'select': //--------------------------case select ------------------------------
        $sql = "SELECT * FROM `db-nutrient` join `dim-nutrient` on (`dbID` = `db-nutrient`.`NID`) WHERE `db-nutrient`.`Name` = `dim-nutrient`.`Name`  AND `dim-nutrient`.`dbID`= `db-nutrient`.`NID`";
        print_r(json_encode(select($sql)));
        break;
    case 'p':
        $Con = $_POST['condition'];
        $numCon = 0;
        $Condition = array();
        foreach ($Con as $i => $val) {
            if ($val != '') {
                $numCon++;
                $Condition[] = $val;
            }
        }
        $NID = $_POST['id'];
        $dataCondition = selectData(" SELECT * FROM `db-fercondition` WHERE `NID` = $NID");
        // echo"---". $numCon."=".$dataCondition[0]['numrow'];
        break;
    case 'update': //--------------------------case update ------------------------------
        $Name = preg_replace('/[[:space:]]+/', ' ', trim($_POST['name']));
        $Type = $_POST['Type'];
        $Unit = $_POST['exampleRadios3'];
        $Usage = $_POST['exampleRadios1'];
        $NID = $_POST['id'];
        $DIMID = $_POST['dimid'];
        $isCondition = false;
        $isData = true;
        $t = time();
        $sql = '';
        $Start = '';
        $End =  '';
        // ------------------------------------ CONDITION DATA ---------------------------------
        if (isset($_POST['start']) && $_POST['exampleRadios2'] == 2) {
            $Start = $_POST['start'];
            $End = $_POST['end'];
        } else {
            $Start = '0101';
            $End = '3112';
        }
        $EQ1 = $_POST['a'];
        if ($Usage == 3) {
            $EQ1 = 0;
        }
        $EQ2 = $_POST['b'];
        // ------------------------------------ update db-nutrient` ---------------------------------
        $IDInsert = $DIMID;
        $sql = "SELECT * FROM `db-nutrient` WHERE `NID` = $NID";
        $dataSelect = select($sql);
        $dataAll = $dataSelect[1];
        if (
            $Start == $dataAll['Start'] && $End == $dataAll['End'] && $Name == $dataAll['Name'] && $Type == $dataAll['Type']
            && $EQ1 == $dataAll['EQ1'] && $EQ2 == $dataAll['EQ2'] && $Unit == $dataAll['Unit']  && $Usage == $dataAll['usage']
        ) {
            $isData = false;
        } else {
            $sql_update = "UPDATE `db-nutrient` 
            SET `Start` = '$Start', `End`= '$End', `Name` = '$Name',`Type` = '$Type', `Usage` = '$Usage',
            `EQ1` = $EQ1, `EQ2` = $EQ2 ,`Unit` = $Unit 
            WHERE `NID` = $NID;";
            updateData($sql_update);
            // // ------------------------------------ insert log ---------------------------------

            $StartDD = intval(str_split($dataAll['Start'], 2)[0]);
            $StartMM = intval(str_split($dataAll['Start'], 2)[1]);
            $EndDD = intval(str_split($dataAll['End'], 2)[0]);
            $EndMM = intval(str_split($dataAll['End'], 2)[1]);

            $data = [
                'NID' => $NID, 'Name' => $Name, 'Type' => $Type, 'Unit' => $Unit, 'Usage' => $Usage, 'EQ1' => $EQ1,
                'EQ2' => $EQ2, 'StartDD' => $StartDD, 'StartMM' => $StartMM, 'EndDD' => $EndDD,
                'EndMM' => $EndMM
            ];



            updateLog($DIMID);
            $IDInsert = insertLog($data);
        }

        // ------------------------------------ update db fer condition` ---------------------------------
        $Con = $_POST['condition'];
        $numCon = 0;
        $Condition = array();
        foreach ($Con as $i => $val) {
            if ($val != '') {

                $Condition[$numCon] = preg_replace('/[[:space:]]+/', ' ', trim($val));
                // echo"---". trim($val)."-----";
                $numCon++;
            }
        }
        $dataCondition = selectData(" SELECT * FROM `db-fercondition` WHERE `NID` = $NID");
        if (sizeof($Condition) > 0) {
            if ($dataCondition[0]['numrow'] > 0) {
                if ($dataCondition[0]['numrow'] == $numCon) {
                    foreach ($dataCondition as $i => $val) {
                        if ($i > 0) {
                            if ($dataCondition[$i]['Condition'] != $Condition[($i - 1)]) {
                                $isCondition = true;
                                // break;
                            }
                        }
                    }
                    if ($isCondition) {
                        $sql_del = "DELETE FROM `db-fercondition` WHERE `NID` = $NID;";
                        deletedata($sql_del);
                        $size = 1;
                        updateLogCon($DIMID);
                        foreach ($Condition as $i => $val) {
                            $val = trim($val);
                            $sql_insert = "INSERT INTO `db-fercondition` (`NID`,`Order`,`Condition`) VALUES ($NID,$size,'$val');";
                            addinsertData($sql_insert);
                            insertLogCon($Condition[$i], $IDInsert, $size);
                            $size++;
                        }
                    } else {
                        if ($DIMID != $IDInsert) {
                            $IDCon = $DIMID;
                            updateLogCon($DIMID);
                            $size = 1;
                            foreach ($Condition as $i => $val) {
                                insertLogCon($Condition[$i], $IDInsert, $size);
                                $size++;
                            }
                        }
                        // else break;
                    }
                } else {
                    $sql_del = "DELETE FROM `db-fercondition` WHERE `NID` = $NID;";
                    deletedata($sql_del);
                    $size = 1;
                    updateLogCon($DIMID);
                    foreach ($Condition as $i => $val) {
                        $sql_insert = "INSERT INTO `db-fercondition` (`NID`,`Order`,`Condition`) VALUES ($NID,$size,'$val');";
                        addinsertData($sql_insert);
                        insertLogCon($Condition[$i], $IDInsert, $size);
                        $size++;
                    }
                }
            } else {
                $size = 1;
                foreach ($Condition as $i => $val) {
                    $sql_insert = "INSERT INTO `db-fercondition` (`NID`,`Order`,`Condition`) VALUES ($NID,$size,'$val');";
                    addinsertData($sql_insert);
                    insertLogCon($Condition[$i], $IDInsert, $size);
                    // insertLogCon($Condition[$i],$DIMID);
                    $size++;
                }
            }
        } else {
            $sql_del = "DELETE FROM `db-fercondition` WHERE `NID` = $NID;";
            deletedata($sql_del);
            updateLogCon($DIMID);
        }
        break;
    case 'insert':
        $Name =  preg_replace('/[[:space:]]+/', ' ', trim($_POST['name_insert']));
        $Type = $_POST['Type'];
        $sql = "INSERT INTO `db-nutrient` (`Name`,`Type`) VALUES ('$Name','$Type');";
        $id = addinsertData($sql);
        // ------------------------------------ insert log ---------------------------------
        $sql = "SELECT * FROM `db-nutrient` WHERE `NID` = $id";
        $dataSelect = select($sql);
        $dataAll = $dataSelect[1];
        $StartDD = intval(str_split($dataAll['Start'], 2)[0]);
        $StartMM = intval(str_split($dataAll['Start'], 2)[1]);
        $EndDD = intval(str_split($dataAll['End'], 2)[0]);
        $EndMM = intval(str_split($dataAll['End'], 2)[1]);

        $data = [
            'NID' => $id, 'Name' => $Name, 'Type' => $Type, 'Unit' => $dataAll['Unit'], 'Usage' => $dataAll['Usage'], 'EQ1' => $dataAll['EQ1'],
            'EQ2' => $dataAll['EQ2'], 'StartDD' => $StartDD, 'StartMM' => $StartMM, 'EndDD' => $EndDD,
            'EndMM' => $EndMM
        ];
        $datatest = json_encode($data);
        $DIMID = insertLog($data);
        break;
    case 'selectCondition':
        $id = $_POST['id'];
        $sql = "SELECT * FROM `db-fercondition` WHERE `NID` = $id";

        print_r(json_encode(select($sql)));
        break;
    case 'delete': //--------------------------case delete ------------------------------
        $NID = $_POST['id'];
        $DIMID = $_POST['dimid'];
        updateLog($DIMID);
        updateLogCon($DIMID);
        $sql = "DELETE FROM `db-fercondition` WHERE `NID` = $NID";
        delete($sql);
        $sql = "DELETE FROM `db-nutrient` WHERE `NID` = $NID";
        $result = delete($sql);
        print_r($_POST);
        break;
}
function insertLog($data)
{
    $NID = $data['NID'];
    $Name = $data['Name'];
    $Type = $data['Type'];
    $StartT = time();
    $StartDD = $data['StartDD'];
    $StartMM = $data['StartMM'];
    $EndDD = $data['EndDD'];
    $EndMM = $data['EndMM'];
    $Usage = $data['Usage'];
    $EQ1 = $data['EQ1'];
    $EQ2 = $data['EQ2'];
    $Unit = $data['Unit'];
    $DIMnutrID = '';
    $LOGloginID = $_SESSION[md5('LOG_LOGIN')][1]['ID'];
    $StartID = getDIMDate()[1]['ID'];

    $sql = "SELECT * FROM `dim-nutrient` WHERE `dbID` = $NID  AND `Name` = '$Name' ";
    $checkDIM = selectData($sql);
    if ($checkDIM[0]['numrow'] > 0) {
        $DIMnutrID = $checkDIM[1]['ID'];
    } else {

        $sqlDIM_Nutrient = "INSERT INTO `dim-nutrient` (`dbID`,`Name`) VALUE ($NID,'$Name')";
        $DIMnutrID = addinsertData($sqlDIM_Nutrient);
    }

    $sqlLog_Ntrient = "INSERT INTO `log-nutrient` (`LOGloginID`,`StartT`,`StartID`,`DIMnutrID`,`Type`,`StartDD`,`StartMM`,`EndDD`
    ,`EndMM`,`Usage`,`Unit`,`EQ1`,`EQ2`) 
    VALUES ($LOGloginID,$StartT,$StartID,$DIMnutrID,'$Type',$StartDD,$StartMM,$EndDD,$EndMM,$Usage,$Unit,$EQ1,$EQ2);";
    addinsertData($sqlLog_Ntrient);
    return $DIMnutrID;
}



function insertLogCon($item, $id, $Order)
{
    $StartID = getDIMDate()[1]['ID'];
    // $StartID = 1;
    $LOGloginID = $_SESSION[md5('LOG_LOGIN')][1]['ID'];
    $t = time();
    $sqlLog_FerCondition = "INSERT INTO `log-fercondition` (`LOGloginID`,`StartT`,`StartID`,`DIMnutrID`,`Order`,`Condition`)
    VALUES ($LOGloginID,$t,$StartID,$id,$Order,'$item');";
    // addinsertData($sqlLog_FerCondition);
    addinsertData($sqlLog_FerCondition);
    // echo"---". "insertLogCon";
}

function updateLog($ID)
{
    $EndID = getDIMDate()[1]['ID'];
    $t = time();
    $sql = "UPDATE `log-nutrient` 
    SET `EndT`= $t ,`EndID` = $EndID
    WHERE `DIMnutrID` = $ID AND `EndID` IS NULL;";
    updateData($sql);
    // echo"---". "updateLog";
}
function updateLogCon($ID)
{
    $EndID = getDIMDate()[1]['ID'];
    $t = time();
    $sql = "UPDATE `log-fercondition` 
    SET `EndT`= $t ,`EndID` = $EndID
    WHERE `DIMnutrID` = $ID AND `EndID` IS NULL;";
    updateData($sql);
    // echo"---". "updateLogCon";

}
