<?php

class Login
{
    public static function generateLoginForm(): string{
        return '
            <div id="log-div">
                <form action="login.php" method="post" id="log-form">
                    <label for="login">Login</label>
                    <input name="login" type="text">
                    <label for="mdp">Mot de passe</label>
                    <input name="mdp" type="password">
                    <button class="btn btn-dark">Log in</button>
                </form>
            </div>    
        ';
    }

    public function generateErrors($login, $mdp){
        $content = "";
        if($login || $mdp){
            $content .= "
                <div class='error'>
                    Mauvais login ou mdp
                </div>";
         }
         return $content;
    }

    public function try($login, $mdp): array{
        $granted = false;
        $login_err = false;
        $mdp_err = false;

        if($login != "admin") $login_err = true;
        if($mdp != "mdpadmin") $mdp_err = true;

        if(!$login_err && !$mdp_err){
            $granted = true;
        }

        $res = [
            'granted' => $granted,
            'login_err' => $login_err,
            'mdp_err' => $mdp_err
        ];

        return $res;
    }
}