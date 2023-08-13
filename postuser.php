<?php
    require "connection.php";

    header('Content-Type: application/json; charset=utf-8');
    header("access-control-allow-method:POST");

    if($_SERVER['REQUEST_METHOD']=="POST"){
        if(isset($_post['add'])){
            $first_name=$_post['first_name'];
            $last_name=$_post['last_name'];
            $age=$_post['age'];
            $id=$_post['id'];

            $sql=mysqli_query($conn,"INSERT INTO users (first_name,last_name,age,)values('$first_name','$last_name','$age');");

            if(!$sql){
                die("failed to add data" .mysqli_error($conn));
            
                $response = array(
                    "status"=>200,
                    "message"=>"data recorded"
                );
            }else{
                $response = array(
                    "status"=>200,
                    "message"=>"data recorded"
                );
            }
            echo json_encode($response);
            
        }
    }
?>