USE BUSSANORICCARDO;

SELECT Provincia FROM Rappresentante GROUP BY Provincia

SELECT * FROM Rappresentante WHERE UltimoFatturato > 1000 AND Regione = 'Toscana';

UPDATE Rappresentante 
SET PercentualeProvvigione = PercentualeProvvigione + 2 
WHERE UltimoFatturato > 1000 
AND Regione = 'Toscana'
AND PercentualeProvvigione <= 98;