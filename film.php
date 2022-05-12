<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use template\Template;
    use movie\MovieRenderer;
    use mdb\Commentaire;

    if(empty($_GET['id'])){
        header("Location: liste.php");
        exit();
    }

    $rech = new Recherche();
    $movie = $rech->rechercher_id($_GET['id']);
    $com_class = new Commentaire();
    $coms = $com_class->getAllComs($movie[0]->movie_id);

    $rendered_coms = "";

    foreach($coms as $com){
        $rendered_coms .= $com_class->renderCom($com);
    }

    $content = "";

    session_start();
    if (empty($_SESSION["admin_granted"])) {
            $content .= "<div id='solo-movie'>". MovieRenderer::solo_film($movie[0]) ."</div>";
    } else {
        if (($_SESSION['admin_granted'])) {
            $content .= "<div id='solo-movie'>". MovieRenderer::admin_solo_film($movie[0]) ."</div>";
        } else {
            $content .= "<div id='solo-movie'>". MovieRenderer::solo_film($movie[0]) ."</div>";
        }
    }
    session_commit();

    $content .= "
        <button type='button' class='btn btn-secondary' style='display: flex; margin-left: 25%; margin-top: 20px; margin-right: 60%' id='com-form-button'>Ajoute un commentaire</button>
        <div id='com-form' class=''></div>
        <label for='commentaires' id='com-label'>Commentaires</label>
        <div id='commentaires' name='commentaires'>". $rendered_coms ."</div>
    ";

    Template::render($content);
?>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        let button = document.getElementById("com-form-button")
        let form_div = document.getElementById("com-form")
        let movie_id = document.getElementById("movie_id")
        let cpt_mail = 0;
        let cpt_pseudo = 0;
        let cpt_com = 0;

        button.addEventListener("click", function(){
            button.remove()
            form_div.innerHTML = "<form method='post' action='add_com.php' id='com-form-form'>" +
                    "<div id='a' style='display: flex; flex-direction: row'>" +
                        "<div id='mail&pseudo' style='display: flex; flex-direction: column'>" +
                        "<label for='mail'>E-Mail</label>" +
                        "<input type='email' name='mail' style='width: 250px;' id='form-mail'>" +
                        "<label for='pseudo'>Pseudo</label>" +
                        "<input type='text' name='pseudo' style='width: 250px;' id='form-pseudo'>" +
                        "</div>" +
                        "<div id='errors'>" +
                        "</div>" +
                    "</div>" +
                    "<label for='commentaire' style='margin-left: 30px'>Commentaire</label>" +
                    "<textarea type='text' name='commentaire' style='width: 500px; height: 150px; margin-left: 30px;' id='form-com'></textarea>" +
                    "<button type='submit' name='id' value='" + movie_id.value +"' class='btn btn-dark' disabled='true' id='form-button'>Ajouter ce commentaire</button>"
                "</form>";
            form_div.classList.add("com-form-style")

            let form = document.getElementById("com-form-form")
            let form_mail = document.getElementById("form-mail")
            let form_pseudo = document.getElementById("form-pseudo")
            let form_com = document.getElementById("form-com")
            let form_button = document.getElementById("form-button")
            let error_div = document.getElementById("errors")

            let mail_error = document.createElement("p")
            mail_error.innerText = "Entrez un mail valide."

            let pseudo_error = document.createElement("p")
            pseudo_error.innerHTML = "Entrez un pseudo valide. <br> (Entre 4 et 20 caractères)"

            let com_error = document.createElement("p")
            com_error.innerText = "Entrez un commentaire valide, pas plus de 100 caractères."

            izoké(cpt_mail, cpt_pseudo, cpt_com)
            error_div.appendChild(mail_error)
            let mail_err_displayed = true

            error_div.appendChild(pseudo_error)
            let pseudo_err_displayed = true

            error_div.appendChild(com_error)
            let com_err_displayed = true

            form_mail.addEventListener("input", function(){
                let test_expression = /\S+@\S+\.\S+/;
                if(test_expression.test(this.value)){
                    cpt_mail = 1
                    if(mail_err_displayed){
                        error_div.removeChild(mail_error)
                        mail_err_displayed = !mail_err_displayed
                    }
                }
                else{
                    cpt_mail = 0
                    if(!mail_err_displayed){
                        error_div.appendChild(mail_error)
                        mail_err_displayed = !mail_err_displayed
                    }
                }
                izoké(cpt_mail, cpt_pseudo, cpt_com);
            })

            form_pseudo.addEventListener("input", function(){
                if(this.value.length < 21 && this.value.length > 3){
                    cpt_pseudo = 1
                    if(pseudo_err_displayed){
                        error_div.removeChild(pseudo_error)
                        pseudo_err_displayed = !pseudo_err_displayed
                    }
                }
                else{
                    cpt_pseudo = 0;
                    if(!pseudo_err_displayed){
                        error_div.appendChild(pseudo_error)
                        pseudo_err_displayed = !pseudo_err_displayed
                    }
                }
                izoké(cpt_mail, cpt_pseudo, cpt_com)
            })

            form_com.addEventListener("input", function(){
                if(this.value.length > 0 && this.value.length < 101){
                    cpt_com = 1;
                    if(com_err_displayed){
                        error_div.removeChild(com_error)
                        com_err_displayed = !com_err_displayed
                    }
                }
                else{
                    cpt_com = 0;
                    if(!com_err_displayed){
                        error_div.appendChild(com_error)
                        com_err_displayed = !com_err_displayed
                    }
                }
                izoké(cpt_mail, cpt_pseudo, cpt_com)
            })

            function izoké(cpt_mail, cpt_pseudo, cpt_com){
                if (cpt_mail+cpt_pseudo+cpt_com == 3) {
                    form_button.disabled = false;
                }
                else form_button.disabled = true;
            }
        })


    })
</script>