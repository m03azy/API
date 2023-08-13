<?php
    require "connection.php";
    
    header('Content-Type: application/json; charset=utf-8');
    header("access-control-allow-method:GET");

    if($_SERVER['REQUEST_METHOD']==='GET'){
        
        $id = isset($_GET['id']) ? $_GET['id'] : "";
       
        $user = null;
        $users = null;
    
        if($id != ""){
            
            $sql = mysqli_query($conn,"SELECT * from users where id='$id' LIMIT 1;");
            
            if(mysqli_num_rows($sql)==0){
                $status=404;
                
                $message="user not found";
            
            }else{
                $data=mysqli_fetch_assoc($sql);
                
                $status=200;

                $user = [
                    "id"=> $data['id'],
                    "age"=>$data['age']
                ];
                
                $message="user found";

            }
            $data=['user'=>$user];
       
        }elseif($id == ""){
            $sql = mysqli_query($conn,"SELECT * from users;");
            if(mysqli_num_rows($sql)>0){

                while($data=mysqli_fetch_assoc($sql)){
                
                    $users[] = [
                        "id"=> $data['id'],
                        "first_name"=>$data['first_name'],
                        "last_name"=>$data['last_name'],
                        "age"=>$data['age'],
                        "username"=>$data['username']
                    ];
                    
                }
                $status=200;

                $message="users found";

            }
            else{
                $status=404;

                $message="users not found";
            }

            $data=['users'=>$users];
           
        }

        $response = array(
            "status" => $status,
            "data" => $data,
            "message" => $message
        );
        http_response_code($status);
        echo json_encode($response);
    }elseif($_SERVER['REQUEST_METHOD']==="POST"){

        $data = json_decode(file_get_contents('php://input'), true);
        // var_dump($data['first_name']);
        // die();
        $first_name=$data['first_name'];
        $last_name=$data['last_name'];
        $age=$data['age'];
        $username=$data['username']; $username=$data['username'];

        $sql=mysqli_query($conn,"SELECT username from users where username='$username';");

        if(mysqli_num_rows($sql)==0){
            $insert=mysqli_query($conn,"INSERT into users (first_name,last_name,age,username) values ('$first_name','$last_name','$age','$username')");
            $last_id=mysqli_insert_id($conn);

            if($insert){
                $status=201;
                $message="user added";
                // echo $message;
                $data['id']=$last_id;
            }
            else{
                $status=401;
                $message="not added";
                // echo $message;
            }
        }else{
            // die("error occured".mysqli_error($conn));
            $status=401;
            $data=NULL;
            $message="username unavailable";
            // echo $message;
        }
        

        $response = array(
            "status" => $status,
            "data" => $data,
            "message" => $message
        );

        http_response_code($status);
        echo json_encode($response);
            
    }
    elseif($_SERVER['REQUEST_METHOD']=="DELETE"){
      
        $id=$_GET['id'];
        $query="select * from users where id=$id";
        // var_dump($query);
        // die();
  
        $sql=mysqli_query($conn,$query);
   
        if(mysqli_num_rows($sql)==1){

            $delete=mysqli_query($conn,"delete from users where id=$id");
            if(!$delete){
                $status=401;
                $message="user not deleted";
            }else{
                $status=204;
                $message="user deleted";
            }
        }
        else{
            $status=404;
            $message="user not found";
        }
        $response=array(
            "status"=>$status,
            "message"=>$message
        );
        
        // var_dump($response);
        // die();

        http_response_code($status);
        echo json_encode($response);

    }elseif($_SERVER['REQUEST_METHOD']=="PUT"){
        $data = json_decode(file_get_contents('php://input'), true);
        $id=$data['id'];
        $sql=mysqli_query($conn,"select id from users where id=$id");
       
       if($sql){
            //create[p=p]
            $first_name=$data['first_name'];
            $last_name=$data['last_name'];
            $age=$data['age'];

            $query=mysqli_query($conn,"update users set first_name='$first_name',last_name='$last_name',age='$age' where id=$id");
            if($query){
                $status=201;
                $message="user data updated";

            }else{
                $status=404;
                $message="user not found";
            }
       }else{
            $status=500;
            $message="error occured";
       }
       $response=array(
            "status"=>$status,
            "message"=>$message
        );

       
        http_response_code($status);
        echo json_encode($response);
    }

?>