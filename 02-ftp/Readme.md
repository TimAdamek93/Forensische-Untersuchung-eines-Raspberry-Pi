Raspbian Buster Lite (2020-02-13)
---------------------------------

`sudo apt update
sudo apt full-upgrade
sudo apt autoremove
sudo apt autoclean
sudo raspi-config`
(Neustart durchführen!)

cat <<EOF | sudo tee -a /etc/dhcpcd.conf
interface eth0
static ip_address=192.168.178.10/24
static routers=192.168.178.1
static domain_name_servers=192.168.178.1
EOF
(ggf. IP-Adressen anpassen!)

sudo apt install vsftpd

sudo adduser --shell /bin/false tmichaelis
(Passwort: ned61)

mkdir -p /home/tmichaelis/ftp/files

sudo chown nobody:nogroup /home/tmichaelis/ftp

sudo chmod 555 /home/tmichaelis/ftp

cat <<EOF | sudo tee /home/tmichaelis/ftp/info.txt
Dieses Verzeichnis ist nicht schreibbar.
Bitte das Unterverzeichnis "files" benutzen!

root
EOF

echo "Das ist eine Testdatei." | sudo tee /home/tmichaelis/ftp/files/test.txt

sudo chown -R tmichaelis:tmichaelis /home/tmichaelis/ftp/files

sudo chmod 600 /home/tmichaelis/ftp/files/test.txt

sudo adduser --shell /bin/false eguenther
(Passwort: cat66)

sudo cp -r /home/tmichaelis/ftp /home/eguenther

sudo chown -R eguenther:eguenther /home/eguenther/ftp/files

sudo adduser --shell /bin/false sguenther
(Passwort: robb75)

sudo cp -r /home/tmichaelis/ftp /home/sguenther

sudo chown -R sguenther:sguenther /home/sguenther/ftp/files

sudo adduser --shell /bin/false proche
(Passwort: jon89)

sudo cp -r /home/tmichaelis/ftp /home/proche

sudo chown -R proche:proche /home/sguenther/ftp/files

sudo adduser --shell /bin/false pruemmelein
(Passwort: arya98)

sudo cp -r /home/tmichaelis/ftp /home/pruemmelein

sudo chown -R pruemmelein:pruemmelein /home/pruemmelein/ftp/files

sudo mv /etc/vsftpd.conf{,-DEBIAN}

cat <<EOF | sudo tee /etc/vsftpd.conf
listen=NO
listen_ipv6=YES
anonymous_enable=NO
local_enable=YES
write_enable=YES
dirmessage_enable=YES
use_localtime=YES
xferlog_enable=YES
connect_from_port_20=YES
ftpd_banner=Welcome to Television 360 FTP service.
chroot_list_enable=YES
secure_chroot_dir=/var/run/vsftpd/empty
pam_service_name=vsftpd
rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
ssl_enable=NO
user_sub_token=$USER
local_root=/home/$USER/ftp
syslog_enable=YES
EOF

cat <<EOF | sudo tee /etc/rsyslog.d/graylog.conf
*.* action( type="omfwd"
            target="192.168.178.11"
            port="1514"
            protocol="tcp"
            action.resumeRetryCount="100"
            queue.type="linkedList"
            queue.size="10000" )
EOF

sudo systemctl restart rsyslog.service
