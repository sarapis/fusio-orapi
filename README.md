<p align="center">
    <a href="http://api.dc.openreferral.org/" target="_blank"><img src="https://sarapis.org/wp-content/uploads/2019/05/sarapis-01-2.png" width="300px"></a>
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

1. Deploy to Ubuntu 20.04 VM (Virtual Machine)
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

If the API is the only application running on the VM, you can move the files to server root:
```bash
    sudo chmod 777 -R /var/www/html
    sudo chmod 777 -R /var/www/html/.*
    sudo chown www-data:www-data /var/www/html/ -R
```

And then update Apache config (Assuming your vhost is `\etc\apache2\sites-available\000-default.conf`)
```bash
    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
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

2. Deploy to Azure App Service
To deploy the application to Microsoft Azure (https://azure.com) follow the below steps. The guide assumes you have signed up for Azure account, have an active subscription and basic Linux server administration skills.

*Create App Service* 
* Login to your Azure account and navigate to subscriptions page (https://portal.azure.com/#blade/Microsoft_Azure_Billing/SubscriptionsBlade). Azure gives a Free Trial subscription on signup.
* Once you have confirmed you have an active subscription, create a resource group. A resource group is a container that holds related resources. Resources could be applications e.g App service, databases e.g Azure Database for MySQL etc. Here is a guide on creating a resource group - https://docs.microsoft.com/en-us/azure/azure-resource-manager/management/manage-resource-groups-portal#create-resource-groups
* Navigate to App Services (https://portal.azure.com/#blade/HubsExtension/BrowseResource/resourceType/Microsoft.Web%2Fsites) and click the "+ Add" button
* Fill out the details and click "Review + create" button. Make sure to select the subscription you created ealier and the resource group. Provide the instance name (the name will form part of the application url e.g naming your service orapi, the application will be accessible via https://orapi.azurewebsites.net/). For publish select "Code", Runtime stack - select PHP 7.4, Region - select any (tip: select the region where most of the users using your application are located to reduce latency), for Linux Plan - leave as default. For Sku and size - leave as default.

*Create MySQL Database*

* Navigate to Azure Database for MySQL servers (https://portal.azure.com/#blade/HubsExtension/BrowseResource/resourceType/Microsoft.DBforMySQL%2Fservers) and click "+ Add" button.
* Choose between Single Server and Flexible server (Preview) - if you are testing and/or on a limited budget, we recommened you select Flexible server. For production and high workload environment, go with Single server.
* On Flexible server tab, click "Create"
* Fill out subscription details, resource group, server name, region and workload type. On "Compute + Storage", you can leave it as default, or click "Configure server". Fill out the configuration for your optimal workload. Lastly fill out the username and password. Note these somewhere as you will use them to connect the application to the database.
* Next, click the "Next: Networking". Here, make sure to check the box "Allow public access from any Azure service within Azure to this server" to allow App Service to connect to the database. If you need to connect to the database remotely from another computer, under "Firewall rules", add the computers IP address.
* Lastly click "Review + Create". It will take sometime to provision the database server. Once the provisioning is done, note the database host (something like <YOUR_SERVER_NAME>.mysql.database.azure.com)
* It's important to note, the provisioning does not automatically create a database. To create a database, connect to the server (you can do this from App Service console). To connect to the server from App Service, go to the console and 
```bash
   mysql --host=<YOUR_SERVER_NAME>.mysql.database.azure.com --user=<YOUR_USERNAME_NAME> -p
```
You will be prompted to type your password. Provide the password you used when creating the server. Once connected to server, create a database

```bash
   create database <DATABASE_NAME>;
```
Note somewhere the host (<YOUR_SERVER_NAME>.mysql.database.azure.com), database name (created in the command above), username and password (both supplied when creating the server). 


Once you are done setting up the database, go back to App Service and ssh to the server.

- Install Composer
```bash
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
```

**Download repository**

Navidate to `site`


```bash
    git clone https://github.com/pruvn/orapi .
```

Move contents of the api to `wwwroot`
```bash
    cd wwwroot
    mv ../orapi/* .
```
Make sure all the files (especially the .files - .env, .htaccess etc) are moved to wwwroot

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
1. Make sure all the data in `orapi_fusion.sql` file is imported into the database. It should create two databases: `orapi_fusio` and `orapi_data` You should connect the database you create above to confirm this
2. Update the values in `resources/connections.yaml` file to reflect your database host and credentials.
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
