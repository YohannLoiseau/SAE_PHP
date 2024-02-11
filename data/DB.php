<?php
class DB{
    static function db_script(string $requete){
        $file_db = new PDO('sqlite:../data/fixtures.sqlite3');
        $stmt = $file_db->query($requete);
        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        if(!isset($instances[0]['nomMusicien']) && !isset($instances[0]['nomGenre']) && 
        !isset($instances[0]['titre']) && !isset($instances[0]['estAdmin'])){
            return $instances;
        }
        foreach($instances as $i){
            $objet = Factory::create($i);
            $res[] = $objet;
        }
        return $res;
    }

    static function db_add_user(string $nom, string $mdp, bool $estAdmin){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $stmt = $file_db->prepare("INSERT INTO UTILISATEUR(idUtilisateur, nomUtilisateur, mdp, estAdmin)
            VALUES (:idUtilisateur, :nomUtilisateur, :mdp, :estAdmin)");

            $new_id_data = $file_db->query('SELECT MAX(idUtilisateur) max from UTILISATEUR');
            $new_id = 1 + $new_id_data->fetch(PDO::FETCH_ASSOC)["max"];

            $stmt->bindParam(':idUtilisateur', $new_id);
            $stmt->bindParam(':nomUtilisateur', $nom);
            $stmt->bindParam(':mdp', $mdp);
            $stmt->bindParam(':estAdmin', $estAdmin);

            $stmt->execute();
            header("Location: admin-utilisateurs.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('ajout utilisateur IMPOSSIBLE. Le nom déjà existe');</script>";
        }
    }

    static function db_delete_user(int $idUtilisateur){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $delete_a = "DELETE FROM APPRECIER WHERE idUtilisateur=$idUtilisateur";
            $delete_e = "DELETE FROM EVALUER WHERE idUtilisateur=$idUtilisateur";
            $delete_u = "DELETE FROM UTILISATEUR WHERE idUtilisateur=$idUtilisateur";

            $file_db->exec($delete_a);
            $file_db->exec($delete_e);
            $file_db->exec($delete_u);
            header("Location: admin-utilisateurs.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('supprimer utilisateur IMPOSSIBLE');</script>";
        }
    }

    static function db_add_musicien(string $nom){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $stmt = $file_db->prepare("INSERT INTO MUSICIEN(nomMusicien)
            VALUES (:nom)");

            $stmt->bindParam(':nom', $nom);

            $stmt->execute();
            header("Location: admin-musiciens.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('ajout musicien IMPOSSIBLE. Le nom déjà existe');</script>";
        }
    }

    static function db_delete_musicien(string $nomMusicien){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $delete_a = "DELETE FROM ALBUM WHERE musicienBy='$nomMusicien'";
            $delete_aa = "DELETE FROM ALBUM WHERE musicienParent='$nomMusicien'";
            $delete_m = "DELETE FROM MUSICIEN WHERE nomMusicien='$nomMusicien'";
            // $delete_u = "DELETE FROM UTILISATEUR WHERE idUtilisateur=$idUtilisateur";

            $file_db->exec($delete_a);
            $file_db->exec($delete_aa);
            $file_db->exec($delete_m);
            // $file_db->exec($delete_u);

            header("Location: admin-musiciens.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('supprimer musicien IMPOSSIBLE');</script>";
        }
    }

    static function db_edit_musicien(string $nomAncien, string $nomMusicien){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $update_a = "UPDATE ALBUM SET musicienBy='$nomMusicien' WHERE musicienBy='$nomAncien'";
            $update_aa = "UPDATE ALBUM SET musicienParent='$nomMusicien' WHERE musicienParent='$nomAncien'";
            $update_m = "UPDATE MUSICIEN SET nomMusicien='$nomMusicien' WHERE nomMusicien='$nomAncien'";

            $file_db->exec($update_a);
            $file_db->exec($update_aa);
            $file_db->exec($update_m);
            header("Location: admin-musiciens.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('modification musicien IMPOSSIBLE. Le nom déjà existe');</script>";
        }
    }

    static function db_add_album(string $titre, string $image, int $annee,
    string $musicienBy, string $musicienParent, string $roleParent, array $genres){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $stmt_album = $file_db->prepare("INSERT INTO ALBUM
            (idAlbum,titre,image,annee,musicienBy,musicienParent,roleParent)
            VALUES (:idAlbum,:titre,:image,:annee,:musicienBy,:musicienParent,:roleParent)");
            $stmt_app = $file_db->prepare("INSERT INTO APPARTENIR
            (nomGenre,idAlbum)
            VALUES(:nomGenre, :idAlbum)");

            $new_id_data = $file_db->query('SELECT MAX(idAlbum) max from ALBUM');
            $new_id = 1 + $new_id_data->fetch(PDO::FETCH_ASSOC)["max"];

            $stmt_album->bindParam(':idAlbum', $new_id);
            $stmt_album->bindParam(':titre', $titre);
            $stmt_album->bindParam(':image', $image);
            $stmt_album->bindParam(':annee', $annee);
            $stmt_album->bindParam(':musicienBy', $musicienBy);
            $stmt_album->bindParam(':musicienParent', $musicienParent);
            $stmt_album->bindParam(':roleParent', $roleParent);

            $stmt_album->execute();
            
            $stmt_app->bindParam(':idAlbum', $new_id);
            foreach($genres as $g){
                $stmt_app->bindParam(':nomGenre', $g);
                $stmt_app->execute();
            }
            header("Location: admin-albums.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('ajout album IMPOSSIBLE');</script>";
        }
    }

    static function db_delete_album(int $idAlbum){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $delete_e = "DELETE FROM EVALUER WHERE idAlbum=$idAlbum";
            $delete_app = "DELETE FROM APPRECIER WHERE idAlbum=$idAlbum";
            $delete_a = "DELETE FROM ALBUM WHERE idAlbum=$idAlbum";

            $file_db->exec($delete_e);
            $file_db->exec($delete_app);
            $file_db->exec($delete_a);
            header("Location: admin-albums.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('supprimer album IMPOSSIBLE');</script>";
        }
    }

    static function db_edit_album(string $idAlbum, string $titre, string $image,
    int $annee, string $chanteur, string $auteur, string $roleParent, array $genres){
        try{
            $file_db = new PDO('sqlite:../data/fixtures.sqlite3');

            $update_a = "UPDATE ALBUM SET titre='$titre', image='$image', annee='$annee',
            musicienBy='$chanteur', musicienParent='$auteur', roleParent='$roleParent'
            WHERE idAlbum=$idAlbum";

            $file_db->exec($update_a);

            $genres_de_data = $file_db->query('SELECT * FROM APPARTENIR WHERE idAlbum='.$idAlbum);
            $genres_de = $genres_de_data->fetchAll(PDO::FETCH_ASSOC);

            $genre_de_strings = [];
            foreach ($genres_de as $genre) {
                $genre_de_strings[] = $genre["nomGenre"];
            }

            $genres_a_supprimer = array_diff($genre_de_strings, $genres);
            $genres_a_ajouter = array_diff($genres, $genre_de_strings);

            var_dump($genres_a_ajouter);
            var_dump($genres_a_supprimer);

            // supprimer instance APPARTENIR
            foreach($genres_a_supprimer as $g){
                $delete_app = "DELETE FROM APPARTENIR WHERE idAlbum=$idAlbum AND nomGenre='$g'";
                $file_db->exec($delete_app);
            }

            // ajouter instance APPARTENIR
            $stmt_app = $file_db->prepare("INSERT INTO APPARTENIR
            (nomGenre,idAlbum)
            VALUES(:nomGenre, :idAlbum)");
            $stmt_app->bindParam(':idAlbum', $idAlbum);
            foreach($genres_a_ajouter as $g){
                $stmt_app->bindParam(':nomGenre', $g);
                $stmt_app->execute();
            }

            header("Location: admin-albums.php");
        }catch(PDOException $ex){
            echo "<script type='text/javascript'>alert('modification album IMPOSSIBLE. Le nom déjà existe');</script>";
        }   
    }
}
?>