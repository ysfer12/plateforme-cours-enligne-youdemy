create database Youdemy;
create table Role (
	role_id int auto_increment primary key,
    titre varchar(30)
);

create table Utilisateurs(
	id int auto_increment primary key,
    prenom varchar(30),
	nom varchar(30),
    email varchar(200),
    mot_de_passe varchar(200),
    role_id int,
    statut varchar(15) default 'inactif',
    dateAjout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dateSuppression TIMESTAMP NULL,
    foreign key  (role_id) references Role(role_id)
);

CREATE TABLE Category (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(200)
);

CREATE TABLE Tag (
    tag_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Cours (
    cours_id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(100) NOT NULL,
    description TEXT,
    typeContenu VARCHAR(300),
    lienContenu VARCHAR(300),
    enseignat_id INT,
    category_id INT,
    dateAjout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enseignat_id) REFERENCES Utilisateurs(id),
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

CREATE TABLE Cours_Tags (
    cours_id INT,
    tag_id INT,
    PRIMARY KEY (cours_id, tag_id),
    FOREIGN KEY (cours_id) REFERENCES Cours(cours_id),
    FOREIGN KEY (tag_id) REFERENCES Tag(tag_id)
);

CREATE TABLE Inscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    etudiant_id INT,
    cours_id INT,
    dateInscription  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES Utilisateurs(id),
    FOREIGN KEY (cours_id) REFERENCES Cours(cours_id)
);

