<?php
    require "connection.php";
    
    header('Content-Type: application/json; charset=utf-8');
    header("access-control-allow-method:GET");

    if($_SERVER['REQUEST_METHOD']==='GET'){
        
        $id = isset($_GET['id']) ? $_GET['id'] : "";
       
        $post = null;
        $posts = null;
    
        if($id != ""){
            
            $sql = mysqli_query($conn,"SELECT * from posts where id='$id' LIMIT 1;");
            
            if(mysqli_num_rows($sql)==0){
                $status=404;
                
                $message="post not found";
            
            }else{
                $data=mysqli_fetch_assoc($sql);
                
                $status=200;

                $post = [
                    "id"=> $data['id'],
                    "tilte"=>$data['title']
                ];
                
                $message="post found";

            }
            $data=['post'=>$post];
       
        }elseif($id == ""){
            $sql = mysqli_query($conn,"SELECT * from posts;");
            if(mysqli_num_rows($sql)>0){

                while($data=mysqli_fetch_assoc($sql)){
                
                    $posts[] = [
                        "id"=> $data['id'],
                        "user_id"=>$data['user_id'],
                        "title"=>$data['title'],
                        "body"=>$data['body'],
                        "created at"=>$data['created_at'],
                        "updated at"=>$data['updated_at']
                    ];
                    
                }
                $status=200;
                $message="posts found";

            }
            else{
                $status=404;
                $message="post not found";
            }

            $data=['posts'=>$posts];
           
        }

        $response = array(
            "status" => $status,
            "data" => $data,
            "message" => $message
        );
        
        http_response_code($status);
        echo json_encode($response);

    }elseif($_SERVER['REQUEST_METHOD']==='DELETE'){

        $id = isset($_GET['id']) ? $_GET['id'] : "";

        $sql=mysqli_query($conn,"DELETE from posts where id ='$id';");
        if(!$sql){
            die("not created". mysqli_error($conn));
            
            $response = array(
                "status"=>401,
                "message"=>"post not recorded"
            );
        }

        $response = array(
            "status"=>200,
            "message"=>"data deleted"
        );
        
        echo json_encode($response);

    }elseif($_SERVER['REQUEST_METHOD']==="POST"){

        $data = json_decode(file_get_contents('php://input'), true);
        // var_dump($data['first_name']);
        // die();
        $user_id=$data['user_id']==isset($_GET['id']);
       
       

        $sql=mysqli_query($conn,"SELECT user_id from posts where user_id='$user_id';");

        if(mysqli_num_rows($sql)==0){
            $user_id=$data['user_id'];
            $title=$data['title'];
            $body=$data['body'];
       

            $insert=mysqli_query($conn,"INSERT into posts (user_id,title,body) values ('$user_id','$title','$body');");
            $last_id=mysqli_insert_id($conn);

            if($insert){
                $status=201;
                $message="post added";
                // echo $message;
                $data['id']=$last_id;
            }
            else{
                $status=401;
                $message="post added";
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
        $query="select * from posts where id=$id";
        // var_dump($query);
        // die();
  
        $sql=mysqli_query($conn,$query);
   
        if(mysqli_num_rows($sql)==1){

            $delete=mysqli_query($conn,"delete from posts where id=$id");
            if($delete){
                $status=204;
                $message="post deleted";
            }
        }
        else{
            $status=404;
            $message="post not found";
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
        $sql=mysqli_query($conn,"select id from post where id=$id");
       
       if($sql){
            //create[p=p]
            $title=$data['title'];
            $body=$data['boy'];
            $id=$data['id'];

            $query=mysqli_query($conn,"update posts set title='$title',body='$body' where id=$id");
            if($query){
                $status=201;
                $message="post data updated";

            }else{
                $status=404;
                $message="post not found";
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