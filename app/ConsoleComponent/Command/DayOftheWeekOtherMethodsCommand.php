<?php

namespace ConsoleComponent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;


class DayOftheWeekOtherMethodsCommand extends Command{

    protected function configure(){

        $this->setName('techjourney:DayOftheWeekOtherMethods')
        ->setDescription('Sample to show how use other methods in command class')
        ->addArgument('date', InputArgument::REQUIRED, 'Date to get  day of the Week', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $date = $input->getArgument('date');
        $output->writeln('The day of the Week is'. date('l', strtotime($date)));
        return 0;

    }

    protected function interact(InputInterface $input, OutputInterface $output){

       $date = $input->getArgument('date');
       if(!$this->validateDate($date)){
           $output->writeln("Please enter a valid date");
           die;

       }

    }

    protected function initialize(InputInterface $input, OutputInterface $output){

        echo "Iniciando .. \n\r";
        
    }

    private function validateDate($date){

        $d = \DateTime::createFromFormat('Y-m-d',$date);
        return $d && $d->format('Y-m-d') == $date;

    }




}