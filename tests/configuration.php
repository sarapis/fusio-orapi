<?php

if (!getenv('FUSIO_ENV')) {
    $dotenv = new \Symfony\Component\Dotenv\Dotenv();
    $dotenv->load(__DIR__ . '/../.env');
}

return [

    // Whether the implicit flow is allowed. This is mostly needed for 
    // javascript apps
    'fusio_grant_implicit'    => true,

    // Expire times of the different tokens which can be issued
    'fusio_expire_implicit'   => 'PT1H',
    'fusio_expire_app'        => 'P2D',
    'fusio_expire_backend'    => 'PT1H',
    'fusio_expire_consumer'   => 'PT1H',

    // How long can you use the refresh token after the access token was
    // generated
    'fusio_expire_refresh'    => 'P3D',

    // The secret key of a project. It is recommended to change this to another
    // random value. This is used i.e. to encrypt the connection credentials in 
    // the database. NOTE IF YOU CHANGE THE KEY FUSIO CAN NO LONGER READ ANY 
    // DATA WHICH WAS ENCRYPTED BEFORE. BECAUSE OF THAT IT IS RECOMMENDED TO 
    // CHANGE THE KEY ONLY BEFORE THE INSTALLATION
    'fusio_project_key'       => '42eec18ffdbffc9fda6110dcc705d6ce',

    // Indicates whether the PHP sandbox feature is enabled. If yes it is
    // possible to create an action at the backend which contains PHP code. This
    // helps to quickly develop new actions but you should also be aware of the
    // security implications. The code gets checked by a parser which prevents
    // the use of unsafe functions but there is no guarantee that this is
    // complete safe. If you dont need this feature it is recommended to turn it
    // off, then it is not possible to create or update such actions
    'fusio_php_sandbox'       => true,

    // The three-character ISO-4217 currency code which is used to process
    // payments
    'fusio_payment_currency'  => 'EUR',

    // Points to the Fusio provider file which contains specific classes for the
    // system. Please take a look at the provider file for more information
    'fusio_provider'          => __DIR__ . '/provider.php',

    // Settings of the internal mailer. By default we use the internal PHP mail
    // function
    /*
    'fusio_mailer'            => [
        'transport'           => 'smtp',
        'host'                => 'email-smtp.us-east-1.amazonaws.com',
        'port'                => 587,
        'username'            => 'my-username',
        'password'            => 'my-password',
        'encryption'          => 'tls',
    ],
    */

    // Endpoint of the apps repository. All listed apps can be installed by the
    // user at the backend app
    'fusio_marketplace_url'   => 'https://raw.githubusercontent.com/apioo/fusio/master/marketplace.yaml',

    // Location of the automatically generated cron file. Note Fusio writes only
    // to this file if it exists. In order to use the cronjob service you need
    // to create this file with i.e. "touch /etc/cron.d/fusio"
    'fusio_cron_file'         => '/etc/cron.d/fusio',

    // Command to execute the Fusio console which is used in the generated cron
    // file
    'fusio_cron_exec'         => '/usr/bin/php ' . __DIR__ . '/bin/fusio',

    // The url to the psx public folder (i.e. http://127.0.0.1/psx/public or 
    // http://localhost.com)
    'psx_url'                 => 'http://127.0.0.1',

    // To enable clean urls you need to set this to '' this works only in case
    // mod rewrite is activated
    'psx_dispatch'            => 'index.php/',

    // The default timezone
    'psx_timezone'            => 'UTC',

    // Whether PSX runs in debug mode or not. If not error reporting is set to 0
    // Also several caches are used if the debug mode is false
    'psx_debug'               => true,

    // Database parameters which are used for the doctrine DBAL connection
    // http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
    'psx_connection'          => [
        'memory'              => true,
        'driver'              => 'pdo_sqlite',
    ],

    // Folder locations
    'psx_path_cache'          => __DIR__ . '/../cache',
    'psx_path_public'         => __DIR__ . '/../public',
    'psx_path_src'            => __DIR__ . '/../src',

    // Supported writers
    'psx_supported_writer'    => [
        \PSX\Data\Writer\Json::class,
        \PSX\Data\Writer\Jsonp::class,
        \PSX\Data\Writer\Jsonx::class,
    ],

    // Global middleware which are applied before and after every request. Must
    // bei either a classname, closure or PSX\Dispatch\FilterInterface instance
    //'psx_filter_pre'          => [],
    //'psx_filter_post'         => [],

    // A closure which returns a doctrine cache implementation. If null the
    // filesystem cache is used
    //'psx_cache_factory'       => null,

    // A closure which returns a monolog handler implementation. If null the
    // system handler is used
    //'psx_logger_factory'      => null,

    // Class name of the error controller
    //'psx_error_controller'    => null,

    // If you only want to change the appearance of the error page you can 
    // specify a custom template
    //'psx_error_template'      => null,

];
