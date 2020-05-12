Raspbian Buster Lite (2020-02-13)
---------------------------------

sudo apt update
sudo apt full-upgrade
sudo apt autoremove
sudo apt autoclean
sudo raspi-config 
(Neustart durchführen!)

sudo apt install ddclient certbot apache2 mariadb-server mariadb-client libapache2-mod-php php-mysql

(Dateien aus dem Repository installieren)

MariaDB-Dump einspielen

MariaDB-Benutzer anlegen

CREATE USER 'master'@'%' IDENTIFIED BY '???';
GRANT USAGE ON `cnc` . * TO 'master'@'%' IDENTIFIED BY '???' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `cnc` . * TO 'master'@'%';
FLUSH PRIVILEGES;
(Eigenes Passwort wählen!)

Hinweis auf desec.io (dedyn.io)

Lets Encrypt-Zertifikat erzeugen

sudo systemctl restart apache2.service