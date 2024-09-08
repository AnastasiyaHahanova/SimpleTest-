<?php

declare(strict_types=1);

namespace App\Factory\Question;

use App\Entity\Question\QuestionInterface;

interface QuestionFactoryInterface
{
	/**
	 * @param string $content
	 * @param string[] $correctAnswers
	 * @param string[] $wrongAnswers
	 * @return QuestionInterface
	 */
	public function create(string $content, array $correctAnswers, array $wrongAnswers): QuestionInterface;
}