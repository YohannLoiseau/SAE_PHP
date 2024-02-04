<?php

namespace src\Model;
use \PDO;

class Album{

    public int $idAlbum;

    public string $titre;

    public ?string $image;

    public ?int $annee;

    public ?string $roleParent;

    public Musicien $chanteur;

    public Musicien $auteur;

    public function __construct(int $idAlbum, string $titre, ?string $image,
    ?int $annee, ?string $roleParent, Musicien $chanteur, Musicien $auteur){
        $this->idAlbum = $idAlbum;
        $this->titre = $titre;
        $this->image = $image ?? "default.jpg";
        $this->annee = $annee;
        $this->roleParent = $roleParent;
        $this->chanteur = $chanteur;
        $this->auteur = $auteur;
    }

    public function genres(){
        $currentDir = dirname(__FILE__);
        $databasePath = $currentDir . '/../../data/fixtures.sqlite3';
        $file_db = new PDO('sqlite:' . $databasePath);
        $stmt = $file_db->prepare('SELECT * FROM APPARTENIR where idAlbum=:idAlbum');

        $stmt->bindParam(':idAlbum', $this->idAlbum);
        $stmt->execute();

        $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $genres = array();

        foreach($instances as $i){
            $genres[] = new Genre($i['nomGenre']);
        }

        return $genres;
    }

    public function render(){
        $html = "<li><a href='albums.php?idAlbum=$this->idAlbum'><img src='../data/images/".$this->image."'/><p>";
        $html = $html.$this->titre."<br/>".$this->chanteur->nomMusicien."</p></a></li>";
        return $html;
    }

    public function __toString() {
        return $this->titre;
    } 
    
}