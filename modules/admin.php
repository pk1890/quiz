<?php  
    session_start();

    if(isset($_POST['login']) && isset($_POST['pass'])){
        $login = $_POST['login'];
        $pass = $_POST['pass'];

        if($login == 'admin' &&$pass == "ala123"){
            $_SESSION['admin'] = true;
        } else {
            header("Location: ../index.php?page=admin");
        }
    }else{
        header("Location: ../index.php");
    }