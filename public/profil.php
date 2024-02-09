<html>
    <head>
        <title>Mon Profil</title>
    </head>
    <body>
        <h1>Mon Profil</h1>
        <a href='logout.php'>Déconnexion</a>
        <a href='albums.php'>Tous les albums</a>
        <h2>Mes informations</h2>
        <?php
            session_start();
            unset($_SESSION['next']);

            include '../src/Factory.php';
            include '../data/DB.php';
            require_once '../src/autoloader.php';

            use src\Model\Genre;
            use src\Model\Album;
            use src\Model\Musicien;

            $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='
            .$_SESSION["idUtilisateur"])[0];

            $html.="<p>Nom: ".$user->nomUtilisateur.
            "<p>Mot de Passe: ".$user->mdp.
            "<p>Admin: ".($user->estAdmin ? 'TRUE' : 'FALSE');

            echo $html;
        ?>
        <h2>Mon Playlist</h2>
        <ul>
        <?php
            $html="";
            $playlist = DB::db_script('SELECT * FROM APPRECIER WHERE idUtilisateur='
            .$_SESSION["idUtilisateur"].' AND estDansPlaylist=true');
            foreach($playlist as $p){
                $objetAlbum = DB::db_script('SELECT * FROM ALBUM WHERE idAlbum='.$p['idAlbum'])[0];
                $html.=$objetAlbum->render();
            }
            echo $html;
        ?>
        </ul>
        <h2>Mes Avis</h2>
        <ul>
        <?php
            $html="";

            if(!empty($_GET['idAlbum'])){
                $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                $user->delete_note_album($_GET['idAlbum']);
            }

            $avis = DB::db_script('SELECT * FROM APPRECIER WHERE idUtilisateur='
            .$_SESSION["idUtilisateur"].' AND note IS NOT NULL');
            foreach($avis as $a){
                $objetAlbum = DB::db_script('SELECT * FROM ALBUM WHERE idAlbum='.$a['idAlbum'])[0];
                $html.='<li><h3>'.$objetAlbum->titre.'</h3>';
                $html.='<p>Note: '.$a['note'].'</p></li>';
                $html.="<a href='profil.php?idAlbum=".$a['idAlbum']."' onclick='return confirm(\"Confirmation\")'>Supprimer</a>";
            }
            echo $html;
        ?>
        </ul>
    </body>
</html>

            