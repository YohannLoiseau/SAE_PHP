<?php
require_once 'src/autoloader.php';
use src\Model\Genre;
use src\Model\Musicien;
use src\Model\Album;

function createAlbum($album){
    $lesGenres = get_genres($album['idAlbum']);
    return new Album(
        $album['idAlbum'],
        $album['titre'],
        $album['image'],
        $album['annee'],
        $album['roleParent'],
        $lesGenres,
        new Musicien($album['musicienBy']),
        new Musicien($album['musicienParent'])
    );
}

function createGenre($genre){
    return new Genre(
        $genre['nomGenre']
    );
}

function createMusicien($musicien){
    return new Album(
        $musicien['nomMusicien']
    );
}

// retourne la BD
function get_bd(){
    $file_db_path = __DIR__ . '/data/fixtures.sqlite3';
    $file_db = new PDO('sqlite:' . $file_db_path);
    $file_db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    return $file_db;
}

function get_genres($ida){
    $file_db = get_bd();
    $requete = 'SELECT * FROM APPARTENIR WHERE idAlbum=:ida';
    $stmt = $file_db->prepare($requete);
    $stmt->bindParam(':ida', $q['ida']);
    $stmt->execute();
    $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $res = [];
    foreach($instances as $i){
        $res[] = new Genre($i['nomGenre']);
    }
    return $res;
}