<?php

namespace Joelvardy\Memcached\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Delete extends Command {


    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure() {
        $this->setName('delete')
            ->setDescription('Delete keys stored in memcache store.')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Memcached server hostname.')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Memcached server port.')
            ->addOption('key', null, InputOption::VALUE_OPTIONAL, 'Delete specific keys.')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Delete all keys.')
            ->addOption('selector', null, InputOption::VALUE_OPTIONAL, 'Delete keys matching selector, eg: some_keys_*')
            ->addOption('regex', null, InputOption::VALUE_OPTIONAL, 'Delete keys matching regex, eg: /^some_keys_([0-9]+)$/');
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

        if ($input->getOption('key')) {
            $output->writeln('<error>Deleted</error> '.$input->getOption('key').' key');
            $memcached->delete($input->getOption('key'));
        }

        if ($input->getOption('all')) {

            // Not using $memcached->flush(); because keys will still be returned in getAllKeys()

            $output->writeln('<error>Delete</error> all keys in memcache store');

            $keys = Keys::keys($memcached);
            foreach ($keys as $key) {
                $memcached->delete($key);
                $output->writeln('<error>Deleted</error> '.$key.' key');
            }

            $output->writeln('<info>'.count($keys).' keys deleted</info>');

        }

        if ($regex) {

            $output->writeln('<info>Delete keys matching '.$regex.'</info>');

            $keys = Keys::keys($memcached, $regex);
            foreach ($keys as $key) {
                $memcached->delete($key);
                $output->writeln('<error>Deleted</error> '.$key.' key');
            }

            $output->writeln('<info>'.count($keys).' keys deleted</info>');

        }

        if ( ! $input->getOption('key') && ! $input->getOption('all') && ! $regex) {
            $output->writeln('For help run <info>memcachedtool help delete</info>');
        }

    }


}
