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

INSERT INTO Auteurs (Nom_auteur, Prénom_auteur, Email_auteur, Password)
VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'password1'),
('Martin', 'Sophie', 'sophie.martin@example.com', 'password2'),
('Lambert', 'Paul', 'paul.lambert@example.com', 'password3'),
('Durand', 'Alice', 'alice.durand@example.com', 'password4'),
('Lemoine', 'Claire', 'claire.lemoine@example.com', 'password5');


-- requete pour afficher les information des articles d'un auteur :
SELECT 
    Articles.ID_article, 
    Articles.Titre,
    Articles.Contenu_article,
    Articles.Categorie,
    DATE_FORMAT(Articles.date_creation, '%d-%m-%Y') AS date_creation,
    Auteurs.Nom_auteur,
    Auteurs.Prénom_auteur,
    (SELECT SUM(nbr_L_V) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'like') AS nbr_likes,
    (SELECT SUM(nbr_L_V) FROM likes_vues WHERE likes_vues.ID_article = Articles.ID_article AND likes_vues.type = 'vue') AS nbr_vues,
    (SELECT COUNT(*) FROM Commentaires WHERE Commentaires.id_article = Articles.ID_article) AS nbr_commentaires
FROM 
    Articles
JOIN 
    Auteurs ON Articles.ID_auteur = Auteurs.ID_auteur
WHERE 
    Auteurs.ID_auteur = 6 ;


UPDATE Articles
SET Titre = "test update", Contenu_article = "contenue modifier de l'article test tes testest test ", Categorie = "categoryUpdate"
WHERE ID_article = 9 ;


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

-- insert into commentaires : COMMENT
INSERT INTO commentaires(Nom_visiteur, email_visiteur, contenu_vomment, id_article) VALUES ('visiteur test', 'visiteur@gmail.com', 'commentaire test', 9);


SELECT * FROM commentaires WHERE id_article = 9;

CREATE TABLE likes_vues (
    ID_L_V INT AUTO_INCREMENT PRIMARY KEY,
    nbr_L_V INT NOT NULL,
    type ENUM('like', 'vue') NOT NULL,
    ID_article INT,
    FOREIGN KEY (ID_article) REFERENCES Articles(ID_article)
);


UPDATE likes_vues SET nbr_L_V = nbr_L_V + 1 WHERE ID_article = 9 and type = "like";

insert into likes_vues (nbr_L_V, type, `ID_article`) VALUES(2, 'like', 9);

select count(*) from likes_vues WHERE `ID_article` = 2 ;

-- pour supprimer les tableaux : 
-- 1-
SET FOREIGN_KEY_CHECKS = 0;

-- 2-
DROP TABLE likes_vues;
DROP TABLE Commentaires;
DROP TABLE articles;
DROP TABLE auteurs;
