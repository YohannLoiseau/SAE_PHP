<nav>
    <img src='../data/images/logo.png' alt='Logo'>
    <?php
        if (isset($_SESSION['idUtilisateur'])) {
            $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
            $html="<ul>
            <li><a href='logout.php'><button>Déconnexion</button></a></li>
            <li><a href='profil.php'><button>".$user->nomUtilisateur."</button></a><li/>
            </ul>";
        }else{
            $html="<ul>
            <li><a href='login.php'><button>Connexion</button></a></li>
            </ul>";
        }
        echo $html;
    ?>
</nav>