<?php

namespace src\Model;

class Genre{

    public string $nomGenre;

    public function __construct(string $nomGenre){
        $this->nomGenre = $nomGenre;
    }

    public function lesAlbums(string $nomGenre){
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

    public function __toString() {
        return $this->nomGenre;
    }

    public function render(){
        return "<a href='genres.php?nomGenre=".$this->nomGenre."'>".$this->nomGenre."</a>";
    }
    
}