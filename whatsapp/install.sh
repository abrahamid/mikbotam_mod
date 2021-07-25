curl -fsSL https://deb.nodesource.com/setup_14.x | sudo -E bash -
apt-get install -y nodejs
apt install -y apache2 php php-curl libnss3-dev libatk1.0-dev libcups2-dev libxkbcommon-dev libgtk-3-dev mysql-server
# cara membuat user baru
## CREATE USER 'pmauser'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password_here';
## GRANT ALL PRIVILEGES ON *.* TO 'pmauser'@'localhost' WITH GRANT OPTION;
# cara mengganti password database
## ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'reni1234';
## FLUSH PRIVILEGES;