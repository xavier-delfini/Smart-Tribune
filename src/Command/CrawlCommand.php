<?php

namespace App\Command;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:crawl',
    description: 'Run a node script to crawl pages',
)]
class CrawlCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $process = new Process(['assets', 'node crawler.js']);
        $process->setWorkingDirectory("/var/www/html/Smart-Tribune/assets");
        $process->run();

        if(!$process->isSuccessful())
        {
            throw new ProcessFailedException($process);
        }
        
        echo $process->getOutput();

        return Command::SUCCESS;
    }
}
