<?php
  require_once "connection.php";

    header('Content-Type: application/json; charset=utf-8');
    header("access-control-allow-method:GET");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // echo "method is GET";
    $id=$_GET['id'];
    $sql = mysqli_query($conn,"  SELECT * FROM users where id='$id';");
    $users = [];
    
    while($data=mysqli_fetch_assoc($sql)){
        // $id = $_SERVER[$data['id']];
         array_push($users,$data);
    }
    $response = array(
        'status' => 200,
        'data' => [
            'users' => $users
        ]
    );
       
        echo json_encode($response);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // To get ID
        // $id = $_GET['id'];
        // var_dump($id);

        $sql=mysqli_query($conn,"INSERT into users(id,first_name,last_name,age) values('3','jane','doe','34');");
        if(!$sql){
            die("not created". mysqli_error($conn));
            
            $response = array(
                "status"=>201,
                "message"=>"post not recorded"
            );
        }else{
            $response = array(
                "status"=>200,
                "message"=>"data recorded"
            );
        }

        
        // To get Data
        $data = json_decode(file_get_contents('php://input'), true);
        // var_dump($data);
        echo json_encode($response);
        // Process stafss
        // ...
    
        // Return response as json :: json_encode
    
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // To get ID
        // $id = $_GET['id'];
        // var_dump($id);
        
        
        $sql=mysqli_query($conn,"UPDATE  users set first_name='BOB' where id ='3';");
        if(!$sql){
            die("not created". mysqli_error($conn));
            
            $response = array(
                "status"=>401,
                "message"=>"post not recorded"
            );
        }else{
            $response = array(
                "status"=>200,
                "message"=>"data updated"
            );
        }

        

        // To get Data
        // $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($response);
        var_dump($data);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        
        $sql=mysqli_query($conn,"DELETE from users where id ='3';");
        if(!$sql){
            $response = array(
                "status"=>401,
                "message"=>"not deleted"
            );
        }
        $response = array(
            "status"=>201,
            "message"=>"data deleted"
        );
        echo json_encode($response);
    }
?> 
