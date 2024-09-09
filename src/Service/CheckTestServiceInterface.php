<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Question\QuestionInterface;
use App\Entity\TestResult\TestResultInterface;

interface CheckTestServiceInterface
{
	/**
	 * @param QuestionInterface[] $questions
	 * @param $data
	 * @return TestResultInterface
	 */
	public function check(array $questions, $data): TestResultInterface;

}