<?php
include_once 'config.php';
$json='';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email = isset($_POST['email'])?mysqli_real_escape_string($conn,$_POST['email']):"";
    $password = isset($_POST['password'])?mysqli_real_escape_string($conn,$_POST['password']):"";
    
    if(!empty($email) && !empty($password)){
        $searchSql = $db->prepare("select * from `student` where email=?");
        $searchSql->bindValue(1,$email,PDO::PARAM_STR);
        $searchSql->execute();
        //$querySql = mysqli_query($conn, $searchSql);
        $searchData = $searchSql->fetchAll(PDO::FETCH_ASSOC);
        //$searhData = mysqli_fetch_array($querySql);
        if(!empty($searchData)){
            $searchsql2 = $db->prepare("select * from `student` where email = ? and password = ?");
            $searchsql2->bindValue(1,$email,PDO::PARAM_STR);
            $searchsql2->bindValue(2,$password,PDO::PARAM_STR);
            $searchsql2->execute();
            //$querySql2 = mysqli_query($conn, $searchsql2);
            //$searchData2 = mysqli_fetch_array($querySql2);
            $searchData2 = $searchsql2->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($searchData2)){
                $json = array('status'=>'1', 'msg'=>'login succesfully');
            }else{
                $json = array('status'=>'0', 'msg'=>'Incorrect Email or Password');
            }
        }else{
            $json = array('status'=>'0', 'msg'=>'Email is not registered');
        }
    }else{
        $json = array('status'=>'0', 'msg'=>'Email or Password cannot be blank');
    }
}else{
    $json = array('status'=>'0', 'msg'=>'Unknown request method');
}

@mysqli_close($conn);

header('Content-type : application/json');
echo json_encode($json);

