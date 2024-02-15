<header>
    <img src='../data/images/logo.png' alt='Logo'>
    <?php
        if (isset($_SESSION['idUtilisateur'])) {
            $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
            $html="<ul>";
            //if (strpos($_SERVER['PHP_SELF'], "admin") !== false){
            if($user->estAdmin){
                $html.="<button><a href='admin-albums.php'>Gérer les albums</a></button>
                <button><a href='admin-musiciens.php'>Gérer les musiciens</a></button>
                <button><a href='admin-utilisateurs.php'>Gérer les utilisateurs</a></button>";
            }
            $html.="<li><a href='logout.php'><button>Déconnexion</button></a></li>";
            $html.="<li><a href='profil.php'><button>".$user->nomUtilisateur."</button></a><li/>";
            $html.="</ul>";
        }else{
            $html="<ul>
            <li><a href='login.php'><button>Connexion</button></a></li>
            </ul>";
        }
        echo $html;
    ?>
</header>
