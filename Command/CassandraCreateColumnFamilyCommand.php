<?php

namespace ADR\Bundle\CassandraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use phpcassa\SystemManager;

class CassandraCreateColumnFamilyCommand extends ContainerAwareCommand
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
            ->setName('cassandra:columnfamily:create')
            ->setDescription('Creates the columnfamily in the default keyspace (or in the supplied keyspace) in selected cluster')
            ->addArgument('client', null, InputOption::VALUE_REQUIRED, 'The client name in Symfony2 where keyspace will be created')
            ->addArgument('columnfamilyname', null, InputOption::VALUE_REQUIRED, 'The ColumnFamily name that will be created')
            ->addOption('keyspace', null, InputOption::VALUE_OPTIONAL, 'The keyspace name. If not supplied, the configured keypace in client is used')
            ->setHelp(<<<EOT
The <info>cassandra:columnfamily:create</info> command creates the ColumnFamily in the selected client in the default keyspace, or in the supplied one if any.

<info>app/console cassandra:columnfamily:create [client] [columnfamilyname] --keyspace=[keyspace]</info>
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

        $manager = $this->getContainer()->get('cassandra.' . $input->getArgument('client') . '.manager');
        $keyspace = $input->getOption('keyspace') ? : $manager->getKeyspace();
        $columnFamilyName = $input->getArgument('columnfamilyname');

        $manager->create_column_family($keyspace, $columnFamilyName);;

        $output->writeln('<info>ColumnFamily ' . $columnFamilyName . ' successfully created at ' . $manager->getServer() . ':' . $keyspace .'</info>');
    }
}