<?php

namespace ADR\Bundle\CassandraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use phpcassa\SystemManager;

class CassandraCreateKeyspaceCommand extends ContainerAwareCommand
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    /**
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('cassandra:keyspace:create')
            ->setDescription('Creates the configured keyspace in selected cluster')
            ->addArgument('cluster', null, InputOption::VALUE_REQUIRED, 'The cluster name in Symfony2 where keyspace will be created')
            ->setHelp(<<<EOT
The <info>cassandra:keyspace:create</info> command creates the configured keyspace in the selected cluster.

<info>app/console cassandra:keyspace:create test</info>
EOT
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $cluster = $this->getContainer()->get('cassandra.cluster.' . $input->getArgument('cluster'));
        $clusterServers = $cluster->getServers();

        $manager = new SystemManager($clusterServers[0]);
        $manager->create_keyspace($cluster->getKeyspace(), array());

        $output->writeln('<info>Keyspace ' . $cluster->getKeyspace() . ' successfully created at ' . $clusterServers[0] . '</info>');
    }
}