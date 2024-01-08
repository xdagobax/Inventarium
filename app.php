<?php

require __DIR__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use ConsoleComponent\Command\HelloWorldCommand;
use ConsoleComponent\Command\DayOfTheWeekCommand;
use ConsoleComponent\Command\DayOftheWeekOtherMethodsCommand;
use ConsoleComponent\Command\DeployPluginCommand;

$app = new Application("Test App", "1.0.0");
$app->add(new HelloWorldCommand());
$app->add(new DayOfTheWeekCommand());
$app->add(new DayOftheWeekOtherMethodsCommand());
$app->add(new DeployPluginCommand());
$app->run();