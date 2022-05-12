<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use template\Template;
    use mdb\MovieDB;

    session_start();
    if(!$_SESSION['admin_granted']){
        header("Location: index.php");
        exit();
    }
    session_commit();

    $rech = new Recherche();
    $movie = $rech->rechercher_id($_GET['movie_id']);
    $movie = $movie[0];

    $content = '
            <div>
                <form action="modif.php" method="post" enctype="multipart/form-data" id="add-form">
                    <div id="affiche-div">
                        <input name="id" value="'. $movie->movie_id .'" style="visibility: hidden">
                        <input type="file" name="fichier" id="affiche" value="'. $movie->affiche .'" accept=".jpg .png .jpeg">
                    </div>
                    <div id="film-infos">
                        <label for="titre">Titre du film</label>
                        <input type="text" name="titre" id="titre" style="width: 300px;" value="'. utf8_encode($movie->titre) .'">
                        <label for="date_de_sortie">Date de sortie</label>
                        <input type="date" name="date" id="date" style="width: 140px;" value="'. $movie->date_sortie .'">
                        <label for="synopsis">Synopsis</label>
                        <textarea name="synopsis" id="synopsis" style="width: 350px; height: 80px;">'. utf8_encode($movie->synopsis) .'</textarea>
                    </div>
                    <div id="error-div">
                        <button type="submit" name="button" value="modif" id="submit-button" class="btn btn-dark" style="width: 150px; height: 50px; margin: 20px;" disabled="true">Modifier</button>
                        <button type="submit" name="button" value="delete" id="delete-button" class="btn btn-danger" style="width: 150px; height: 50px; margin-left: 20px; margin-right: 20px; margin-bottom: 20px;">Supprimer</button>
                    </div>
                </form>
                
                <div id="solo-movie" name="previsu-div">
                    <img id="solo-img" class="movie-poster" src="src/img/'. $movie->affiche .'">
                    <div id="previsu-film-infos">
                        <h2 id="solo-title"></h2>
                        <h4 id="solo-year"> Sorti le :</h4>
                        <div><h5>Synopsis :</h5><p id="solo-synopsis"></p></div> 
                    </div>
                </div>
            </div>
        ';

    Template::render($content);

?>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        let affiche = document.getElementById("affiche");
        let titre = document.getElementById("titre")
        let date = document.getElementById("date")
        let synopsis = document.getElementById("synopsis");
        let submit_button = document.getElementById("submit-button")
        let errors_div = document.getElementById("error-div")

        let pre_affiche = document.getElementById("solo-img")
        let pre_titre = document.getElementById("solo-title")
        let pre_date = document.getElementById("solo-year")
        let pre_synopsis = document.getElementById("solo-synopsis");

        pre_titre.innerText = titre.value
        pre_date.innerText = date.value
        pre_synopsis.innerText = synopsis.value

        let cpt_affiche = true
        let affiche_err = document.createElement("p")
        let affiche_err_displayed = false
        affiche_err.innerText = "Il faut une image valide"

        let cpt_titre = true
        let titre_err = document.createElement("p")
        let titre_err_displayed = false
        titre_err.innerText = "Il faut une titre valide"

        let cpt_date = true
        let date_err = document.createElement("p")
        let date_err_displayed = false
        date_err.innerText = "Il faut une date de sortie valide"

        let cpt_synopsis = true
        let synopsis_err = document.createElement("p")
        let synopsis_err_displayed = false
        synopsis_err.innerText = "Il faut un synopsis valide"


        function previewImage(input) {
            var preview = pre_affiche
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.setAttribute('src', 'placeholder.png');
            }
        }

        affiche.addEventListener("change", function(){
            previewImage(this)
            if(affiche.value != ""){
                cpt_affiche = true
                if(affiche_err_displayed){
                    errors_div.removeChild(affiche_err)
                    affiche_err_displayed = !affiche_err_displayed
                }
            } else {
                cpt_affiche = false
                if(!affiche_err_displayed){
                    errors_div.appendChild(affiche_err)
                    affiche_err_displayed = !affiche_err_displayed
                }
            }
            izoké(cpt_affiche, cpt_titre, cpt_date, cpt_synopsis)
        })

        titre.addEventListener("input", function(){
            pre_titre.innerText = titre.value
            if(titre.value.length > 4 && titre.value.length < 60){
                cpt_titre = true
                if(titre_err_displayed) {
                    errors_div.removeChild(titre_err)
                    titre_err_displayed = !titre_err_displayed
                }
            } else {
                cpt_titre = false
                if(!titre_err_displayed){
                    errors_div.appendChild(titre_err)
                    titre_err_displayed = !titre_err_displayed
                }
            }
            izoké(cpt_affiche, cpt_titre, cpt_date, cpt_synopsis)
        })

        date.addEventListener("input", function(){
            pre_date.innerText = date.value
            if(date != ""){
                cpt_date = true
                if(date_err_displayed){
                    errors_div.removeChild(date_err)
                    date_err_displayed = !date_err_displayed
                }
            } else {
                cpt_date = false
                if(!date_err_displayed){
                    errors_div.appendChild(date_err)
                    date_err_displayed = !date_err_displayed
                }
            }
            izoké(cpt_affiche, cpt_titre, cpt_date, cpt_synopsis)
        })

        synopsis.addEventListener("input", function(){
            pre_synopsis.innerText = synopsis.value
            if(synopsis.value.length >= 20 && synopsis.value.length <= 4000){
                cpt_synopsis = true
                if(synopsis_err_displayed){
                    errors_div.removeChild(synopsis_err)
                    synopsis_err_displayed = !synopsis_err_displayed
                }
            } else {
                cpt_synopsis = false
                if(!synopsis_err_displayed){
                    errors_div.appendChild(synopsis_err)
                    synopsis_err_displayed = !synopsis_err_displayed
                }
            }
            izoké(cpt_affiche, cpt_titre, cpt_date, cpt_synopsis)
        })



        function izoké(cpt_affiche, cpt_titre, cpt_date, cpt_synopsis){
            if(cpt_synopsis && cpt_affiche && cpt_date && cpt_titre){
                submit_button.disabled = false;
            }
        }
    })
</script>