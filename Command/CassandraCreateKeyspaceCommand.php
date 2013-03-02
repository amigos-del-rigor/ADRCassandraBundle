<?php

namespace ADR\Bundle\CassandraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument('client', InputArgument::REQUIRED, 'The client name in Symfony2 where keyspace will be created')
            ->addArgument('keyspace', InputArgument::REQUIRED, 'The keyspace name')
            ->setHelp(<<<EOT
The <info>cassandra:keyspace:create</info> command creates the configured keyspace in the selected cluster.

<info>app/console cassandra:keyspace:create [client] [keyspace]</info>
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

        $keyspace = $input->getArgument('keyspace');

        $manager = $this->getContainer()->get('cassandra.' . $input->getArgument('client') . '.manager');
        $manager->create_keyspace($keyspace, array());

        $output->writeln('<info>Keyspace ' . $keyspace . ' successfully created at ' . $manager->getServer() . '</info>');
    }
}