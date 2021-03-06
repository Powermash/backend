<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('comicvine:import')
    ->setDescription('Import data from comic vine')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $output->writeln('Fetching characters');

        $characters = $app['comicvine.facemash']->getCharacters();
        $output->writeln(sprintf('Fetched %d characters', sizeof($characters)));

        foreach ($characters as $character) {
        	$output->writeln(sprintf('Fetched Character <info>%s</info>', $character->name));

            $exists = $app['db']->fetchAssoc('SELECT * FROM `characters` WHERE id = ?', array((int) $character->id));
            if (false === $exists) {
                $output->writeln("\t> Character does not exist in database: inserting");

            	$affected = $app['db']->insert('characters', [
            		'id' => $character->id,
            		'image' => $character->getImage(),
            		'name' => $character->name,
            		'description' => $character->description,
            	]);

                $output->writeln("\t> Character succesfully inserted");
            } else {
                $output->writeln("\t> Character exists in database: updating");
                
                $affected = $app['db']->update('characters', [
                    'image' => $character->getImage(),
                    'name' => $character->name,
                    'description' => $character->description,
                ], [
                    'id' => $character->id
                ]);

                $output->writeln("\t> Character succesfully updated");
            }
        }
    })
;

return $console;
