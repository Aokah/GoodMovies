<?php
    if(empty($_POST)){
        header("Location: liste.php");
        exit();
    }

    require "classes/Autoloader.php";
    Autoloader::register();
    use mdb\Commentaire;
    use template\Template;

    $com = new Commentaire();

    if($com->verif($_POST['mail'], $_POST['pseudo'])){
        $com->addCom($_POST['id'], $_POST['mail'], $_POST['pseudo'], $_POST['commentaire']);
        $good_content = "
            <form style='display:flex; flex-direction: column; text-align: center; margin: 20px;' action='film.php' method='get' id='form'>
                <h4 style='color: darkgreen'>Commentaire ajouté !</h4>
                <button class='btn btn-danger' type='submit' style='width: 350px; margin: auto' name='id' value='". $_POST['id'] ."'>Retour</button>
            </form>
        ";
        Template::render($good_content);
    } else {
        $error_content = "
            <form style='display:flex; flex-direction: column; text-align: center; margin: 20px;' action='film.php' method='get' id='form'>
                <h4 style='color: orangered'>Vous avez déjà utilisé ce mail avec un autre pseudo ou ce pseudo avec un autre mail, merci d'utiliser la bonne combinaison.</h4>
                <button class='btn btn-danger' type='submit' style='width: 350px; margin: auto' name='id' value='". $_POST['id'] ."'>Retour</button>
            </form>
        ";
        Template::render($error_content);
    }
?>
