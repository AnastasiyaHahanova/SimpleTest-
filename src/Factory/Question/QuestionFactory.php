<?php

declare(strict_types=1);

namespace App\Factory\Question;

use App\Entity\Question\Question;
use App\Entity\Question\QuestionInterface;
use Symfony\Component\Uid\Uuid;

class QuestionFactory implements QuestionFactoryInterface
{
	/**
	 * @param string $content
	 * @param string[] $correctAnswers
	 * @param string[] $wrongAnswers
	 * @return QuestionInterface
	 */
	public function create(string $content, array $correctAnswers, array $wrongAnswers): QuestionInterface
	{
		return (new Question())
			->setHash(Uuid::v4()->toString())
			->setContent($content)
			->setCorrectAnswers($correctAnswers)
			->setWrongAnswers($wrongAnswers);

	}
}