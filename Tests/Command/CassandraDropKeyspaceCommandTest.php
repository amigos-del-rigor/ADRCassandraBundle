<?php

namespace ADR\Bundle\CassandraBundle\Tests\Command;

use Symfony\Component\Console\Application;
use ADR\Bundle\CassandraBundle\Command\CassandraDropKeyspaceCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Mockery as m;

class CassandraDropKeyspaceCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getNonInteractiveData
     */
    public function testCreateKeyspaceCommand($input)
    {
        $application = new Application();
        $application->add(new CassandraDropKeyspaceCommand());

        $command = $application->find('cassandra:keyspace:drop');
        $command->setContainer($this->getMockContainer($input));

        $tester = new CommandTester($command);
        $tester->execute(
            array_merge(array('command' => $command->getName()), $input)
        );

        $this->assertEquals('Keyspace ' . $input['keyspace'] . ' successfully dropped at #mockServer#' . PHP_EOL, $tester->getDisplay());
    }

    private function getMockContainer($input)
    {
        $systemManager = m::mock('phpcassa\SystemManager');
        $systemManager
            ->shouldReceive('drop_keyspace')
            ->once()
            ->with($input['keyspace'])
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