<?php

class Recherche
{
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

    public function rechercher_name($rechercher, $tri){
        $rechercher = explode(" ", $rechercher);
        $arech = "";
        foreach($rechercher as $mot){
            $arech .= "%" . $mot;
        }
        $arech .= "%";

        $query = "";
        if($tri == null) $query = 'SELECT * FROM movies WHERE titre LIKE "' . $arech . '"';
        elseif($tri == "sortie") $query = 'SELECT * FROM movies WHERE titre LIKE "' . $arech . '" ORDER BY date_sortie';
        elseif($tri == "titre") $query = 'SELECT * FROM movies WHERE titre LIKE "' . $arech . '" ORDER BY titre';

        $state = self::$pdo->prepare($query);
        $state->execute() or die(var_dump($state->errorInfo()));

        $res = $state->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }

    public function rechercher_id($rechercher){
        $query = 'SELECT * FROM movies WHERE movie_id = ' . $rechercher;

        $state = self::$pdo->prepare($query);
        $state->execute() or die(var_dump($state->errorInfo()));

        $res = $state->fetchAll(PDO::FETCH_OBJ);
        return $res;
    }
}