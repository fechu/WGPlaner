#!/usr/bin/env bash

DBPASSWORD=root

echo "Provisioning virtual machine..."

# Make sure the add-apt-repository command is available
echo "Installing tools"
apt-get update -y > /dev/null
apt-get install -y python-software-properties build-essential > /dev/null

# Install php 5.4
echo "Installing PHP 5.4"
add-apt-repository -y ppa:ondrej/php5-oldstable > /dev/null
apt-get update > /dev/null
apt-get install -y php5 > /dev/null

# Install php extensions
echo "Installing PHP 5.4 Extensions"
apt-get install -y curl php5-curl php5-gd php5-mcrypt php5-mysql php5-intl php-apc > /dev/null

echo "Preparing MySQL"
apt-get install -y debconf-utils
debconf-set-selections <<< "mysql-server mysql-server/root_password password $DBPASSWORD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $DBPASSWORD"
echo "Install MySQL"
apt-get install -y mysql-server  

echo "Preparing PhpMyAdmin"
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWORD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" | debconf-set-selections 

echo "Installing PhpMyAdmin"
apt-get install -y phpmyadmin 

# Install apache2
echo "Installing Apache2"
apt-get install -y apache2 > /dev/null
a2enmod rewrite 
if ! [ -L /var/www ]; then
    rm -rf /var/www
    ln -fs /vagrant/public /var/www
fi

echo "Enable PhpMyAdmin"
# Only add the phpmyadmin config if it is not yet present
grep -q 'Include /etc/phpmyadmin/apache.conf' /etc/apache2/apache2.conf
if ! [ $? -eq 0 ]
then
    echo "Include /etc/phpmyadmin/apache.conf" >> /etc/apache2/apache2.conf
fi


# Install vim
echo "Installing vim"
apt-get install -y vim > /dev/null

# Install git
echo "Installing git"
apt-get install -y git > /dev/null

echo "Restart apache2"
service apache2 restart

echo "Finished Provisioning!"
echo "HINT: Edit the error_reporting of PHP in /etc/php5/apache2.php.ini to get errors displaed."
