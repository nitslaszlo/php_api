
DROP DATABASE IF EXISTS mintaAdatbazis;

CREATE DATABASE mintaAdatbazis
	CHARACTER SET utf8
	COLLATE utf8_hungarian_ci;
-- 
-- Set default database
--
USE mintaAdatbazis;

--
-- Definition for table feedback
--
DROP TABLE IF EXISTS mintaTabla;

CREATE TABLE mintaTabla (
  id int NOT NULL AUTO_INCREMENT,
  mezo1 VARCHAR(50) DEFAULT NULL,
  mezo2 VARCHAR(50) DEFAULT NULL,
  mezo3 VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

INSERT INTO feedback VALUES
(1, 'minta1 adat m1', 'minta1 adat m2', 'minta1 adat m3'),
(2, 'minta2 adat m1', 'minta2 adat m2', 'minta2 adat m3');
