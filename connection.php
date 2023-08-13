<?php
    $conn= new mysqli("localhost","root","","test");

    if(!$conn){
        die(mysqli_connect_error($conn));
    }

?>