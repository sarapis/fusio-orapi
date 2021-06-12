
Business logic
==============

In case your API is not a simple CRUD app you probably need to execute some more 
complex business logic. This chapter shows options how you can organize this 
business logic for simple reuse.

In general an action should not contain any business logic it should simply 
forward the data to the fitting library or service. It is equivalent to a 
controller in a classical framework environment. If an action contains too many 
lines of code or you copy specific code snippets into different actions it is a
smell to extract this logic into an external service. Inside an action you can
then reuse this external service.

Fusio is designed to help you write framework independent code. That means that
all services which you develop are complete free of Fusio specific code so you 
can simply reuse those components in another context.

DI Container
------------

Fusio uses a DI container to manage all internal services. In Fusio all services
are defined in a simple PHP class so there is no ``yaml`` configuration. To
define a custom service you need to create a container class i.e. at
``src/Dependency/Container.php``. This class then needs to extend the Fusio
container:

.. code-block:: php
    
    <?php
    
    namespace App\Dependency;
    
    use Fusio\Impl\Dependency\Container as FusioContainer;
    
    class Container extends FusioContainer
    {
        public function getMyService(): MyServiceInterface
        {
            return new MyService(
                $this->get('connector')->getConnection('System'),
                $this->get('engine_dispatcher')
            );
        }
    }

In this example we simply define a new service. To use this container you need
to create a new instance of this container at the ``container.php`` file.

.. code-block:: php
    
    <?php

    $container = new Fusio\Impl\Dependency\Container();
    $container->setParameter('config.file', __DIR__ . '/configuration.php');
    
    return $container;

Then you can use the autowire feature of the DI container to receive the
dependencies through constructor injection. In the following we create an action
which automatically receives the ``MyServiceInterface`` based on the
type-hint. It is important that the type-hint and the return type of the method
at the DI container match.

.. code-block:: php
    
    <?php
    
    namespace App\Action\Page;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;
    
    class MyAction extends ActionAbstract
    {
        private $myService;
    
        public function __construct(MyServiceInterface $myService)
        {
            $this->myService = $myService;
        }
    
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            $body = [
                'result' => $this->myService->doStuff();
            ];
    
            return $this->response->build(200, [], $body);
        }
    }

We have created also a sample project showing all best practices of Fusio at:
https://github.com/apioo/fusio-sample-cms

Library
-------

The simplest solution is to move business logic into a separate PHP class. This
class can be autoloaded through composer. You can place this class either 
directly into the ``src/`` folder or develop a custom PHP package and require
this package through composer.

In general a library should work with a specific connection. The following 
example shows a simple custom logger implementation which you could use in 
different actions.

.. code-block:: php

    <?php

    $connection = $connector->get('Mysql-1');
    
    $myLogger = new MyLogger($connection);
    $myLogger->log('A new log entry');

A simple implementation of the logger could look like:

.. code-block:: php

    <?php

    namespace Acme\MyLib;

    use Doctrine\DBAL\Connection;

    class MyLogger
    {
        /**
         * @var \Doctrine\DBAL\Connection
         */
        protected $connection;

        public function __construct(Connection $connection)
        {
            $this->connection = $connection;
        }
    
        public function log($message)
        {
            $this->connection->insert('my_table', [
                'message' => $message,
            ]);
        }
    }

Microservice
------------

If your business logic is more complex or has specific performance requirements
you could also develop it as an external microservice. This has the advantage
that service is completely decoupled from your app and it is also possible to
use a complete different language. Usually you can talk to the micro service 
through HTTP. But it would be also possible to use a different protocol i.e. an 
AMQP connection to use a message queue.

.. code-block:: php

    <?php

    $client = $connector->get('Http-1');
    
    $myClient = new MyClient($client);
    $myClient->send(['foo' => 'bar']);

A simple client implementation could look like:

.. code-block:: php

    <?php

    namespace Acme\MyLib;

    use GuzzleHttp\Client;

    class MyClient
    {
        /**
         * @var \GuzzleHttp\Client
         */
        protected $client;

        public function __construct(Client $client)
        {
            $this->client = $client;
        }
    
        public function send($data)
        {
            $this->client->post('http://foo.bar/my_service', [
                'json' => $data
            ]);
        }
    }
