<?php
    session_start();
?>
<header>
    <nav class="navbar navbar-dark bg-dark">
        <a href="index.php" class="header-element"><h2>Good Movies</h2></a>
        <a href="liste.php" class="header-element">Liste</a>
        <div style="flex:1;"></div>
        <?php
                if(empty($_SESSION['admin_granted'])){
                    echo '<a href="login.php" class="header-element">Login</a>';
                } else {
                    if($_SESSION['admin_granted']) {
                        echo '<a href="creation.php" class="header-element">Créer fiche</a>
                          <a href="logout.php" class="header-element">Logout</a>';
                    } else {
                        echo '<a href="login.php" class="header-element">Login</a>';
                    }
                }?>
    </nav>
</header>

<?php
    session_commit();
?>