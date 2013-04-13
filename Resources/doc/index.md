# CassandraBundle

## About ##

This bundle integrates [phpcassa](https://github.com/thobbs/phpcassa) into your Symfony2 application.

## Installation ##

### Using composer ###

Add the `amigosdelrigor/cassandra-bundle` package to your `require` section in the `composer.json` file.

``` json
{
    "require": {
        "amigosdelrigor/cassandra-bundle": "dev-master",
    }
}
```

Add the CassandraBundle to your application's kernel:

``` php
<?php
public function registerBundles()
{
    $bundles = array(
        // ...
        new ADR\Bundle\CassandraBundle\ADRCassandraBundle(),
        // ...
    );
    ...
}
```

## Usage ##

Configure the `cassandra` keyspaces in your `config.yml`:

``` yaml
adr_cassandra:
    clients:
        data:
          server: localhost:9160
          keyspace: data

        otherdata:
          servers:
            - 192.168.1.12:9160
            - 192.168.1.57:9160
          keyspace: otherdatakeyspace
```

You have to configure at least one client which will be a representation of a keyspace.

For each client, 2 services are created:

- cassandra.&lt;name&gt;.pool which returns an instance of `phpcassa\Connection\ConnectionPool` and
 that should be used to send queries to the Cassandra Ring throughout your application

- cassandra.&lt;name&gt;.manager which returns an instance of `phpcassa\SystemManager`
which should be mainly used in tasks like getting information about the schema, making
schema changes or getting information about the state and configuration of the cluster

**Note:** The manager service is always referred to the first server defined under server(s) configuration key.

In your controllers you can now access cassandra keyspaces via all your configured clients,
and of course perform actions to the ColumnFamilies and SuperColumnFamilies:

``` php
<?php
$cFamily = $this->get('cassandra.data.pool')->getColumnFamily('mycfamily');

$cFamily->insert('row_key', array('c1' => 'v1', 'c2' => 'v2','c3' => 'v3','c4' => 'v4',));
$data = $cFamily->get('row_key');
$columns = $cFamily->get_count('row_key');
```
