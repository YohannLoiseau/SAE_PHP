<!-- Page pour charger la base de données -->
<?php
require_once 'Autoloader/autoloader.php';
require_once 'vendor/autoload.php';
// use Classes\;
use Symfony\Component\Yaml\Yaml;

date_default_timezone_set('Europe/Paris');
try{
    // le fic de BD s'appelle fixtures.sqlite3
    $file_db = new PDO('sqlite:fixtures.sqlite3');
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

    // table MUSICIEN
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS MUSICIEN (
            nomMusicien VARCHAR(42) NOT NULL PRIMARY KEY
          )"
    );

    // table GENRE
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS GENRE (
            nomGenre VARCHAR(42) NOT NULL PRIMARY KEY
          )"
    );

    // table UTILISATEUR
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS UTILISATEUR (
            idUtilisateur  int(5) NOT NULL PRIMARY KEY,
            nomUtilisateur VARCHAR(42),
            mdp VARCHAR(42)
          )"
    );

    // table ALBUM
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS ALBUM (
            idAlbum      int(5) NOT NULL PRIMARY KEY,
            titre        VARCHAR(42),
            image        VARCHAR(100),
            annee        YEAR,
            musicienBy VARCHAR(42) NOT NULL,
            musicienParent VARCHAR(42) NOT NULL,
            roleParent VARCHAR(42),
            FOREIGN KEY (musicienBy) REFERENCES MUSICIEN (nomMusicien),
            FOREIGN KEY (musicienParent) REFERENCES MUSICIEN (nomMusicien)
          )"
    );

    // table APPARTENIR
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS APPARTENIR (
            nomGenre VARCHAR(42) NOT NULL,
            idAlbum int(5) NOT NULL,
            PRIMARY KEY (nomGenre, idAlbum),
            FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
            FOREIGN KEY (nomGenre) REFERENCES GENRE (nomGenre)
          )"
    );

    // table APPRECIER
    $file_db->exec(
        "CREATE TABLE IF NOT EXISTS APPRECIER (
            idUtilisateur   int(5) NOT NULL,
            idAlbum         int(5) NOT NULL,
            note            int(1),
            estDansPlaylist boolean,
            PRIMARY KEY (idUtilisateur, idAlbum),
            FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
            FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR (idUtilisateur)
          )"
    );

    echo "Création réussie !";

    $yamlData = Yaml::parseFile('fixtures/extrait.yml');

    foreach($yamlData as $instance){

        // PREPARE
        $stmt_musicien = $file_db->prepare("INSERT OR IGNORE INTO MUSICIEN(nomMusicien)
        VALUES (:nomMusicien)");

        $stmt_genre = $file_db->prepare("INSERT OR IGNORE INTO GENRE(nomGenre)
        VALUES (:nomGenre)");

        $stmt_album = $file_db->prepare("INSERT INTO ALBUM(idAlbum,titre,image,annee,musicienBy,musicienParent,roleParent)
        VALUES (:idAlbum,:titre,:image,:annee,:musicienBy,:musicienParent,:roleParent)");

        $stmt_appartenir = $file_db->prepare("INSERT INTO APPARTENIR(nomGenre,idAlbum)
        VALUES (:nomGenre,:idAlbum)");

        // BINDPARAM & EXECUTE
        $stmt_musicien->bindParam(':nomMusicien', $instance['by']);
        $stmt_musicien->execute();

        // $stmt_musicien->bindParam(':nomMusicien', $instance['parent']);
        // $stmt_musicien->execute();

        $stmt_album->bindParam(':musicienBy', $instance['by']);
        $stmt_album->bindParam(':idAlbum', $instance['entryId']);
        $stmt_album->bindParam(':image', $instance['img']);
        $stmt_album->bindParam(':annee', $instance['releaseYear']);
        $stmt_album->bindParam(':titre', $instance['title']);
        if(preg_match('/^(.*?)\s*\((.*?)\)$/', $instance['parent'], $decoupe)){
            $nom = ''.trim($decoupe[1]);
            $role = ''.trim($decoupe[2]);
            $stmt_musicien->bindParam(':nomMusicien', $nom);
            $stmt_album->bindParam(':musicienParent', $nom);
            $stmt_album->bindParam(':roleParent', $role);
        }else{
            $stmt_musicien->bindParam(':nomMusicien', $instance['parent']);
            $stmt_album->bindParam(':musicienParent', $instance['parent']);
        }

        $stmt_musicien->execute();
        $stmt_album->execute();

        foreach($instance['genre'] as $g){
            $stmt_genre->bindParam(':nomGenre', $g);
            $stmt_genre->execute();

            $stmt_appartenir->bindParam(':nomGenre', $g);
            $stmt_appartenir->bindParam(':idAlbum', $instance['entryId']);
            $stmt_appartenir->execute();
        }
    }

    echo "Insertion réussie !";
    $file_db=null;

}catch(PDOException $ex){
    echo $ex->getMessage();
}    
?>
