<?php
    require_once "../includes/devTools.php";
    require_once "../vendor/autoload.php";
    require_once "../config/db.php";

    session_start();

   //DevTools::p($_POST, true, false, false);
    $data = ORM::for_table("questions")
        ->findArray();
    //DevTools::p($data, true, false, false);

    $value = 10;

    $sum = 0;

    foreach($data as $key => $item){
        if(isset($_POST['answer'][$item['id']]) && $_POST['answer'][$item['id']] == $item["correct"]){
            $sum += 10;
        }
    }

    $user = ORM::for_table("users")
        ->where('id', $_SESSION['userId'])->findOne();

    $user->score = $sum;
    $user->save();

    header("Location: ../index.php?page=scoreboard");