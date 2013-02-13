<?php

namespace ADR\Bundle\CassandraBundle\Tests\Command;

use Symfony\Component\Console\Application;
use ADR\Bundle\CassandraBundle\Command\CassandraCreateKeyspaceCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

class CassandraCreateKeyspaceCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getNonInteractiveData
     */
    public function testCreateKeyspaceCommand($input)
    {
        $application = new Application();
        $application->add(new CassandraCreateKeyspaceCommand());
        $command = $application->find('cassandra:keyspace:create');
        $command->setContainer($this->getMockContainer($input));

        $tester = new CommandTester($command);
        $tester->execute($input);

        $this->assertEquals('Keyspace fooKeySpace successfully created at #mockServer#' . PHP_EOL, $tester->getDisplay());
    }

    private function getMockContainer($input)
    {
        $systemManager = m::mock('phpcassa\SystemManager');
        $systemManager
            ->shouldReceive('create_keyspace')
            ->once()
            ->with($input['keyspace'], array())
        ;

        $systemManager
            ->shouldReceive('getServer')
            ->once()
            ->withNoArgs()
            ->andReturn('#mockServer#')
        ;

        $container = m::mock('Symfony\Component\DependencyInjection\Container');
        $container
            ->shouldReceive('get')
            ->once()
            ->with('cassandra.' . $input['client'] . '.manager')
            ->andReturn($systemManager)
        ;

        return $container;
    }

    public function getNonInteractiveData()
    {
        return array(
            array(array('keyspace' => 'fooKeySpace', 'client' => 'barClient')),
        );
    }
}