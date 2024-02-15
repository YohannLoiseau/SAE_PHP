<!-- Page pour charger la base de données -->
<?php
require_once 'src/autoloader.php';
require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

date_default_timezone_set('Europe/Paris');

$pdo = new PDO('sqlite:' . SQLITE_DB);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

switch ($argv[2]) {
    case 'create-database':
        echo '→ Go create database "fixtures.sqlite3"' . PHP_EOL;
        shell_exec('sqlite3 ' . SQLITE_DB);
        break;

    case 'create-table':
        echo '→ Go create all tables' . PHP_EOL;
        $query =<<<EOF
            CREATE TABLE IF NOT EXISTS MUSICIEN (
                nomMusicien TEXT NOT NULL PRIMARY KEY
            );
            CREATE TABLE IF NOT EXISTS GENRE (
                nomGenre TEXT NOT NULL PRIMARY KEY
            );
            CREATE TABLE IF NOT EXISTS UTILISATEUR (
                idUtilisateur INT NOT NULL PRIMARY KEY,
                nomUtilisateur TEXT NOT NULL,
                mdp TEXT NOT NULL,
                estAdmin BOOLEAN NOT NULL
            );
            CREATE TABLE IF NOT EXISTS ALBUM (
                idAlbum INT NOT NULL PRIMARY KEY,
                titre TEXT NOT NULL,
                image TEXT,
                annee INT NOT NULL,
                musicienBy TEXT NOT NULL,
                musicienParent TEXT NOT NULL,
                roleParent TEXT
            );
            CREATE TABLE IF NOT EXISTS APPARTENIR (
                nomGenre TEXT NOT NULL,
                idAlbum INT NOT NULL,
                FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
                FOREIGN KEY (nomGenre) REFERENCES GENRE (nomGenre),
                PRIMARY KEY (nomGenre, idAlbum)
            );
            CREATE TABLE IF NOT EXISTS APPRECIER (
                idUtilisateur INT NOT NULL,
                idAlbum INT NOT NULL,
                FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
                FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR (idUtilisateur),
                PRIMARY KEY (idUtilisateur, idAlbum)
            );
            CREATE TABLE IF NOT EXISTS EVALUER (
                idUtilisateur INT NOT NULL,
                idAlbum INT NOT NULL,
                note INT NOT NULL,
                FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
                FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR (idUtilisateur),
                PRIMARY KEY (idUtilisateur, idAlbum)
            );
        EOF;
        break;

    case 'delete-table':
        echo '→ Go delete all tables' . PHP_EOL;
        $query =<<<EOF
            DROP TABLE IF EXISTS EVALUER;
            DROP TABLE IF EXISTS APPRECIER;
            DROP TABLE IF EXISTS APPARTENIR;
            DROP TABLE IF EXISTS ALBUM;
            DROP TABLE IF EXISTS UTILISATEUR;
            DROP TABLE IF EXISTS GENRE;
            DROP TABLE IF EXISTS MUSICIEN;
        EOF;
        break;

    case 'load-data':
        echo '→ Go load data to all tables' . PHP_EOL;
        $yamlData = Yaml::parseFile('data/extrait.yml');
        $query = null;

        // PREPARE
        $stmt_musicien = $pdo->prepare("INSERT OR IGNORE INTO MUSICIEN(nomMusicien)
        VALUES (:nomMusicien)");

        $stmt_genre = $pdo->prepare("INSERT OR IGNORE INTO GENRE(nomGenre)
        VALUES (:nomGenre)");

        $stmt_album = $pdo->prepare("INSERT INTO ALBUM(idAlbum,titre,image,annee,musicienBy,musicienParent,roleParent)
        VALUES (:idAlbum,:titre,:image,:annee,:musicienBy,:musicienParent,:roleParent)");

        $stmt_appartenir = $pdo->prepare("INSERT INTO APPARTENIR(nomGenre,idAlbum)
        VALUES (:nomGenre,:idAlbum)");

        foreach($yamlData as $instance){
            // BINDPARAM & EXECUTE
            $stmt_musicien->bindParam(':nomMusicien', $instance['by']);
            $stmt_musicien->execute();

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

        $pdo->exec("INSERT INTO UTILISATEUR(idUtilisateur, nomUtilisateur, mdp, estAdmin)
        VALUES (1, 'toto', 'toto', TRUE),
        (2, 'aboo', 'aboo', FALSE)");
        break;

    default:
        echo 'No action defined 🙀'.PHP_EOL;
        break;
}

if ($query) {
    try {
        $pdo->exec($query);
    } catch (PDOException $e) {
        var_dump($e->getMessage());
    }
}