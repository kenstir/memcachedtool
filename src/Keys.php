<?php

namespace Joelvardy\Memcached\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Keys extends Command {


    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure() {
        $this->setName('keys')
            ->setDescription('List keys stored in memcache store.')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Memcached server hostname.')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Memcached server port.')
            ->addOption('selector', null, InputOption::VALUE_OPTIONAL, 'List all keys matching selector, eg: some_keys_*')
            ->addOption('regex', null, InputOption::VALUE_OPTIONAL, 'List all keys matching regex, eg: /^some_keys_([0-9]+)$/');
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

        if ($input->getOption('selector')) {
            $regex = Selector::toRegex($input->getOption('selector'));
        } else if ($input->getOption('regex')) {
            $regex = $input->getOption('regex');
        } else {
            $regex = false;
        }

        if ($regex) {
            $output->writeln('<info>All keys matching '.$regex.'</info>');
        } else {
            $output->writeln('<info>All keys in memcache store</info>');
        }

        $keys = static::keys($memcached, $regex);
        foreach ($keys as $key) {
            $output->writeln('Key: '.$key);
        }

        $output->writeln('<info>Total keys: '.count($keys).'</info>');

    }


    public static function keys(\Memcached $memcached, $regex = false) {

        $allKeys = $memcached->getAllKeys();

        if ( ! $regex) return $allKeys;

        $matchingKeys = [];
        foreach ($allKeys as $key) {
            if (preg_match($regex, $key) > 0) {
                array_push($matchingKeys, $key);
            }
        }
        return $matchingKeys;

    }


}
