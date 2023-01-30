sudo apt-get update
sudo apt-get install apache2 -y
sudo systemctl start apache2
sudo systemctl enable apache2
sudo apt-get install mariadb-server mariadb-client
sudo systemctl start mariadb
sudo systemctl enable mariadb
(sleep 2; echo ""; sleep 2;echo "n";sleep 2; echo "y"; sleep 2; echo -e "niemahasla";sleep 2;  echo -e "niemahasla"; sleep 2; echo "y";sleep 2; echo "y";sleep 2;  echo "y"; sleep 2; echo "y") | sudo mysql_secure_installation;
sudo touch /etc/mysql/mysqlpassword.cnf
sudo chmod 700 /etc/mysql/mysqlpassword.cnf
sudo echo '[mysqldump]' | sudo tee -a /etc/mysql/mysqlpassword.cnf
sudo echo 'password="niemahasla"' | sudo tee -a /etc/mysql/mysqlpassword.cnf
sudo apt-get install apt-transport-https  ca-certificates lsb-release software-properties-common gnupg2 -y
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/sury-php.list
curl -fsSL  https://packages.sury.org/php/apt.gpg| sudo gpg --dearmor -o /etc/apt/trusted.gpg.d/sury-keyring.gpg
sudo chmod -R 777 /var/www/WorkingTimeSchedule/public_html/app/style/img/avatars
sudo apt-get update
sudo apt-get install php8.1 -y
sudo apt-get install php8.1-{bcmath,fpm,xml,mysql,zip,intl,ldap,gd,cli,bz2,curl,mbstring,pgsql,opcache,soap,cgi} -y
sudo cp -r WorkingTimeSchedule /var/www/
sudo cp /var/www/WorkingTimeSchedule/conf/WorkingTimeSchedule.conf /etc/apache2/sites-available/WorkingTimeSchedule.conf
sudo a2dissite 000-default.conf
sudo a2ensite WorkingTimeSchedule.conf
sudo systemctl restart apache2
sudo mysql --defaults-file=/path-to-file/.my.cnf –u root app_commercial < new_database_for_wts.sql
