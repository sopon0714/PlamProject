<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
session_start(); 
// connectDB();
$myConDB = connectDB();
// echo $_POST['did'];
//   echo "come";
//  echo $_POST['request'];
if(isset($_GET['confirm'])){
    $uid = $_GET['uid'];
    $sql = "UPDATE `db-farmer` SET IsBlock ='0' WHERE UFID=$uid";
    updateData($sql);
    header("location:FarmerListAdmin.php");

}
if(isset($_POST['request'])){
    $request = $_POST['request'];
    $sql ='';

    switch($request){
        case 'select' :
            $sql = "SELECT * FROM `db-farmer`";

            print_r(json_encode(select($sql)));
            break;
    
        case 'getDimU' :
            $uid = $_POST['uid'];
            $get_idDim = getDIMf($uid);
            echo $get_idDim;
            break;
        case 'insert' :
            
            $last_id = last_id();

            $title = trim($_POST['title']);
            $fname = trim($_POST['fname']);
            // echo $fname;
            $lname = trim($_POST['lname']);
            $idline = trim($_POST['idline']);
            $formalid = trim($_POST['formalid']);
            $address = trim($_POST['address']);
            $address = preg_replace('/[[:space:]]+/', ' ', trim($address));
           
            $id_province = trim($_POST['id_prov']);  
            $id_distrinct = trim($_POST['id_dist']);
            $id_subdistrinct = trim($_POST['id_subdist']);

            $province = trim($_POST['prov']);  
            $distrinct = trim($_POST['dist']);
            $subdistrinct = trim($_POST['subdist']);

            $st = trim($_POST['st']);

            $array = check_dim_user_du($title,$fname,$lname);
            $check_dim = $array[0];
            $id_data  =$array[1];
            $id_u = $array[2];
            echo "<br>check_dim = $check_dim <br>";
            echo "id data = $id_data <br>";
            echo "id u = $id_u <br>";
            echo "id subdist = $id_subdistrinct <br>";
            echo "id pro = $id_province <br>";
            echo "subdist = $subdistrinct <br>";
            
            if($check_dim == 1){
                $uid = $last_id+1;
            }else{
                $uid = $id_u;
            }
            echo "uid = $uid <br>";
            
            $sql = "INSERT INTO `db-farmer` (`UFID`,Title,FirstName,LastName,IdLine,FormalID,Icon,`Address`,AD3ID,IsBlock) 
             VALUES ('$uid','$title','$fname','$lname','$idline','$formalid','default.jpg','$address','$id_subdistrinct','0')";

            addinsertData($sql);

            if($st == 1){
                
            }else{
                $sql = "UPDATE `db-farmer` SET IsBlock = NULL WHERE UFID=$uid";
                updateData($sql);
            }
           
            $array = check_dim_user_du($title,$fname,$lname);
            $check_dim = $array[0];
            $id_data  =$array[1];
            $id_u = $array[2];
            $fullname = $array[3];

            if($check_dim == 1){
                $sql = "INSERT INTO `dim-user` (ID,`dbID`,`Type`,Title,FullName,Alias,FormalID) 
                    VALUES ('','$uid','F','$title','$fullname','$fname','$formalid')";

                $id_u=addinsertData($sql);
            }else{
                $id_u=$id_data;
                echo "ซ่ำ ";
                echo $id_u;
            }

            $fulladdress = $address." ต.".$subdistrinct." อ.".$distrinct." จ.".$province;

            $array = check_dim_addr_du($fulladdress);
            $check_dim_addr = $array[0];
            $id_dim_addr  =$array[1];

            if($check_dim_addr == 1){
                $sql = "INSERT INTO `dim-address` (ID,`FullAddress`,`dbsubDID`,dbDistID,dbprovID,SubDistrinct,Distrinct,Province) 
                VALUES ('','$fulladdress','$id_subdistrinct','$id_distrinct','$id_province','$subdistrinct','$distrinct','$province')";
    
                $id_addr = addinsertData($sql);
            }else{
                $id_addr = $id_dim_addr;
            }

            $time = time();
                $data_t =  getDIMDate();
            $id_t = $data_t[1]['ID'];
                $loglogin = $_SESSION[md5('LOG_LOGIN')];
            $loglogin_id = $loglogin[1]['ID'];
            
            $sql = "INSERT INTO `log-farmer` (ID,DIMuserID,LOGloginID,StartT,StartID,DIMaddrID) 
                        VALUES ('','$id_u','$loglogin_id','$time','$id_t','$id_addr')";
            $did = addinsertData($sql);
            
            $path = "icon/user/0";
            $Icon = "default.jpg";

            $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
                        VALUES ('','$loglogin_id','$time','$id_t','$id_u','5','$Icon','$path')";
                
            addinsertData($sql);
            
            header("location:FarmerListAdmin.php");
            
            break;
        
        case 'delete' ;
            $uid = $_POST['uid'];           
            // echo "uid = ".$uid."<br>";
            $get_idDim = getDIMf($uid);
            echo "dim id = ".$get_idDim."<br>";
            $time = time();
                $data_t =  getDIMDate();
            $id_t = $data_t[1]['ID'];
            //set end log-farmer
            $sql="UPDATE `log-farmer`
            SET EndT='$time', EndID='$id_t'
            WHERE DIMuserID='$get_idDim' ";
            updateData($sql);
            //set end log-icon
            $sql="UPDATE `log-icon`
            SET EndT='$time', EndID='$id_t'
            WHERE DIMiconID='$get_idDim' ";
            updateData($sql);

            $sql = "SELECT * FROM `db-farm` WHERE `UFID`= '$uid'";
            $FARM = selectData($sql);

            for($i = 1 ; $i <= $FARM[0]['numrow'] ; $i++){
                delFarm($FARM[1]['FMID']);
            }

            $sql = "DELETE FROM `db-farmer` WHERE `UFID`='".$uid."'";  
            delete($sql);

            break;

        case 'block' ;
            $val = $_POST['val'];
            $uid = $_POST['uid'];

            $get_idDim = getDIMf($uid);
            $sql=   "UPDATE `db-farmer` SET IsBlock=$val
                WHERE `UFID`='$uid' ";
            $re = updateData($sql);

            break;
        
        case 'update' :
            $set = 0;
            $uid = $_POST['e_uid'];
            $title = trim($_POST['e_title']);
            $fname = trim($_POST['e_fname']);
            $lname = trim($_POST['e_lname']);
            $idline = trim($_POST['e_idline']);
            // $formalid = trim($_POST['e_formalid']);
            $address = trim($_POST['e_address']);
            $address = preg_replace('/[[:space:]]+/', ' ', trim($address));

            echo $uid." -- <br>";
           
            $id_province = trim($_POST['e_id_prov']);  
            $id_distrinct = trim($_POST['e_id_dist']);
            $id_subdistrinct = trim($_POST['e_id_subdist']);

            $province = trim($_POST['e_prov']);  
            $distrinct = trim($_POST['e_dist']);
            $subdistrinct = trim($_POST['e_subdist']);

            // $st = trim($_POST['e_st']);
            
                $get_User = getFarmer($uid);

                $o_title = $get_User[1]['Title'];
                $o_fname = $get_User[1]['FirstName'];
                $o_lname = $get_User[1]['LastName'];
                $o_idline = $get_User[1]['IdLine'];
                $o_address= $get_User[1]['Address'];
                $o_id_subdistrinct = $get_User[1]['AD3ID'];

                $formalid=$get_User[1]['FormalID'];

                echo "o_fname = ".$o_fname."<br>";
                echo "fname = ".$fname."<br>";
               
                $get_idDim = getDIMf($uid);     //id-dim ตัวเก่า
                // $get_o_idDim = getDIMu($uid); 
                $time = time();
                $data_t =  getDIMDate();
                $id_t = $data_t[1]['ID'];
                $loglogin = $_SESSION[md5('LOG_LOGIN')];
                $loglogin_id = $loglogin[1]['ID'];

                $sql=   "UPDATE `db-farmer` 
                        SET Title='$title', FirstName='$fname', LastName='$lname', IdLine='$idline', `Address`='$address',AD3ID='$id_subdistrinct'
                        WHERE `UFID`='$uid' ";

                $re = updateData($sql);

                $fulladdress = $address." ต.".$subdistrinct." อ.".$distrinct." จ.".$province;

                $array = check_dim_addr_du($fulladdress);
                $check_dim_addr = $array[0];
                $id_dim_addr  =$array[1];
    
                if($check_dim_addr == 1){
                    $sql = "INSERT INTO `dim-address` (ID,`FullAddress`,`dbsubDID`,dbDistID,dbprovID,SubDistrinct,Distrinct,Province) 
                    VALUES ('','$fulladdress','$id_subdistrinct','$id_distrinct','$id_province','$subdistrinct','$distrinct','$province')";
        
                    $id_addr = addinsertData($sql);
                }else{
                    $id_addr = $id_dim_addr;
                }
                

                $array = check_dim_user_du($title,$fname,$lname);
                $check_dim = $array[0];
                $id_data  =$array[1];
                $id_u = $array[2];
                $fullname = $array[3];

               
                if($o_title == $title && $o_fname == $fname && $o_lname == $lname && $o_address == $address &&$o_id_subdistrinct == $id_subdistrinct){
                    $id_dim=$get_idDim;
                }else{
                    if($check_dim == 1 ){
                        // header("location:test.php");
                        echo   "<script>
                            console.log('ไม่ซ้ำ');
                            </script>";
//    --------------------------------------------------- update dim-user ---------------------------------------------------
                        $sql = "INSERT INTO `dim-user` (ID,`dbID`,`Type`,Title,FullName,Alias,FormalID) 
                        VALUES ('','$uid','F','$title','$fullname','$fname','$formalid')";

                        $id_dim = addinsertData($sql);
                                    
//    ------------------------------------------------ update and Add log-login ---------------------------------------------------                       
                        // $sh = $_SESSION[md5('user')];
                        // $row = $sh[0]['numrow'];
                        UpdateLogLogin();
                        NewLogLogin();

                        // $id_dim = $get_idDim ; 
                        $set = 1;


                    }else{
                                $id_dim=$id_data;
                                echo   "<script>
                                console.log('ซ้ำ');
                                </script>";
                                // header("location:FarmerListAdmin.php");
                                if($o_title == $title && $o_fname == $fname && $o_lname == $lname){
                                    $set=0;
                                }else{
                                    $set=1;
                                }  
                    }
                }
               

                if($set == 1){                       
                            $sql = "SELECT * FROM `log-icon` WHERE DIMiconID='$get_idDim' AND EndT IS NULL AND `Type`='5'" ;
                            $LogIcon = selectData($sql);
                        
                            $sql=   "UPDATE `log-icon` SET EndT='$time', EndID='$id_t'
                            WHERE `DIMiconID`='$get_idDim' AND EndT IS NULL AND `Type`='5'";
                
                            $re = updateData($sql);
                            $Icon = $LogIcon[1]['FileName'];
                            $path = $LogIcon[1]['Path'];
                            echo "<br> $get_idDim <br>icon = $Icon<br>path = $path";

                            $sql = "INSERT INTO `log-icon` (`ID`,LOGloginID,StartT,StartID,DIMiconID,`Type`,`FileName`,`Path`) 
                             VALUES ('','$loglogin_id','$time','$id_t','$id_dim','5','$Icon','$path')";
                
                            addinsertData($sql);
                }
//    --------------------------------------------------- get for log -------------------------------------------------------
               
                if($o_title == $title && $o_fname == $fname && $o_lname == $lname && $o_address == $address &&$o_id_subdistrinct == $id_subdistrinct){

                    echo " o_title= ".$o_title;
                    echo " title= ".$title;
                
                }else{
//    --------------------------------------------------- update log-user ---------------------------------------------------
                    echo " iddim = ".$get_idDim;
                    $sql="UPDATE `log-farmer` 
                            SET EndT='$time', EndID='$id_t'
                            WHERE DIMuserID='$get_idDim' AND EndT IS NULL ";
                $o_did = updateData($sql);

            //    echo "";

//    --------------------------------------------------- insert log-user ---------------------------------------------------

                $sql = "INSERT INTO `log-farmer` (ID,DIMuserID,LOGloginID,StartT,StartID,DIMaddrID) 
                VALUES ('','$id_dim','$loglogin_id','$time','$id_t','$id_addr')";
                 $did = addinsertData($sql);
                }
                
                $sql = "SELECT * FROM `log-farm` 
                WHERE `log-farm`.`DIMownerID` = '$get_idDim' AND EndT IS NULL ";
                $LOGFARM = selectData($sql);

                print_r($LOGFARM);
                $sql="UPDATE `log-farm` 
                    SET EndT='$time', EndID='$id_t'
                    WHERE DIMownerID='$get_idDim' AND EndT IS NULL ";
                $o_did = updateData($sql);

                for($i = 1 ;$i <= $LOGFARM[0]['numrow'] ;$i++){
                    $dim_farm_id = $LOGFARM[$i]['DIMfarmID'];
                    $dim_subfarm_id = $LOGFARM[$i]['DIMSubfID'];
                    $dim_addr_id = $LOGFARM[$i]['DIMaddrID'];
                    $iscoor = $LOGFARM[$i]['IsCoordinate'];
                    $la = $LOGFARM[$i]['Latitude'];
                    $long = $LOGFARM[$i]['Longitude'];
                    $num_sub = $LOGFARM[$i]['NumSubFarm'];
                    $num_tree = $LOGFARM[$i]['NumTree'];
                    $rai = $LOGFARM[$i]['AreaRai'];
                    $ngan = $LOGFARM[$i]['AreaNgan'];
                    $wa = $LOGFARM[$i]['AreaWa'];
                    $total = $LOGFARM[$i]['AreaTotal'];

                    $sql = "INSERT INTO `log-farm` (LOGloginID,StartT,StartID,DIMownerID,DIMfarmID,DIMSubfID,
                    DIMaddrID,IsCoordinate,Latitude,Longitude,NumSubFarm,NumTree,AreaRai,AreaNgan,AreaWa,AreaTotal) 
                    VALUES ('$loglogin_id','$time','$id_t','$id_dim','$dim_farm_id','$dim_subfarm_id'
                    ,'$dim_addr_id','$iscoor','$la','$long','$num_sub','$num_tree','$rai','$ngan','$wa','$total')";
                    $did = addinsertData($sql);

                    if($dim_subfarm_id == 0 || $dim_subfarm_id == NULL){
                        $dim_subfarm_id == NULL;
                        $sql = "UPDATE `log-farm` 
                        SET DIMSubfID = NULL
                        WHERE `log-farm`.ID ='$did'";
                        updateData($sql);

                    }
                }
                
                header("location:FarmerListAdmin.php");
               
            break;

            // }
    }

    
}

function check_dim_addr_du($fulladdress){
    $DATA =  select_dimAddr();  //get DIM_user for check ADD duplicate dim-user
    $array[0] = 1;
    $array[1] = 0;
        $i = 1;
        $check_dim = 1;
    
        for($i = 1;$i <= $DATA[0]['numrow'];$i++){
            if($DATA[$i]['FullAddress'] == $fulladdress){
                $array[0] = 0;
                $array[1] = $DATA[$i]['ID'] ;
                // $get_idDim_du = getDIMu($uid);   //id-dim ที่ซ้ำ
                break;
            }
        }
    return $array;
}
function check_dim_user_du($title,$fname,$lname){
            $DATA =  select_dimFarmer();  //get DIM_user for check ADD duplicate dim-user
            $array[0] = 1;
            $array[1] = 0;
            $array[2] = 0;
                if($title == 1){
                    $title_show = "นาย";
                }else if($title == 2){
                    $title_show = "นาง";
                }else{
                    $title_show = "นางสาว";
                }
    
                $fullname = $title_show." ".$fname." ".$lname;
                $i = 1;
                $check_dim = 1;
            
                $array[3] = $fullname;

                for($i = 1;$i <= $DATA[0]['numrow'];$i++){
                    if($DATA[$i]['Title'] == $title && $DATA[$i]['FullName'] == $fullname  && $DATA[$i]['Alias'] == $fname ){
                        $array[0] = 0;
                        $array[1] = $DATA[$i]['ID'] ;
                        $array[2] = $DATA[$i]['dbID'];
                        // $get_idDim_du = getDIMu($uid);   //id-dim ที่ซ้ำ
                        break;
                    }
                }
            return $array;
}
function delFarm($FID){
    $time = time();
    $DIMDATE = getDIMDate();
    // update log-farm
    $sql = "UPDATE `log-farm` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'  WHERE `log-farm`.`ID` IN 
    (SELECT `log-farm`.`ID` FROM `log-farm` INNER JOIN `dim-farm` ON `log-farm`.`DIMfarmID`=`dim-farm`.`ID` 
    WHERE`dim-farm`.`dbID`=$FID AND `dim-farm`.`IsFarm`=1 AND `log-farm`.`EndT`IS NULL) ";
    updateData($sql);
    //update log-icon subfarm
    $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'
      WHERE `log-icon`.`Type`= 3 AND `log-icon`.`EndT` IS NULL AND `log-icon`.`DIMiconID` IN 
      (SELECT `dim-farm`.`ID` FROM `dim-farm` INNER JOIN `db-subfarm` ON `db-subfarm`.`FSID`=`dim-farm`.`dbID`
       WHERE `dim-farm`.`IsFarm`=0 AND `db-subfarm`.`FMID` = $FID) ";
    updateData($sql);
    // update log-iconfarm
    $sql = "UPDATE `log-icon` SET `EndT` = '$time', `EndID` = '{$DIMDATE[1]['ID']}'
      WHERE `log-icon`.`Type`= 4 AND `log-icon`.`EndT` IS NULL AND `log-icon`.`DIMiconID` IN 
      (SELECT `dim-farm`.`ID` FROM `dim-farm` INNER JOIN `db-farm`ON `db-farm`.`FMID` = `dim-farm`.`dbID`
       WHERE `dim-farm`.`IsFarm`=1 AND `db-farm`.`FMID` =$FID) ";
    updateData($sql);

    $sql = "DELETE FROM `db-coorfarm` WHERE `db-coorfarm`.`FCID`IN 
    (SELECT `db-coorfarm`.`FCID` FROM `db-coorfarm` INNER JOIN `db-subfarm`
     ON  `db-coorfarm`.`FSID` = `db-subfarm`.`FSID` WHERE `db-subfarm`.`FMID` = '$FID')";
    delete($sql);
    $sql = "DELETE FROM `db-subfarm` WHERE `db-subfarm`.`FMID`='$FID'";
    delete($sql);

    echo $sql . "<br/>";
    $sql = "DELETE FROM`db-farm` WHERE `db-farm`.`FMID`='$FID'";
    delete($sql);
    echo $sql . "<br/>";
}
function last_id(){
    $sql = "SELECT MAX(`dbID`)as max FROM `dim-user` WHERE `Type`='F'";
    $max = selectData($sql);
    return $max[1]['max'];
}

function getDIMf($uid){
    $sql = "SELECT * FROM `db-farmer` WHERE `UFID`='$uid'";
    $DATA = selectData($sql);
    $title = $DATA[1]['Title'];
    // echo "title = ".$title."<br>";
    if($title == 1){
        $fullname = "นาย ";
    }else if($title == 2){
        $fullname = "นาง ";
    }else{
        $fullname = "นางสาว ";
    }
    $fname = $DATA[1]['FirstName'];
    $lname = $DATA[1]['LastName'];
    $fullname = $fullname.$fname." ".$lname;
    // echo "fullname = ".$fullname."<br>";
    $sql = "SELECT * FROM `dim-user` WHERE Title='$title' AND FullName='$fullname' AND Alias='$fname' AND `Type`='F'";
    $DATA = selectData($sql);
    if(isset($DATA[1]['ID'])){
        $IDdim_user = $DATA[1]['ID'];
    }else{
        $IDdim_user=0;
    }
    return $IDdim_user;

}
function get_DIMd($id_department){
    $sql = "SELECT * FROM `db-department` WHERE `DID`='$id_department'";
    $DATA = selectData($sql);
    $department = $DATA[1]['Department'];
    $alias = $DATA[1]['Alias'];
    $note = $DATA[1]['Note'];

    // echo $department;

    $sql = "SELECT * FROM `dim-department` WHERE `Department`='$department' AND Alias='$alias' AND Note='$note'";
    $DATA = selectData($sql);
    $dimd_id = $DATA[1]['ID'];

   return $dimd_id;
}
function getDIM($uid,$title,$fname,$lname){
    if($title == 1){
        $fullname = "นาย ";
    }else if($title == 2){
        $fullname = "นาง ";
    }else{
        $fullname = "นางสาว ";
    }
    $fullname = $fullname.$fname." ".$lname;
    $sql = "SELECT * FROM `dim-user` WHERE `dbID`='$uid' AND Title='$title' AND FullName='$fullname' AND Alias='$fname'";

   $DATA = selectData($sql);
   return $DATA;
}
function select_dimFarmer(){
    $sql = "SELECT * FROM `dim-user` WHERE `Type`='F'";

   $DATA = selectData($sql);
   return $DATA;

}
function select_dimAddr(){
    $sql = "SELECT * FROM `dim-address`";

   $DATA = selectData($sql);
   return $DATA;

}
function getFarmer($uid){
    $sql = "SELECT * FROM `db-farmer` WHERE `UFID`='$uid'";

   $DATA = selectData($sql);
   return $DATA;
}

?>