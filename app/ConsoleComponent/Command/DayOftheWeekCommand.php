<?php

namespace ConsoleComponent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class DayOftheWeekCommand extends Command{

    protected function configure(){

        $this->setName('techjourney:DayOftheWeek')
        ->setDescription('Prints Days of the Week')
        ->addArgument('date', InputArgument::REQUIRED, 'Date to get  day of the Week', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $date = $input->getArgument('date');
        $output->writeln('The day of the Week is'. date('l', strtotime($date)));
        return 0;

    }


}