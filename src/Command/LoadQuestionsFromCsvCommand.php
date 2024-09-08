<?php

declare(strict_types=1);

namespace App\Command;

use App\Factory\Question\QuestionFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'load:questions:from:csv',
	description: 'Load questions data from csv',
)]
class LoadQuestionsFromCsvCommand extends Command
{
	public const FILE_EXTENSION = 'csv';

	public function __construct(
		private readonly EntityManagerInterface $entityManager,
		private readonly QuestionFactoryInterface $factory
	)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->addArgument('path_to_file', InputArgument::OPTIONAL, 'Path to csv file')
			->addOption('question_fields_separator', 'qs', InputOption::VALUE_OPTIONAL, 'Question fields separator', ';')
			->addOption('question_answers_separator', 'as', InputOption::VALUE_OPTIONAL, 'Question answers separator', ',')
			->addOption('chunk_size', 'ch', InputOption::VALUE_OPTIONAL, 'Chunk size', 20)
			->addOption('file_with_headers', 'fwh', InputOption::VALUE_OPTIONAL, 'First line is headers', true);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$path = $input->getArgument('path_to_file');
		if (!$this->checkFilePath($io, $path))
		{
			return Command::FAILURE;
		}

		$questionFieldsSeparator = $input->getOption('question_fields_separator');
		$questionAnswersSeparator = $input->getOption('question_answers_separator');
		$isFirstLineHeaders = $input->getOption('file_with_headers');
		$chunkSize = $input->getOption('chunk_size');
		$count = 0;

		if (($handle = fopen($path, 'r')) !== false)
		{
			if ($isFirstLineHeaders)
			{
				fgetcsv($handle, 1000, $questionFieldsSeparator);
			}

			while (($data = fgetcsv($handle, 1000, $questionFieldsSeparator)) !== false)
			{
				$question = $this->factory->create($data[0], explode($questionAnswersSeparator, $data[1]), explode($questionAnswersSeparator, $data[2]));
				$this->entityManager->persist($question);
				$count++;
				if ($count % $chunkSize === 0)
				{
					$this->entityManager->flush();
					$this->entityManager->clear();
				}
			}

			$this->entityManager->flush();
			fclose($handle);
		}

		$io->success('All questions was successfully loaded to database!');
		$io->comment($count . ' rows was affected.');

		return Command::SUCCESS;
	}

	private function checkFilePath(SymfonyStyle $io, string $path): bool
	{
		if (!file_exists($path))
		{
			$io->error(sprintf('File with path %s does not exist.', $path));
			return false;
		}

		if (!is_readable($path))
		{
			$io->error(sprintf('File with path %s can not be read.', $path));
			return false;
		}

		$pathParts = pathinfo($path);
		if ($pathParts['extension'] !== self::FILE_EXTENSION)
		{
			$io->error(sprintf('File with path %s has wrong extension %s.', $path, $pathParts['extension']));
			return false;
		}

		return true;
	}
}
