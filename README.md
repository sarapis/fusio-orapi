<p align="center">
    <a href="http://api.dc.openreferral.org/socialServicesApp/search.php" target="_blank"><img src="https://pruvn.co/wp-content/uploads/2020/07/Pruvn-logo2-01-2048x2048.png"></a>
</p>

# DC

## Environment

**LAMP stack**
*	php >=7.3
*	MySQL >=5.7 or MariaDB >=10.0
*	Apache >=2.2
*	mod_rewrite
*	git
*	composer


## Installation

**Download repository**
Arrange Apache virtual host - level 1 domain or subdomain

`cd /script_folder`

`git clone https://github.com/sarapis/fusio-orapi .`

**Update software components**
`composer update`

**Set environment values**
`php bin/orapi env`

`mysql -u root -p < orapi_fusio.sql`

`php bin/fusio deploy`



## API

API is available for reading data from several entry points described by Human Service Data API Suite (HSDA) - https://openreferral.readthedocs.io/en/latest/hsda/

Base API entry point is /

Authentication is not required



API docs available at /developer


## API back-end

/console

**Create Fusio engine user**
`php bin/fusio adduser`



## MySQL

During the installation two databases and service user will be created:
* DB `orapi_data` - containing main HSDA dataset
* DB `orapi_fusio` - containing management information for API
