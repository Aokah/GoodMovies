<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use mdb\MovieDB;

    if(empty($_FILES) && empty($_POST)){
        header("Location: index.php");
        exit();
    }

    /*var_dump($_FILES);
    var_dump($_POST);*/

    $file = $_FILES["fichier"];

        if ($file['error'] == 0) {
            $file_name = $file['name'];
            $dir_name = './src/img/';
            $full_name = $dir_name . $file_name;

            if(move_uploaded_file($file['tmp_name'], $full_name)) {
                $new_movie = new MovieDB();
                $new_movie->addMovie(utf8_decode($_POST['titre']), $_POST['date'], $file_name, utf8_decode($_POST['synopsis']));
                header("Location: index.php");
                exit();
            } else {
                echo 'problème';
            }
        }

