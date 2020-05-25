<?php
require_once("../../dbConnect.php");
connectDB();
session_start(); 
require_once("../../set-log-login.php");
include_once("./../../query/query.php");

if(isset($_POST['request'])){
    $request = $_POST['request'];
    $sql ='';

    switch($request){
        case 'selectSubfarm' :
            $fmid = $_POST['fmid'];
            print_r(json_encode(getSubfarm($fmid)));
            break;
        case 'selectPest' :
              $ptid = $_POST['ptid'];
              print_r(json_encode(getPestById($ptid)));
            break;
        case 'selectPestByPID';
              $pid = $_POST['pid'];
              print_r(json_encode(getPestByPID($pid)));
          break;
        case 'insert' :
            $department = trim($_POST['department']);
            $alias = trim($_POST['alias']);
            $note = trim($_POST['note']);
            $time = time();
            
                    // echo $time;
                    $department = preg_replace('/[[:space:]]+/', ' ', trim($department));
                    $alias = preg_replace('/[[:space:]]+/', ' ', trim($alias));
                    $note = preg_replace('/[[:space:]]+/', ' ', trim($note));
                    
                    $i = 1;

                    $last_id = last_id();

                    $array = check_de_du($department,$alias,$note);
                    $check_dim = $array[0];
                    $id_data = $array[1];
                    $id_de = $array[2];
                    if($check_dim == 1){
                        $id_d = $last_id+1;
                    }else{
                        $id_d = $id_de;
                    }
                    echo   "<br>check_dim = ".$check_dim;
                    echo   "<br>id_d = ".$id_d;

                            $sql = "INSERT INTO `db-department` (DID,Department,Alias,Note) 
                            VALUES ('$id_d','$department','$alias','$note')";
        
                            $did = addinsertData($sql);
                    echo   "<br>did add = ".$did;

                    $array = check_de_du($department,$alias,$note);
                    $check_dim = $array[0];
                    $id_data = $array[1];
                    $id_de = $array[2];

                    if($check_dim){
                        $sql = "INSERT INTO `dim-department` (ID,`dbID`,Department,Alias,Note) 
                        VALUES ('','$did','$department','$alias','$note')";  
    
                        $id_d = addinsertData($sql);
                        // echo $id_d;
                        
                    }else{
                        $id_d = $id_data;
                    }
                    $data_t =  getDIMDate();
                        $id_t = $data_t[1]['ID'];
                        // echo $id_t;

                        // echo $id_t[1]['ID'];
                        $loglogin = $_SESSION[md5('LOG_LOGIN')];
                        $loglogin_id = $loglogin[1]['ID'];
                        echo   "<script>
                            console.log($loglogin_id );
                            </script>";
                        $sql = "INSERT INTO `log-department` (ID,DIMdeptID,LOGloginID,StartT,StartID) 
                        VALUES ('','$id_d','$loglogin_id','$time','$id_t')";

                        $did = addinsertData($sql);
                    
                
                    header("location:DepartmentList.php");


 
            break;
        
        case 'delete' ;
            $id = $_POST['id'];
            echo 'id = '.$id;
            $sql="UPDATE `log-pestalarm` 
                        SET isDelete='1'
                        WHERE ID='$id' ";
            $o_did = updateData($sql);
            
            break;
            
        case 'update' :
            echo   "<script>
            console.log('sfdsf');
            </script>";
            $did = $_POST['e_did'];
            $department = trim($_POST['e_department']);
            $alias = trim($_POST['e_alias']);
            $note = trim($_POST['e_note']);

            $department = preg_replace('/[[:space:]]+/', ' ', trim($department));
            $alias = preg_replace('/[[:space:]]+/', ' ', trim($alias));
            $note = preg_replace('/[[:space:]]+/', ' ', trim($note));

            $o_department = trim($_POST['e_o_department']);
            $o_alias = trim($_POST['e_o_alias']);
            $o_note = trim($_POST['e_o_note']);
            // echo   "<script>
            // console.log('$o_department $o_alias $o_note');
            // </script>";
            $dim=getDIM($did,$o_department,$o_alias,$o_note);
            $dim_id = $dim[1]['ID'];

            $o_log=getLog($dim_id);
            $o_log_id = $o_log[1]['ID'];
            // echo   "<script>
            //             console.log('$o_log_id');
            //             </script>";

            echo "$did";
                $sql=   "UPDATE `db-department` 
                        SET Department='$department', Alias='$alias', Note='$note'
                        WHERE DID='$did' ";

                $re = updateData($sql);
                $DATA = select_dimDepartment();
                $i = 1;
                $check_dim = 1;
                for($i = 1;$i <= $DATA[0]['numrow'];$i++){
                    if($DATA[$i]['Department'] == $department && $DATA[$i]['Alias'] == $alias  && $DATA[$i]['Note'] == $note ){
                        $id_d=$DATA[$i]['ID'];
                        $check_dim = 0;
                        break;
                    }
                }
// ------------------------------------- if DIM don't duplicated ----------------------------
                if($check_dim){
                    $sql = "INSERT INTO `dim-department` (ID,`dbID`,Department,Alias,Note) 
                    VALUES ('','$did','$department','$alias','$note')";  

                    $id_d = addinsertData($sql);
                    
                   
                }
                $time = time();
                $data_t =  getDIMDate();
                $id_t = $data_t[1]['ID'];
                $loglogin = $_SESSION[md5('LOG_LOGIN')];
                $loglogin_id = $loglogin[1]['ID'];

                if($o_department == $department && $o_alias ==$alias && $o_note == $note){

                }else{
                    echo $o_log_id;
                    $sql="UPDATE `log-department` 
                    SET EndT='$time', EndID='$id_t'
                    WHERE ID='$o_log_id' AND EndT IS NULL";
    
                    $o_did = updateData($sql);
    
                    $sql = "INSERT INTO `log-department` (ID,DIMdeptID,LOGloginID,StartT,StartID) 
                    VALUES ('','$id_d','$loglogin_id','$time','$id_t')";
    
                    $did = addinsertData($sql);
                }
              


                header("location:DepartmentList.php");
            break;

           
    }
    
   
    
}

?>