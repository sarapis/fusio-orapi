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
This guide provides two installation option: a VM (with Ubuntu 20.04 or any other Linux Distro) and Azure App Service

** Deploy to Ubuntu 20.04 VM**
- Install Apache
```bash
    sudo apt-get install apache2
```

Ensure port 80 and 443 (If you using a domain with SSL) are open. If using AWS, configure this in security group. Alternatively, you can run:
```bash
    sudo ufw allow in "Apache Full"
```

- Install MySQL
```bash
    sudo apt-get install mysql-server
```

- Install PHP (7.4 in this case. 7.3 will work just fine)
```bash
    sudo apt -y install software-properties-common
    sudo add-apt-repository ppa:ondrej/php
    sudo apt-get update
    sudo apt -y install php7.4
```

- Install PHP modules
```bash
    sudo apt-get install -y php7.4-{bcmath,bz2,intl,gd,mbstring,mysql,zip,common,json,curl,cli,xml,soap,opcache,readline,fpm,dev}
```

- Install Composer
```bash
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
```

- Enable Apache mod_rewrite
```bash
    sudo a2enmod rewrite
    sudo systemctl restart apache2
```



**Download repository**

Arrange apache virtual host - level 1 domain or subdomain

```bash
    cd /script_folder
```

```bash
    git clone https://github.com/pruvn/orapi .
```


**Update dependencies**

```bash
    composer update
```


**Set environment values**

```bash
    php bin/orapi env
```

Mysql should be launched as user with databases and users creation rights

```bash
    mysql -u root -p < orapi_fusio.sql
```
Note: 
1. Make sure all the data in `orapi_fusion.sql` file is imported into the database. It should create two databases: `orapi_fusio` and `orapi_data` 
2. If you are using a remote database make sure to update the values in `resources/connections.yaml` file to reflect your database host and credentials.
3. If your database password contains `$` , you may get a connection error. To sort out this, enclose your database password within single quotation marks instead of double quotation marks in `.env` file

```bash
    php bin/fusio deploy
```

**Test API**

Open following entry point in browser - you will see 1st page with 50 of 1075 services descriptions

```bash
    /services
```


## API

API is available for reading data from several entry points described by Human Service Data API Suite (HSDA) - https://openreferral.readthedocs.io/en/latest/hsda/

Base API entry point 

```bash
    /
```

Authentication is not required



API docs available at 

```bash
    /developer
```


## API back-end

```bash
    /console
```

**Create Fusio engine user**

```bash
    php bin/fusio adduser
```



## MySQL

During the installation two databases and service user will be created:
* DB `orapi_data` - containing main HSDA dataset
* DB `orapi_fusio` - containing management information for API
