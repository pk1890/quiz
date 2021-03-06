<?php
    require_once "vendor/autoload.php";
    require_once "config/db.php";
    require_once "includes/devTools.php";
    require_once "config/twig.php";

    session_start();

    if(!isset($_GET['page'])){
        $_GET['page'] = '';
    }
    
    switch($_GET['page']){
        case 'admin':{
            if(isset($_SESSION['admin'])){
                $questions = ORM::for_table('questions')->find_array();
                echo $twig->render('admin.twig', [
                    'message' => isset($_SESSION['message'])? $_SESSION['message'] : '',
                    'questions' => $questions
                ]);
            }else{
                echo $twig->render('adminLogin.twig', [
                    'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''           
                ]);
            }
        }
        break;
        case 'userLogin':
        {
            echo $twig->render
            ('userLogin.twig', [
                'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''                
            ]);
        }break;
        case 'qadd':
        {
            if(isset($_SESSION['admin'])){
                echo $twig->render
                ('qadd.twig', [
                     'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''                
                ]);
            }else{
                 echo $twig->render('adminLogin.twig', [
                    'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''           
                ]);
            }
        }break;
        case 'register':
        {
            echo $twig->render
            ('register.twig', [
                'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''
            ]);
        }break;
        case 'quiz':{
            if(isset($_SESSION['userId'])){
                $userData = ORM::for_table('users')
                ->where('id', $_SESSION['userId'])
                ->findOne();
                if($userData->active == 0){
                    $_SESSION['message'] = "Quiz został już wykonany.";
                    header("Location: index.php?page=scoreboard");
                    exit;
                }
                $questions = ORM::for_table('questions')
                    ->findArray();

                echo $twig->render('quiz.twig', [
                    'data' => $questions
                ]);
            }
            else{
                echo $twig->render
                ('userLogin.twig', [
                    'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''                
                ]);
            }
        }
        break;
        case 'scoreboard':{
            $data = ORM::for_table('users')
                ->order_by_desc('score')
                ->findArray();

            echo $twig->render('scoreboard.twig', [
                'data' => $data,
                'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''
           ]);
        }
        break;
        default:
        if(isset($_SESSION['userId'])){
            echo $twig->render('index.twig', [
                'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''
            ]);
        }
        else{
            echo $twig->render('start.twig', [
                'message' => isset($_SESSION['message'])? $_SESSION['message'] : ''
            ]);
        }
    }

    unset($_SESSION['message']);