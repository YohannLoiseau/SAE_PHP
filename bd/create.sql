CREATE TABLE ALBUM (
  PRIMARY KEY (idAlbum),
  idAlbum      int(5) NOT NULL,
  titre        VARCHAR(42),
  image        VARCHAR(42),     -- todo : type
  annee        YEAR,
  idBy int(5) NOT NULL,
  idParent int(5) NOT NULL
);

CREATE TABLE APPARTENIR (
  PRIMARY KEY (idGenre, idAlbum),
  idGenre int(5) NOT NULL,
  idAlbum int(5) NOT NULL
);

CREATE TABLE APPRECIER (
  PRIMARY KEY (idUtilisateur, idAlbum),
  idUtilisateur   int(5) NOT NULL,
  idAlbum         int(5) NOT NULL,
  note            int(1),
  estDansPlaylist boolean
);

CREATE TABLE GENRE (
  PRIMARY KEY (idGenre),
  idGenre  int(5) NOT NULL,
  nomGenre VARCHAR(42)
);

CREATE TABLE MUSICIEN (
  PRIMARY KEY (idMusicien),
  idMusicien  int(5) NOT NULL,
  nomMusicien VARCHAR(42)
);

CREATE TABLE UTILISATEUR (
  PRIMARY KEY (idUtilisateur),
  idUtilisateur  int(5) NOT NULL,
  nomUtilisateur VARCHAR(42)
);

ALTER TABLE ALBUM ADD FOREIGN KEY (idBy) REFERENCES MUSICIEN (idMusicien);
ALTER TABLE ALBUM ADD FOREIGN KEY (idParent) REFERENCES MUSICIEN (idMusicien);

ALTER TABLE APPARTENIR ADD FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum);
ALTER TABLE APPARTENIR ADD FOREIGN KEY (idGenre) REFERENCES GENRE (idGenre);

ALTER TABLE APPRECIER ADD FOREIGN KEY (idAlbum) REFERENCES ALBUM (idAlbum);
ALTER TABLE APPRECIER ADD FOREIGN KEY (idUtilisateur) REFERENCES UTILISATEUR (idUtilisateur);
