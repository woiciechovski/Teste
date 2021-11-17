<?php

namespace ASPTest;

require __DIR__ . '/src/config/bootstrap.php';

use Symfony\Component\Console\Application;
use ASPTest\Command\CreateUserCommand;
use ASPTest\Command\CreatePasswordUserCommand;


$app = new Application();
$app->add(new CreatePasswordUserCommand());
$app->add(new CreateUserCommand());


$app->run();
