<?php

namespace ADR\Bundle\CassandraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CassandraDropKeyspaceCommand extends ContainerAwareCommand
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
            ->setName('cassandra:keyspace:drop')
            ->setDescription('Drops the keyspace in selected client')
            ->addArgument('client', InputArgument::REQUIRED, 'The cluster name in Symfony2 where keyspace will be created')
            ->addArgument('keyspace', InputArgument::REQUIRED, 'The keyspace name')
            ->setHelp(<<<EOT
The <info>cassandra:keyspace:drop</info> command drops the configured keyspace in the selected cluster.

<info>app/console cassandra:keyspace:drop [client] [keyspace]</info>
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
        $manager->drop_keyspace($keyspace);

        $output->writeln('<info>Keyspace ' . $keyspace . ' successfully dropped at ' . $manager->getServer() . '</info>');
    }
}