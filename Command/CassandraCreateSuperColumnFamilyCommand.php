<?php

namespace ADR\Bundle\CassandraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use phpcassa\SystemManager;

class CassandraCreateSuperColumnFamilyCommand extends ContainerAwareCommand
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
            ->setName('cassandra:supercolumnfamily:create')
            ->setDescription('Creates the supercolumnfamily in the default keyspace (or in the supplied keyspace) in selected cluster')
            ->addArgument('client', InputArgument::REQUIRED, 'The client name in Symfony2 where keyspace will be created')
            ->addArgument('supercolumnfamilyname', InputArgument::REQUIRED, 'The SuperColumnFamily name that will be created')
            ->addOption('keyspace', null, InputOption::VALUE_OPTIONAL, 'The keyspace name. If not supplied, the configured keypace in client is used')
            ->setHelp(<<<EOT
The <info>cassandra:supercolumnfamily:create</info> command creates the SuperColumnFamily in the selected client in the default keyspace, or in the supplied one if any.

<info>app/console cassandra:supercolumnfamily:create [client] [supercolumnfamilyname] --keyspace=[keyspace]</info>
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
        $superColumnFamilyName = $input->getArgument('supercolumnfamilyname');

        $manager->create_column_family($keyspace, $superColumnFamilyName, array('column_type' => 'Super'));;

        $output->writeln('<info>SuperColumnFamily ' . $superColumnFamilyName . ' successfully created at ' . $manager->getServer() . ':' . $keyspace .'</info>');
    }
}