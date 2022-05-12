<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use template\Template;
    use movie\MovieRenderer;

    if(!isset($_GET['recherche'])){
        header("Location: index.php");
        exit();
    }

    $rech = new Recherche();

    $content = "<div id='search-div'>
                    <form method='get' action='resultat_recherche.php' id='search-form' class='form-control rounded'>
                        <input type='search' name='recherche' placeholder='Rechercher' value='" .$_GET['recherche'] . "'>
                        <button type='submit' class='btn btn-primary'>Search</button>
                    </form>
                    <form method='get' action='resultat_recherche.php' id='tri-form'>
                        <legend>Trier par :</legend>
                        <select id='tri-select' name='tri-value' size='2'>
                            <option value='sortie'>Par sortie</option>
                            <option value='titre'>Par titre</option>
                        </select>
                        <input type='text' name='recherche' value='". $_GET['recherche'] ."' style='visibility: hidden; width: 0px;'>
                    </form>
                </div>
                <form method='get' action='film.php'>
                    <section id='movie-list'>";

    if(!isset($_GET['tri-value'])) $tri = "titre";
    else $tri = $_GET['tri-value'];

    session_start();
    foreach ($rech->rechercher_name($_GET['recherche'], $tri) as $movie){
            if (empty($_SESSION["admin_granted"])) {
                $content .= MovieRenderer::card_render($movie);
            } else {
                if (($_SESSION['admin_granted'])) {
                    $content .= MovieRenderer::admin_card_render($movie);
                } else {
                    $content .= MovieRenderer::card_render($movie);
                }
            }
    }
    session_commit();

    $content .= "</section>
                 </form>";

    Template::render($content);

?>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        let select = document.getElementById("tri-select")
        let form = document.getElementById("tri-form")

        select.addEventListener("change", function(){
            form.submit();
        })
    })
</script>
