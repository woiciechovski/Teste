<?php

namespace ASPTest\Command;

use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use ASPTest\Model\User;

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'USER:CREATE';

    protected function configure(): void
    {
        $this->setName('USER:CREATE')
            ->setDescription('Creates a user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.')
            ->addArgument('secondName', InputArgument::REQUIRED, 'The second name of the user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('age', InputArgument::OPTIONAL, 'The age of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errors = [];
        strlen($input->getArgument('name')) >= 2 ?  Null : $errors[] = 'Name must be at least 2 characters long.';
        strlen($input->getArgument('secondName')) >= 2 ? Null : $errors[] = 'secondName must be at least 2 characters long.';
        strlen($input->getArgument('name')) <= 35 ? null : $errors[] = 'Name must be at most 35 characters long.';
        strlen($input->getArgument('secondName')) <= 35 ? null : $errors[] = 'secondName must be at most 35 characters long.';
        filter_var($input->getArgument('email'), FILTER_VALIDATE_EMAIL) ? null : $errors[] = 'Email is not valid.';
        if ($input->getArgument('age') != null) {
            $age = $input->getArgument('age');
            $age >= 0 ? null : $errors[] = 'Age must be at least 0 years old.';
            $age <= 150 ? null : $errors[] = 'Age must be at most 150 years old.';
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln($error);
            }
            $output->writeln('User could not be created.');
            return Command::INVALID;
        } else {

            $user = new User();
            $user->setName($input->getArgument('name'));
            $user->setSecondName($input->getArgument('secondName'));
            $user->setEmail($input->getArgument('email'));
            $user->setAge($age ?? null);

            $user->save();

            if ($user->id > 0) {
                $output->writeln('User created successfully.');
                $output->writeln(json_encode($user));
                return Command::SUCCESS;
            } else {
                $output->writeln('User could not be created.');
                return Command::FAILURE;
            }
        }
    }
}
