<?php

namespace movie;

class MovieRenderer{

    public static function card_render($movie){
            return '<article class="card list-card" style="height: 448px;">
                        <form method="get" action="film.php">
                            <button type="submit" style="background-color: #181426; border: none; height: 450px;" name="id" value="' . $movie->movie_id . '" class="button">
                            <img src="src/img/' . $movie->affiche . '" class="movie-poster card-img-top">
                            <div class="movie-content">
                                <h5 class="card-title">' . utf8_encode($movie->titre) . '</h5>                
                                <div class="movie-year card-text">' . $movie->date_sortie . '</div>
                            </div>
                            </button>
                        </form>
                    </article>';
    }

    public static function admin_card_render($movie){
        return '
                <article class="card list-card" style="height: 448px;">
                    <form method="get" action="film.php" style="margin-bottom: 0px">
                        <button type="submit" style="background-color: #181426; border: none;" name="id" value="'. $movie->movie_id .'" class="button">
                        <img src="src/img/' . $movie->affiche . '" class="movie-poster card-img-top">
                        <div class="movie-content">
                            <h5 class="card-title">' . utf8_encode($movie->titre) . '</h5>                
                            <div class="movie-year card-text">' . $movie->date_sortie . '</div>
                        </div>
                        </button>
                    </form>
                    <form action="modification.php" method="get" id="modif-form" style="width: 100%; background-color: #181426; margin-bottom: 0px;">
                        <button type="submit" name="movie_id" id="movie_id" value="'.$movie->movie_id.'" style="display: flex; margin: auto;" class="btn btn-dark">Modifier</button>
                    </form>
                </article>';
    }

    public static function solo_film($movie): string{
        return '
            <img src="src/img/' . $movie->affiche . '" id="solo-img">
            <div id="movie-content">
                <h2 id="solo-title">' . utf8_encode($movie->titre) . '</h2>
                <h4 id="solo-year"> Sorti le : ' . $movie->date_sortie . ' </h4>
                <div id="solo-synopsis"> <h5>Synopsis :</h5> <span>' . utf8_encode($movie->synopsis) . ' </span></div>
                <button id="movie_id" value="'.$movie->movie_id.'" style="visibility: hidden"></button>
            </div>
        ';
    }

    public static function admin_solo_film($movie): string{
        return '
            <div>
                <img src="src/img/' . $movie->affiche . '" id="solo-img">
                <form action="modification.php" method="get">
                    <button type="submit" name="movie_id" id="movie_id" value="'.$movie->movie_id.'" style="display: flex; margin: auto;" class="btn btn-dark">Modifier</button>
                </form>
            </div>
            <div id="movie-content">
                <h2 id="solo-title">' . utf8_encode($movie->titre) . '</h2>
                <h4 id="solo-year"> Sorti le : ' . $movie->date_sortie . ' </h4>
                <div id="solo-synopsis"> <h5>Synopsis :</h5> ' . utf8_encode($movie->synopsis) . ' </div>
                
            </div>
        ';
    }

    public static function index_solo_film($movie): string{ //quasi même fonction que solo_film mais là on rajoute un lien sur le titre et l'image
        return '
            <a href="film.php?id='. $movie->movie_id .'"><img src="src/img/' . $movie->affiche . '" id="solo-img"></a>
            <div id="movie-content">
                <a href="film.php?id='. $movie->movie_id .'" style="text-decoration: none; color:white;"><h2 id="solo-title">' . utf8_encode($movie->titre) . '</h2></a>
                <h4 id="solo-year"> Sorti le : ' . $movie->date_sortie . ' </h4>
                <div id="solo-synopsis"> <h5>Synopsis :</h5> <span>' . utf8_encode($movie->synopsis) . ' </span></div>
                <button id="movie_id" value="'.$movie->movie_id.'" style="visibility: hidden"></button>
            </div>
        ';
    }

    public static function admin_index_solo_film($movie): string{
        return '
            <div>
                <a href="film.php?id='. $movie->movie_id .'"><img src="src/img/' . $movie->affiche . '" id="solo-img"></a>
                <form action="modification.php" method="get">
                    <button type="submit" name="movie_id" id="movie_id" value="'.$movie->movie_id.'" style="display: flex; margin: auto;" class="btn btn-dark">Modifier</button>
                </form>
            </div>
            <div id="movie-content">
                <a href="film.php?id='. $movie->movie_id .'" style="text-decoration: none; color:white;"><h2 id="solo-title">' . utf8_encode($movie->titre) . '</h2></a>
                <h4 id="solo-year"> Sorti le : ' . $movie->date_sortie . ' </h4>
                <div id="solo-synopsis"> <h5>Synopsis :</h5> ' . utf8_encode($movie->synopsis) . ' </div>
                
            </div>
        ';
    }
}