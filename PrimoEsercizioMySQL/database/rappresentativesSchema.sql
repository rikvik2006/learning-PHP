CREATE DATABASE IF NOT EXISTS BUSSANORICCARDO;

USE BUSSANORICCARDO;

CREATE TABLE Rappresentante (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(50) NOT NULL,
    Cognome VARCHAR(50) NOT NULL,
    UltimoFatturato INT UNSIGNED,
    Regione VARCHAR(255) NOT NULL,
    Provincia VARCHAR(255) NOT NULL,
    PercentualeProvvigione INT UNSIGNED NOT NULL CHECK(PercentualeProvvigione >= 0 AND PercentualeProvvigione <= 100)
)