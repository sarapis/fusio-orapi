<p align="center">
    <a href="http://api.dc.openreferral.org/socialServicesApp/search.php" target="_blank"><img src="https://pruvn.co/wp-content/uploads/2020/07/Pruvn-logo2-01-2048x2048.png" width="200px" height="200px"></a>
</p>

# DC

## Environment

**LAMP stack (Reccommended: Ubuntu 20, PHP 7.4, MySQL 5.7)**
*	php >=7.3
*	MySQL >=5.7 or MariaDB >=10.0
*	Apache >=2.2
*	mod_rewrite
*	git
*	composer

## Installation

**Install LAMP**
On a afresh Ubuntu 20.0 installation:
- Install Apache
`sudo apt-get install apache2`

Ensure port 80 and 443 (If you using a domain with SSL) are open. If using AWS, configure this in security group. Alternatively, you can run:
`sudo ufw allow in "Apache Full"`

- Install MySQL
`sudo apt-get install mysql-server`

- Install PHP (7.4 in this case. 7.3 will work just fine)
`sudo apt -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt -y install php7.4`

- Install PHP modules
`sudo apt-get install -y php7.4-{bcmath,bz2,intl,gd,mbstring,mysql,zip,common,json,curl,cli,xml,soap,opcache,readline,fpm,dev}`

- Install Composer
`php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');" `

- Enable Apache mod_rewrite
`sudo a2enmod rewrite
sudo systemctl restart apache2`



**Download repository**

Arrange apache virtual host - level 1 domain or subdomain

`cd /script_folder`

`git clone https://github.com/pruvn/orapi .`


**Update dependencies**

`composer update`


**Set environment values**

`php bin/orapi env`

Mysql should be launched as user with databases and users creation rights

`mysql -u root -p < orapi_fusio.sql`

`php bin/fusio deploy`

**Test API**

Open following entry point in browser - you will see 1st page with 50 of 1075 services descriptions

`/services`


## API

API is available for reading data from several entry points described by Human Service Data API Suite (HSDA) - https://openreferral.readthedocs.io/en/latest/hsda/

Base API entry point 

`/`

Authentication is not required



API docs available at 

`/developer`


## API back-end

`/console`

**Create Fusio engine user**

`php bin/fusio adduser`



## MySQL

During the installation two databases and service user will be created:
* DB `orapi_data` - containing main HSDA dataset
* DB `orapi_fusio` - containing management information for API
