Debian GNU/Linux 10 (Buster)
----------------------------

sudo apt update
sudo apt full-upgrade
sudo apt autoremove
sudo apt autoclean

sudo sed -i 's/dhcp/static/' /etc/network/interfaces
(Achtung: hierbei wird davon ausgegangen, dass nur eine Netzwerkkarte verbaut ist!)

cat <<EOF | tee -a /etc/network/interfaces
  address 192.168.178.11/24
  gateway 192.168.178.1
  dns-nameservers 192.168.178.1
EOF
(Neustart durchführen!?)

sudo apt-get install apt-transport-https ca-certificates curl gnupg2 software-properties-common

curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -

echo "deb [arch=amd64] https://download.docker.com/linux/debian $( lsb_release -cs ) stable" | sudo tee /etc/apt/sources.list.d/docker.list

sudo apt update

sudo apt install docker-ce docker-ce-cli containerd.io docker-compose

sudo usermod -aG docker "${USER}"

exit
(und neu anmelden, damit die neue Gruppenzugehörigkeit greift)

mkdir -p Docker/graylog

cd Docker/graylog

cat <<EOF > docker-compose.yml
version: '3'

services:
  # MongoDB: https://hub.docker.com/_/mongo/
  mongo:
    image: mongo:3
    volumes:
      - mongo_data:/data/db
    restart: unless-stopped
    networks:
      - graylog
  # Elasticsearch: https://www.elastic.co/guide/en/elasticsearch/reference/6.x/docker.html
  elasticsearch:
    image: docker.elastic.co/elasticsearch/elasticsearch-oss:6.8.5
    volumes:
      - es_data:/usr/share/elasticsearch/data
    restart: unless-stopped
    environment:
      - http.host=0.0.0.0
      - transport.host=localhost
      - network.host=0.0.0.0
      - "ES_JAVA_OPTS=-Xms512m -Xmx512m"
    ulimits:
      memlock:
        soft: -1
        hard: -1
    deploy:
      resources:
        limits:
          memory: 1g
    networks:
      - graylog
  # Graylog: https://hub.docker.com/r/graylog/graylog/
  graylog:
    image: graylog/graylog:3.2
    volumes:
      - graylog_journal:/usr/share/graylog/data/journal
    restart: unless-stopped
    environment:
      # siehe: https://docs.graylog.org/en/3.2/pages/getting_started/configure.html#general-properties
      - GRAYLOG_PASSWORD_SECRET=
      - GRAYLOG_ROOT_PASSWORD_SHA2=
	  - GRAYLOG_ROOT_TIMEZONE=Europe/Berlin
      #- GRAYLOG_HTTP_EXTERNAL_URI=http://192.168.178.21:9000/
      - GRAYLOG_HTTP_EXTERNAL_URI=http://localhost.localdomain:9000/
    networks:
      - graylog
    depends_on:
      - mongo
      - elasticsearch
    ports:
      # Graylog web interface and REST API
      - 9000:9000
      # Syslog TCP
      - 1514:1514
      # Syslog UDP
      - 1514:1514/udp
      # GELF TCP
      #- 12201:12201
      # GELF UDP
      #- 12201:12201/udp
networks:
  graylog:
    driver: bridge
# Volumes for persisting data, see https://docs.docker.com/engine/admin/volumes/volumes/
volumes:
  mongo_data:
    driver: local
  es_data:
    driver: local
  graylog_journal:
    driver: local
EOF

docker-compose up -d

docker-compose logs -f
(kann mit <strg> + <c> beendet werden)