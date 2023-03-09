<?php

$server = "localhost";
$user_name = "abulkalam";
$pass_word = "Devil@015";
$databd = 'mydb';

$conn = new mysqli($server,$user_name,$pass_word,$databd);

if ($conn->connect_error){
    die("connection failed:".$conn->connect_error);
}
echo "connected";





$data = json_decode($_REQUEST['data'], true);
    $res = [
        'status' => 'failed' , 
        'msg' => 'Username Exits..Try Another Username'
    ] ;

    if (checkUserExits($conn , $data['username']) === false){
        add_User($conn , $data['username'] , $data['password'] , $data['email'] , $data['firstname'], $data['lastname']) ;    
        $res['status'] = 'success' ;
        $res['msg'] = 'Created Successfully' ;
;    }
    
    $response = json_encode($res) ; 
    var_dump($conn) ;
    echo $response ;
    


    function add_User($conn , $username , $password , $email , $firstname, $lastname) {
               $conn -> autocommit(FALSE);
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, username, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die ("Error: " . $conn->error);
         };
        $stmt->bind_param("sssss", $firstname, $lastname, $email , $username ,$password);
   
      $stmt->execute();
      if (!$conn -> commit()) {
        echo "Commit transaction failed";
        exit();
      }
    }
    function checkUserExits($conn , $username) {
        $stmt = $conn->prepare("select username from users where username= ?");
        $stmt->bind_param("s", $username);
        $stmt->execute() ;
        $result = $stmt->get_result() ; 
        $data = $result->fetch_assoc() ;
        if ($data === null ) {
            return false ;
        }else if (count($data) > 0){
            return true ;
        }
        return false ;
    }

?>