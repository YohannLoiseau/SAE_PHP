<?php

namespace src\Model;

class Musicien{

    public string $nomMusicien;

    public function __construct(string $nomMusicien){
        $this->nomMusicien = $nomMusicien;
    }

    public function lesAlbums(string $nomMusicien){
        $file_db = new PDO('sqlite:../data/fixtures.sqlite3');
        $stmt = $file_db->prepare('SELECT idAlbum FROM APPARTENIR WHERE nomGenre=:nomGenre');

        $stmt->bindParam(':nomGenre', $nomGenre);
        $stmt->execute();

        $albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $res = array();

        foreach($albums as $a){
            $stmt = $file_db->prepare('SELECT * FROM ALBUM WHERE idAlbum=:idAlbum');

            $stmt->bindParam(':idAlbum', $a['idAlbum']);
            $stmt->execute();

            $objetAlbum = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
}