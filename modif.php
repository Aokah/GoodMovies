<?php
    session_start();
    if(!$_SESSION['admin_granted'] || !isset($_POST['id'])){
        header("Location: index.php");
        exit();
    }
    session_commit();

    require "classes/Autoloader.php";
    Autoloader::register();
    use mdb\MovieDB;

    $db = new MovieDB();



    if($_POST['button'] == 'modif'){
        var_dump($_FILES);
        if($_FILES['fichier']['name'] != ""){
            $file = $_FILES["fichier"];

            if ($file['error'] == 0) {
                $file_name = $file['name'];
                $dir_name = './src/img/';
                $full_name = $dir_name . $file_name;


                if(move_uploaded_file($file['tmp_name'], $full_name)) {
                    $new_movie = new MovieDB();
                    $new_movie->changeMovie($_POST['id'], utf8_decode($_POST['titre']), $_POST['date'], $file_name, utf8_decode($_POST['synopsis']));
                    header("Location: index.php");
                    exit();
                } else {
                    echo 'problème';

                }
            }
        } else {
            $new_movie = new MovieDB();
            $new_movie->changeMovie($_POST['id'], utf8_decode($_POST['titre']), $_POST['date'], null, utf8_decode($_POST['synopsis']));
        }
    }
    elseif($_POST['button'] == 'delete'){
        $db->deleteMovie($_POST['id']);
    }

    header("Location: index.php");
    exit();