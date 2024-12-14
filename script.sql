-- créer DATABASE "FitTrackDB"
CREATE DATABASE IF NOT EXISTS BlogPressDB;
-- show les bases de données existe dans le server
show DATABASES;
-- utiliser DATABASE "FitTrackDB"
USE BlogPressDB;

--  show TABLEs existe dans la base de données
show TABLEs;

-- Contenu des tables : 
SELECT * from Auteurs ;
SELECT * from Articles ;
SELECT * from Commentaires ;
SELECT * from likes_vues ;




--  création des TABLEAUX : ================================================================================================================================================

CREATE TABLE Auteurs (
    ID_auteur INT PRIMARY KEY AUTO_INCREMENT,
    Nom_auteur VARCHAR(50) NOT NULL,
    Prénom_auteur VARCHAR(50) NOT NULL,
    Email_auteur VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);


CREATE TABLE Articles (
    ID_article INT PRIMARY KEY AUTO_INCREMENT,
    Titre VARCHAR(100) NOT NULL,
    Contenu_article TEXT NOT NULL,
    Categorie VARCHAR(50), -- genre de l'article 
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    ID_auteur INT,
    FOREIGN KEY (ID_auteur) REFERENCES Auteurs(ID_auteur)
);


CREATE TABLE Commentaires (
    ID_Comment INT PRIMARY KEY AUTO_INCREMENT,
    Nom_visiteur VARCHAR(50) NOT NULL,
    email_visiteur VARCHAR(100) NOT NULL,
    contenu_vomment TEXT NOT NULL,
    date_comment DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_article INT,
    FOREIGN KEY (id_article) REFERENCES Articles(ID_article)
);


CREATE TABLE likes_vues (
    ID_L_V INT AUTO_INCREMENT PRIMARY KEY,
    nbr_L_V INT NOT NULL,
    type ENUM('like', 'vue') NOT NULL,
    ID_article INT,
    FOREIGN KEY (ID_article) REFERENCES Articles(ID_article)
);


-- pour supprimer les tableaux : 
-- 1-
SET FOREIGN_KEY_CHECKS = 0;

-- 2-
DROP TABLE likes_vues;
DROP TABLE Commentaires;
DROP TABLE articles;
DROP TABLE auteurs;
