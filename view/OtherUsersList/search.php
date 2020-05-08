<?php 

    $admin=0;
    $admin2=0;
    $research=0;
    $operator=0;
    $farmer=0;
    $block=0;
    $unblock=0;
    $department = 0;

    if(isset($_GET['did'])){
        $department = $_GET['did'];
    }
    if(isset($_POST['s_admin'])){
        $admin=1;
    }
    if(isset($_POST['s_admin2'])){
        $admin2=1;
    }
    if(isset($_POST['s_research'])){
        $research=1;
    }
    if(isset($_POST['s_operator'])){
        $operator=1;
    }
    if(isset($_POST['s_farmer'])){
        $farmer=1;
        
    }
    if(isset($_POST['s_department'])){
        $department = $_POST['s_department'];
    }
    
    if(isset($_POST['s_block'])){
        $block=1;
    }   
    if(isset($_POST['s_unblock'])){
        $unblock=1;
    } 
    $sql = "SELECT * FROM `db-user` 
            INNER JOIN `db-department` ON `db-user`.`DID` = `db-department`.`DID` 
            INNER JOIN `db-emailtype` on `db-emailtype`.`ETID` = `db-user`.`ETID`
            WHERE 1 ";
    if($department != 0) $sql = $sql." AND `db-department`.`DID` = '".$department."' ";
    if($admin   ==1) $sql = $sql."  AND IsAdmin = 1 ";
    if($admin2  ==1) $sql = $sql."  AND IsAdmin2 = 1 ";
    if($research==1) $sql = $sql."  AND IsResearch = 1 ";
    if($operator==1) $sql = $sql."  AND IsOperator = 1 ";
    if($farmer  ==1) $sql = $sql."  AND IsFarmer = 1 ";
    if($block==1&&$unblock==0) $sql = $sql."  AND IsBlock = 1 ";
    if($block==0&&$unblock==1) $sql = $sql."  AND IsBlock = 0 ";
    
    //echo $sql;
    $USER = selectData($sql);

    


?>