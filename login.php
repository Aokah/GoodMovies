<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use template\Template;

$login = new Login();

    if(empty($_POST)){
        $content = Login::generateLoginForm();
    } else {
        $res = $login->try($_POST['login'], $_POST['mdp']);
        if($res['granted']){
            session_start();

            $_SESSION['admin_granted'] = true;
            header("Location: index.php");
            exit();
        } else {
            $content = "<div id='wrapper'>";
            $content .= $login->generateErrors($res['login_err'], $res['mdp_err']);
            $content .= Login::generateLoginForm();
            $content .= "</div>";
        }
    }

    Template::render($content);