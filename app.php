<?php

require __DIR__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use ConsoleComponent\Command\DeployPluginCommand;

//TODO en que version vamos?
$app = new Application("Test App", "1.0.0");
$app->add(new DeployPluginCommand());
$app->run();