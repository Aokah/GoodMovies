<?php

namespace mdb;
use \PDO;

class MovieDB{
    public static $pdo;

    public function __construct(){
        try{
            $dbname = "moviesdb"; $dbhost = "127.0.0.1"; $dbport = "3306";
            $dbuser = "root"; $dbpwd = "";
            $dsn = 'mysql:dbname=' . $dbname . ";host=" . $dbhost . ";port=" . $dbport;
            self::$pdo = new PDO($dsn, $dbuser, $dbpwd);

        } catch (\Exception $ex){
            die("Erreur :" . $ex->getMessage());
        }
    }

    public function getAllMovies($tri): array{
        if($tri == null) $state = self::$pdo->prepare("SELECT * FROM movies WHERE movie_id");
        if($tri == "sortie") $state = self::$pdo->prepare("SELECT * FROM movies ORDER BY date_sortie");
        if($tri == "titre") $state = self::$pdo->prepare("SELECT * FROM movies ORDER BY titre");

        $state->execute() or die(var_dump($state->errorInfo()));

        return $state->fetchAll(PDO::FETCH_OBJ);
    }

    public function addMovie($name, $date_sortie, $affiche, $synopsis){
        $state = self::$pdo->prepare("insert into movies (titre, date_sortie, affiche, synopsis) values (:titre, :date_sortie, :affiche, :synopsis)");

        $state->bindValue(':titre', $name);
        $state->bindValue(':date_sortie', $date_sortie);
        $state->bindValue(':affiche', $affiche);
        $state->bindValue(':synopsis', $synopsis);

        $state->execute() or die(var_dump($state->errorInfo()));
    }

    public function changeMovie($id, $name, $date_sortie, $affiche, $synopsis){
        if($affiche == null) $state = self::$pdo->prepare('update movies set titre = "'. $name .'", date_sortie = "'. $date_sortie .'", synopsis = "'. $synopsis .'" where movie_id = '. $id);
        else $state = self::$pdo->prepare('update movies set titre = "'. $name .'", date_sortie = "'. $date_sortie .'", affiche = "'. $affiche .'", synopsis = "'. $synopsis .'" where movie_id = '. $id);

        $state->execute() or die(var_dump($state->errorInfo()));
    }

    public function getMovieById($id){
        $query = 'SELECT * FROM movies WHERE movie_id = ' . $id;

        $state = self::$pdo->prepare($query);
        $state->execute() or die(var_dump($state->errorInfo()));

        $res = $state->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }

    public function deleteMovie($id){
        $movie = $this->getMovieById($id)[0];
        unlink("src/img/" . $movie->affiche);

        $state = self::$pdo->prepare("delete from movies where movie_id =". $id);

        $state->execute() or die(var_dump($state->errorInfo()));
    }
}