CREATE TABLE IF NOT EXISTS MUSICIEN (
    nomMusicien VARCHAR(42) NOT NULL PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS GENRE (
    nomGenre VARCHAR(42) NOT NULL PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS UTILISATEUR (
    idUtilisateur  int(5) NOT NULL PRIMARY KEY,
    nomUtilisateur VARCHAR(42),
    mdp VARCHAR(42)
);

CREATE TABLE IF NOT EXISTS ALBUM (
    idAlbum      int(5) NOT NULL PRIMARY KEY,
    titre        VARCHAR(42),
    image        VARCHAR(100),
    annee        YEAR,
    musicienBy VARCHAR(42) NOT NULL,
    musicienParent VARCHAR(42) NOT NULL,
    roleParent VARCHAR(42),
    FOREIGN KEY (musicienBy) REFERENCES MUSICIEN (nomMusicien),
    FOREIGN KEY (musicienParent) REFERENCES MUSICIEN (nomMusicien)
);

CREATE TABLE IF NOT EXISTS APPARTENIR (
    nomGenre VARCHAR(42) NOT NULL,
    idAlbum int(5) NOT NULL,
    PRIMARY KEY (nomGenre, idAlbum),
    FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
    FOREIGN KEY (nomGenre) REFERENCES GENRE (nomGenre)
);

CREATE TABLE IF NOT EXISTS APPRECIER (
    idUtilisateur   int(5) NOT NULL,
    idAlbum         int(5) NOT NULL,
    note            int(1),
    estDansPlaylist boolean,
    PRIMARY KEY (idUtilisateur, idAlbum),
    FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum),
    FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR (idUtilisateur)
);