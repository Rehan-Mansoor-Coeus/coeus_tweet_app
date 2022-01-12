<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Testing Perpose';

    public function __construct()
    {

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('name', InputArgument::REQUIRED, 'Who do you want to greet?')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arg = $input->getArgument('name');
        $text = 'Hi '.$arg;
        $output->writeln('Your name will be repeat in '.strlen($arg). ' times ..');
        $output->writeln('');
        for ($i = 0; $i < strlen($arg); $i++) {
            $output->writeln($text);
        }

        return Command::SUCCESS;
    }

}