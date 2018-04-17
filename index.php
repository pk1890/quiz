<?php
    require_once "vendor/autoload.php";
    require_once "config/db.php";
    require_once "includes/devTools.php";
    require_once "config/twig.php";

    session_start();
    
    $user = ORM::for_table('users')
        ->where('login', 'mleko')
        ->findOne();
    
    // DevTools::p($user, true, false, true);

    echo $user->login;

    if(!isset($_GET['page'])){
        $_GET['page'] = '';
    }
    
    switch($_GET['page']){
        case 'admin':{
            if(isset($_SESSION['admin'])){
                echo $twig->render('admin.twig', [
                    'data' => "RAFFFFFFFFFał"
                ]);
            }else{
                echo $twig->render('adminLogin.twig', [
                    'data' => "RAFFFFFFFFFał"
                ]);
            }
        }
        break;
        case 'quiz':{

            $questions = ORM::for_table('questions')
                ->findArray();
            //  DevTools::p($questions, true, false, true);

            echo $twig->render('quiz.twig', [
                'data' => $questions
            ]);
        }
        break;
        case 'scoreboard':{
            $data = ORM::for_table('users')
                ->order_by_desc('score')
                ->findArray();

            echo $twig->render('scoreboard.twig', [
                'data' => $data
           ]);
        }
        break;
        default:
        echo $twig->render('index.twig', [
            'data' => "RAFFFFFFFFFał"
        ]);

    }

    