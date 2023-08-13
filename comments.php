<?php
    require "connection.php";
    
    header('Content-Type: application/json; charset=utf-8');
    header("access-control-allow-method:GET");

    if($_SERVER['REQUEST_METHOD']==='GET'){
        
        $id = isset($_GET['id']) ? $_GET['id'] : "";
       
        $comment = null;
        $comments = null;
    
        if($id != ""){
            
            $sql = mysqli_query($conn,"SELECT * from comments where id='$id' LIMIT 1;");
            
            if(mysqli_num_rows($sql)==0){
                $status=404;
                
                $message="comment not found";
            
            }else{
                $data=mysqli_fetch_assoc($sql);
                
                $status=200;

                $comment = [
                    "id"=> $data['id'],
                    "tilte"=>$data['title']
                ];
                
                $message="post found";

            }
            $data=['post'=>$coment];
       
        }elseif($id == ""){
            $sql = mysqli_query($conn,"SELECT * from comments;");
            if(mysqli_num_rows($sql)>0){

                while($data=mysqli_fetch_assoc($sql)){
                
                    $comments[] = [
                        "id"=> $data['id'],
                        "post_id"=>$data['post_id'],
                        "body"=>$data['body'],
                        "created at"=>$data['created_at'],
                        "updated at"=>$data['updated_at']
                    ];
                    
                }
                $status=200;

                $message="comments found";

            }
            else{
                $status=404;

                $message="comments not found";
            }

            $data=['comments'=>$comments];
           
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
        $post_id=$data['post_id']==isset($_GET['id']);
       
       

        $sql=mysqli_query($conn,"SELECT post_id from comments where post_id='$post_id';");

        if(mysqli_num_rows($sql)==0){
            $post_id=$data['post_id'];
            $body=$data['body'];

            $insert=mysqli_query($conn,"INSERT into comments (post_id,body) values ('$post_id','$body');");
            $last_id=mysqli_insert_id($conn);

            if($insert){
                $status=201;
                $message="comment added";
                // echo $message;
                $data['id']=$last_id;
            }
            else{
                $status=401;
                $message="comment added";
                // echo $message;
            }
        }else{
            // die("error occured".mysqli_error($conn));
            $status=401;
            $data=NULL;
            $message="comment unavailable";
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
        $query="select * from comments where id=$id";
        // var_dump($query);
        // die();
  
        $sql=mysqli_query($conn,$query);
   
        if(mysqli_num_rows($sql)==1){

            $delete=mysqli_query($conn,"delete from comments where id=$id");
            if($delete){
                $status=204;
                $message="comment deleted";
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
        
        $id=NULL;
        $sql=mysqli_query($conn,"select id from comments where id=$id");
       
       if($sql){
            //create[p=p]
            $title=$data['title'];
            $body=$data['body'];
            // $id=$data['id'];

            $query=mysqli_query($conn,"update comments set body='$body' where post_id=$id");
            if($query){
                $status=201;
                $message="comment updated";

            }else{
                $status=404;
                $message="comment found";
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