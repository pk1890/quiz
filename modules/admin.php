<?php  

    require_once "../vendor/autoload.php";
    require_once "../config/db.php";
    require_once "../includes/devTools.php";

    session_start();
    
    if(isset($_GET['action'])){
        switch($_GET['action']){
            case 'login':{
               if(isset($_POST['login']) && isset($_POST['pass'])){
                    $login = $_POST['login'];
                    $pass = $_POST['pass'];
                    
                    $userData = ORM::for_table('admins')
                        ->where('login', $login)
                        ->findOne();
                    
                    if($userData == NULL || !password_verify($pass, $userData->pass)){
                        $_SESSION['message'] = "Zły login lub hasło";
                        header("Location: ../index.php?page=admin");
                        exit;
                    }else{
                        $_SESSION['admin'] = $userData->id;
                        header("Location: ../index.php?page=admin");
                        exit;                    
                    }
                }
                    header("Location: ../index.php");
            }break;
            case 'remove':
            {
                if(isset($_GET['id'])){
                    ORM::for_table('questions')
                        ->where('id', $_GET['id'])
                        ->findOne()
                        ->delete();
                    $_SESSION['message'] = "Usunięto pomyślnie";
                    header("Location: ../index.php?page=admin");
                    exit;
                }
            }
            case 'qadd':
            {
                if(
                       isset($_POST['question'])
                    && isset($_POST['ans_a'])
                    && isset($_POST['ans_b'])
                    && isset($_POST['ans_c'])
                    && isset($_POST['ans_d'])
                    && isset($_POST['answer'])
                ){
                    $question = ORM::for_table('questions')
                        ->create();
                    $question->content = $_POST['question'];
                    $question->a = $_POST['ans_a'];
                    $question->b = $_POST['ans_b'];
                    $question->c = $_POST['ans_c'];
                    $question->d = $_POST['ans_d'];
                    $question->correct = $_POST['answer'];
                    $question->save();
                    $_SESSION['message'] = "Dodano pomyślnie pytanie";
                        header("Location: ../index.php?page=qadd");
                }
                else{
                    $_SESSION['message'] = "Nieprawidłowe dane";
                        header("Location: ../index.php?page=qadd");
                }
            }break;
            case 'logout':
            {
                unset($_SESSION['admin']);
                header("Location: ../index.php");
            }break;
        }
    }else {
        header("Location: ../index.php");      
    }