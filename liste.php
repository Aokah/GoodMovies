<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    require "classes/mdb/MovieDB.php";
    use template\Template;
    use mdb\MovieDB;
    use movie\MovieRenderer;
    session_start();

    if(!isset($_POST['page-value'])) $page = 0;
    else $page = $_POST['page-value'];

    if(isset($_POST['page'])){
        if($_POST['page']==0){
            $page--;
        }
            if($_POST['page']==1){
            $page++;
        }
    }


    if($page < 1) $page = 1;
    $db = new MovieDB();

    if(!isset($_POST['tri-value'])) $tri = "titre";
    else $tri = $_POST['tri-value'];

    if($tri == "titre") $options = "<option value='sortie'>Par sortie</option>
                            <option value='titre' selected='selected'>Par titre</option>";
    else $options = "<option value='sortie' selected='selected'>Par sortie</option>
                            <option value='titre'>Par titre</option>";

    $content = "<div id='search-div'>
                    <form method='get' action='resultat_recherche.php' id='search-form' class='form-control rounded'>
                        <input type='search' name='recherche' placeholder='Recherche'>
                        <button type='submit' class='btn btn-primary'>Search</button>
                    </form>
                    <form method='post' action='liste.php' id='tri-form'>
                        <legend>Trier par :</legend>
                        <select id='tri-select' name='tri-value' size='2'>
                            ". $options ."
                        </select>
                    </form>
                </div>
                    <section id='movie-list'>";


    $movie = $db->getAllMovies($tri);

    for($i=(($page-1) * 25); $i<($page * 25); $i++){
        if(!empty($movie[$i])){
            if (empty($_SESSION["admin_granted"])) {
                $content .= MovieRenderer::card_render($movie[$i]);
            } else {
                if (($_SESSION['admin_granted'])) {
                    $content .= MovieRenderer::admin_card_render($movie[$i]);
                } else {
                    $content .= MovieRenderer::card_render($movie[$i]);
                }
            }
        }
    }

    $content .= "    </section>
                 
                 <form method='post' action='liste.php' id='pages-button-form'>
                    <button type='submit' name='page' value='0' class='btn btn-secondary' id='btn--'>Précédente</button>
                    <button type='submit' name='page' value='1' class='btn btn-primary' id='btn-plus'>Suivante</button>
                    <input type='text' name='tri-value' value='". $tri ."' style='visibility: hidden; width: 0px'>
                    <input type='text' name='page-value' value='". $page ."' style='visibility: hidden; width: 0px'>
                 </form>";

    session_commit();

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
