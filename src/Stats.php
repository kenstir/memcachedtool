<?php

namespace Joelvardy\Memcached\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ByteUnits\Binary as Units;

class Stats extends Command {


    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure() {
        $this->setName('stats')
            ->setDescription('Basic information about memcache store.')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Memcached server hostname.')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Memcached server port.');
    }


    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

        $memcached = Connection::get($input->getOption('host'), $input->getOption('port'));
        if ( ! $memcached) return $output->writeln('<error>Could not connect to memcached server!</error>');

        $stats = (object) array_values($memcached->getStats())[0];

        $output->writeln('Memcached Server Stats');
        $output->writeln('Version: <info>'.$stats->version.'</info>');
        $output->writeln('Uptime: <info>'.timeAgoInWords(date('c', (time() - $stats->uptime))).'</info>');
        $output->writeln('Items in cache: <info>'.$stats->curr_items.'</info>');
        $output->writeln('Size of cache: <info>'.Units::bytes($stats->bytes)->format().'</info>');

    }


}
