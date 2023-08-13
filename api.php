<?php
    $users=array(
        ['id'=>'0','name'=>'moses'],
        ['id'=>'1','name'=>'john'],
        ['id'=>'2','name'=>'jane'],
        ['id'=>'3','name'=>'doe'],
        ['id'=>'4','name'=>'bob'],
        ['id'=>'5','name'=>'alice']
    );

    header("content-type: application/json");

        echo json_encode($users) ;
           
?>