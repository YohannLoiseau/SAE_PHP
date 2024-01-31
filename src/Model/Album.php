<?php

namespace src\Model;

class Album{

    public int $idAlbum;

    public string $titre;

    public ?string $image;

    public ?int $annee;

    public ?string $roleParent;

    public ?array $genres;

    public Musicien $chanteur;

    public Musicien $auteur;

    public function __construct(int $idAlbum, string $titre, ?string $image,
    ?int $annee, ?string $roleParent, ?array $genres, Musicien $chanteur, Musicien $auteur){
        $this->idAlbum = $idAlbum;
        $this->titre = $titre;
        $this->image = $image ?? "default.jpg";
        $this->annee = $annee;
        $this->roleParent = $roleParent;
        $this->genres = $genres;
        $this->chanteur = $chanteur;
        $this->auteur = $auteur;
    }

    public function render(){
        $html = "<img src='../data/images/".$this->image."'/><ul>";
        $html = $html.$this->titre."<li>".$this->chanteur->nomMusicien."</li></ul>";
        echo $html;
    }
    
}