<?php
    require "classes/Autoloader.php";
    Autoloader::register();
    use template\Template;
    use mdb\MovieDB;
    use movie\MovieRenderer;


    $db = new MovieDB();
    session_start();
    $content = "<div id='index-movie-list' class='carousel slide' style='display: flex; margin: auto; margin-top: 100px; height:525px' data-ride='carousel' data-pause='hover' data-interval='10000'>
                    <div class='carousel-inner' style='margin: 20px 60px'>";

    $test = true;
    $movies = $db->getAllMovies(null);
    $length = sizeof($movies);
    for($i=0; $i<5; $i++){
        $movie = $movies[random_int(0, $length-1)];
        if($test){
            $content .= "<div class='carousel-item active'>";
            $test = false;
        }
        else $content .= "<div class='carousel-item' >";
        if (empty($_SESSION["admin_granted"])) {
            $content .= MovieRenderer::index_solo_film($movie);
        } else {
            if (($_SESSION['admin_granted'])) {
                $content .= MovieRenderer::admin_index_solo_film($movie);
            } else {
                $content .= MovieRenderer::index_solo_film($movie);
            }
        }
        $content .= "</div>";
    }

    $content .= "   </div>
                    <a class='carousel-control-prev' href='#index-movie-list' role='button' data-slide='prev' style='width: 75px'>
                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    </a>
                    <a class='carousel-control-next' href='#index-movie-list' role='button' data-slide='next' style='width: 75px'>
                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    </a>    
                </div>";
    session_commit();

    Template::render($content);