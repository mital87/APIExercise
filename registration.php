<?php

include_once 'config.php';
$json = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $firstName = isset($_POST['firstname'])?mysqli_real_escape_string($conn,$_POST['firstname']) : "";
    $lastName = isset($_POST['lastname'])?mysqli_real_escape_string($conn,$_POST['lastname']) : "";
    $email = isset($_POST['email'])?mysqli_real_escape_string($conn,$_POST['email']) : "";
    $password = isset($_POST['password'])?mysqli_real_escape_string($conn,$_POST['password']) : "";
    $contact = isset($_POST['contact'])?mysqli_real_escape_string($conn,$_POST['contact']) : "";
    $address = isset($_POST['address'])?mysqli_real_escape_string($conn,$_POST['address']) : "";
    $imageName = isset($_FILES['image']['name'])?strtolower($_FILES['image']['name']): "";
    
    if(!empty($imageName)){
        if(!file_exists($imagepath)){
            mkdir($imagepath, 0777, true);
        } 
        
    }
    if(move_uploaded_file($_FILES['image']['tmp_name'], $imagepath.'/'.$imageName)){
    if(!empty($email) && !empty($password)){
        $stmt = $db->prepare("SELECT * FROM student WHERE email=?");
        $stmt->bindValue(1, $email, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($rows); exit;
      // $searchEmail = "select id from `student` where email = '".$email."'";
       //$searchQuery = mysqli_query($conn, $searchEmail);
       //$searchData = mysqli_fetch_array($searchQuery);
 
       if(!empty($rows)){
           $json = array('status'=>'0', 'msg'=>'Email is already used');
       }else{
          /* $insertSql = "insert into `student` (`id` , `first_name` , `last_name`, `email`,`password`,`contact`,`address`,`image`)"
                   . "values (NULL,'".$firstName."','".$lastName."','".$email."','".$password."','".$contact."','".$address."','".$imageName."')";
           
           $insertQuery = mysqli_query($conn, $insertSql); */
           
            $stmt1 = $db->prepare("insert into `student` (`id` , `first_name` , `last_name`, `email`,`password`,`contact`,`address`,`image`)"
                   . "values (NULL,?,?,?,?,?,?,?)");
           $insertQuery= $stmt1->execute(array($firstName, $lastName, $email, $password, $contact, $address , $imageName));  
           if($insertQuery){
               $json = array('status'=>'1', 'msg'=>'Registration done');
           }else{
               $json = array('status'=>'1', 'msg'=>'error while inserting data');
           }
       }
        
    }else{
        $json = array('status'=>'0', 'msg'=>'Email or Password cannot be blank');
    }
    }
}else{
    $json = array('status'=>'0', 'msg'=>'Unknown request method');
}

@mysqli_close($conn);
echo json_encode($json);

