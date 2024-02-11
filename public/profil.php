<html>
    <head>
        <title>Mon Profil</title>
    </head>
    <body>
        <h1>Mon Profil</h1>
        <a href='logout.php'>Déconnexion</a>
        <a href='albums.php'>Tous les albums</a>
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

            if($user->estAdmin)
                echo '<a href="admin.php">Espace Admin</a>';
        ?>
        <h2>Mes informations</h2>
        <?php
            $html="<p>Nom: ".$user->nomUtilisateur.
            "<p>Mot de Passe: ".$user->mdp.
            "<p>Admin: ".($user->estAdmin ? 'TRUE' : 'FALSE');

            echo $html;
        ?>
        <h2>Mon Playlist</h2>
        <?php
            $html="";
            $playlist = DB::db_script('SELECT * FROM APPRECIER WHERE idUtilisateur='
            .$_SESSION["idUtilisateur"]);
            if(count($playlist)>0){
                $html.='<ul>';
                foreach($playlist as $p){
                    $objetAlbum = DB::db_script('SELECT * FROM ALBUM WHERE idAlbum='.$p['idAlbum'])[0];
                    $html.=$objetAlbum->render();
                }
                $html.='</ul>';
            }else{
                $html.="<p>-- aucun album dans votre playlist --</p>";
            }
            
            echo $html;
        ?>
        <h2>Mes Avis</h2>
        <?php
            $html="";
            if(!empty($_GET['idAlbum'])){
                $user = DB::db_script('SELECT * FROM UTILISATEUR WHERE idUtilisateur='.$_SESSION["idUtilisateur"])[0];
                $user->delete_note_album($_GET['idAlbum']);
            }

            $avis = DB::db_script('SELECT * FROM EVALUER WHERE idUtilisateur='.$_SESSION["idUtilisateur"]);

            if(count($avis)>0){
                $html.='<ul>';
                foreach($avis as $a){
                    $objetAlbum = DB::db_script('SELECT * FROM ALBUM WHERE idAlbum='.$a['idAlbum'])[0];
                    $html.='<li><h3>'.$objetAlbum->titre.'</h3>';
                    $html.='<p>Note: '.$a['note'].' / 10</p></li>';
                    $html.="<a href='profil.php?idAlbum=".$a['idAlbum']."' onclick='return confirm(\"Confirmation\")'>Supprimer</a>";
                }
                $html.='</ul>';
            }else{
                $html.="<p>-- aucun avis trouvé --</p>";
            }
            
            echo $html;
        ?>
        </ul>
    </body>
</html>

            