<?php

namespace mdb;
use \PDO;

class Commentaire
{
    private static $pdo;

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

    public function getAllComs($movie_id): array{
        $state = self::$pdo->prepare("SELECT * FROM commentaires WHERE movie_id = " . $movie_id);
        $state->execute() or die(var_dump($state->errorInfo()));

        return $state->fetchAll(PDO::FETCH_OBJ);
    }

    public function renderCom($com): string{

        return '
            <article class="commentaire">
                <p class="com-pseudo">'. utf8_encode($com->pseudo) .'</p>
                <hr style="margin-top: -7px">
                <div class="com-com">'.utf8_encode($com->commentaire).'</div>
                <hr style="width: 200px">
            </article>
        ';
    }

    public function verif($mail, $pseudo): bool{
        $state = self::$pdo->prepare('SELECT * FROM commentaires WHERE mail = "' . $mail .'"');
        $state->execute() or die(var_dump($state->errorInfo()));

        $res = $state->fetchAll(PDO::FETCH_OBJ);
        $verif = true;
        foreach($res as $test){
            if($test->pseudo != $pseudo){
                $verif = false;
                break;
            }
        }

        $state = self::$pdo->prepare("SELECT * FROM commentaires WHERE pseudo = '" . $pseudo ."'");
        $state->execute() or die(var_dump($state->errorInfo()));
        $res = $state->fetchAll(PDO::FETCH_OBJ);
        foreach($res as $test) {
            if ($test->mail != $mail) {
                $verif = false;
                break;
            }
        }

        return $verif;
    }

    public function addCom($movie_id, $mail, $pseudo, $com): void{
        $state = self::$pdo->prepare("insert into commentaires values (:movie_id, :mail, :pseudo, :commentaire)");

        $state->bindValue(':movie_id', $movie_id);
        $state->bindValue(':mail', utf8_decode($mail));
        $state->bindValue(':pseudo', utf8_decode($pseudo));
        $state->bindValue(':commentaire', utf8_decode($com));

        $state->execute() or die(var_dump($state->errorInfo()));
    }
}