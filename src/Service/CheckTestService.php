<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Question\QuestionInterface;
use App\Entity\TestResult\TestResult;
use App\Entity\TestResult\TestResultInterface;

class CheckTestService implements CheckTestServiceInterface
{
	/**
	 * @param QuestionInterface[] $questions
	 * @param $data
	 * @return TestResultInterface
	 */
	public function check(array $questions, $data): TestResultInterface
	{
		$passedQuestionIds = [];
		$failedQuestionIds = [];
		$allAnswers = [];
		foreach ($questions as $question)
		{
			$userAnswers = $data[$question->getHash()];
			$isPassedQuestion = $this->checkQuestion($question, $userAnswers, $allAnswers);

			$status = $isPassedQuestion ? 'passed' : 'failed';
			$isPassedQuestion ? $passedQuestionIds[] = $question->getId() : $failedQuestionIds[] = $question->getId();

			$allAnswers[$question->getHash()]['question'] = $question->getContent();
			$allAnswers[$question->getHash()]['status'] = $status;
			$allAnswers[$question->getHash()]['correct_answers'] = $question->getCorrectAnswers();
		}

		return (new TestResult())
			->setResult(sprintf('%s/%s', count($passedQuestionIds), count($questions)))
			->setAllAnswers($allAnswers)
			->setPassedQuestions($passedQuestionIds)
			->setFailedQuestions($failedQuestionIds);

	}

	private function checkQuestion(QuestionInterface $question, array $userAnswers, array &$allAnswers): bool
	{
		$countCorrectAnswers = 0;
		$questionAnswers = $question->getAllAnswersByType();

		if (empty(array_filter($userAnswers)))
		{
			$allAnswers[$question->getHash()]['user_answers'] = [];

			return false;
		}

		foreach ($userAnswers as $answerCode => $isSelected)
		{
			if (!$isSelected)
			{
				continue;
			}

			[$answerType, $answerIndex] = explode('_', $answerCode);
			$allAnswers[$question->getHash()]['user_answers'][] = $questionAnswers[$answerType][(int)$answerIndex];

			if ($answerType === 'wrong')
			{
				return false;
			}

			$countCorrectAnswers++;
		}

		if ($countCorrectAnswers > 0)
		{
			return true;
		}

		return false;
	}

}