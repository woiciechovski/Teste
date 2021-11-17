<?php

namespace ASPTest\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

use ASPTest\Model\User;

class CreatePasswordUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'USER:CREATE-PWD';

    protected function configure(): void
    {
        $this->setName('USER:CREATE-PWD {id}')
            ->setDescription('Creates a password')
            ->setHelp('This command allows you to create a password...')
            ->addArgument('id', InputArgument::REQUIRED, 'The id of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $userId = $input->getArgument('id');

        $user = new User();

        if ($user->getUserById($userId)) {
            $user = $user->getUserById($userId);

            $output->writeln(json_encode($user));

            $output->writeln('User found!');
        } else {
            $output->writeln('User not found!');
            return Command::FAILURE;
        }


        $helper = $this->getHelper('question');

        $question = new Question('What is the password?');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $question);

        $question = new Question('Confirm the password');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $confirmPassword = $helper->ask($input, $output, $question);

        if ($password != $confirmPassword) {
            $output->writeln('Passwords do not match.');
            return Command::FAILURE;
        }
        $errors = [];
        if (!preg_match('/[A-Z]/', $password)) {
            // There is at least one upper
            $errors[] = 'Password has no upper case letter';
        }

        if (!preg_match('/[a-z]/', $password)) {
            // There is at least one lower
            $errors[] = 'Password has no lower case letter';
        }

        if (!preg_match('/[0-9]/', $password)) {
            // There is at least one digit

            $errors[] = 'Password has no digit';
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            // There is at least one special character
            $errors[] = 'Password has no special character';
        }

        if (strlen($password) < 6) {
            // There is at least 6 characters
            $errors[] = 'Password has less than 6 characters';
        }

        if (count($errors) > 0) {
            $output->writeln('######ERRORS######');
            foreach ($errors as $error) {
                $output->writeln('**' . $error);
            }
            $output->writeln('Password not created');
            return Command::INVALID;
        }


        $user->setPassword($password);
        $user->save();
        $output->writeln(json_encode($user));

        $output->writeln('Password created successfully.');

        return Command::SUCCESS;
    }
}
