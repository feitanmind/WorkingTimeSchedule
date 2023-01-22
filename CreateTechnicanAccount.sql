

CREATE USER IF NOT EXISTS 'technican'@'%' IDENTIFIED BY 'N4c1nhc3t';
GRANT ALL PRIVILEGES ON *.* TO 'technican'@'%' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON `%`.* TO 'technican'@'%' IDENTIFIED BY 'N4c1nhc3t' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO 'technican'@'%' WITH GRANT OPTION;
#Tworzenie roli
CREATE ROLE 'admin'WITH ADMIN 'technican'@'%';
CREATE ROLE 'manager' WITH ADMIN 'admin';
CREATE ROLE 'worker' WITH ADMIN 'manager';
#przyznawanie roli
GRANT ALL PRIVILEGES ON app_commercial.* TO 'admin' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO 'admin' WITH GRANT OPTION;
GRANT SELECT,INSERT,UPDATE,DELETE ON app_commercial.* TO 'manager' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO 'manager' WITH GRANT OPTION;
GRANT SELECT, UPDATE  ON app_commercial.* TO 'worker';
#Tworzenie konta admin
CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 'niemahasla';
GRANT ALL PRIVILEGES ON `%`.* TO 'admin'@'localhost' IDENTIFIED BY 'N4c1nhc3t' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;

GRANT 'admin' TO 'admin'@'localhost';
FLUSH PRIVILEGES;
