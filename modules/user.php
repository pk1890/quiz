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

                    $userData = ORM::for_table('users')
                        ->where('login', $login)
                        ->findOne();
                    
                    if($userData == NULL || !password_verify($pass, $userData->pass)){
                        $_SESSION['message'] = "Zły login lub hasło";
                        header("Location: ../index.php?page=userLogin");
                        exit;
                    }else{
                        $_SESSION['userId'] = $userData->id;
                        header("Location: ../index.php");
                        exit;
                    }
                }
                    header("Location: ../index.php");
                
            }break;
            case 'register':{
                if(
                       isset($_POST['login'])
                    && isset($_POST['pass'])
                    && isset($_POST['confirmPass'])
                    && isset($_POST['email'])
                    // && isset($_POST['accept'])
                    && $_POST['login'] != ""
                    && $_POST['pass'] != ""
                    && $_POST['confirmPass'] != ""
                    && $_POST['email'] != ""
                    ){
                        if($_POST['accept'] != true){
                            $_SESSION['message'] = "Musisz zaakceptować regulamin";
                            header("Location: ../index.php?page=register");
                            exit;
                        }

                        $login = $_POST['login'];
                        $pass = $_POST['pass'];
                        $email = $_POST['email'];

                        $data = ORM::for_table('users')
                            ->whereAnyIs([
                                ['login' => $login],
                                ['email' => $email]
                            ])->findOne();
                        if($data != NULL){
                            $_SESSION['message'] = "Podany email lub nazwa użytkownika jest już zajęty";
                            header("Location: ../index.php?page=register");
                            exit;
                        }
                        $user = ORM::for_table('users')
                            ->create();
                        $user->login = $_POST['login'];
                        $user->pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                        $user->email = $_POST['email'];
                        
                        $user->save();
                        
                        $_SESSION['message'] = "Rejestracja zakończona sukcesem";
                        header("Location: ../index.php");
                        exit;
                    }else{

                        $_SESSION['message'] = "Nieprawidłowe lub niekompletne dane";
                            header("Location: ../index.php?page=register");
                            exit;
                    }
            }break;
            case 'logout':
            {
                unset($_SESSION['userId']);
                $_SESSION['message'] = "Wylogowano";
                header("Location: ../index.php");
            }break;
        }
    }else {
        header("Location: ../index.php");      
    }