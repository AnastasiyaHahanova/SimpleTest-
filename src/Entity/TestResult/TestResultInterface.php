<?php

declare(strict_types=1);

namespace App\Entity\TestResult;

interface TestResultInterface
{
	public function getId(): ?int;

	public function getPassedQuestions(): array;

	public function setPassedQuestions(array $passedQuestions): static;

	public function getFailedQuestions(): array;

	public function setFailedQuestions(array $failedQuestions): static;

	public function getResult(): ?string;

	public function setResult(string $result): static;

	public function getAllAnswers(): array;

	public function setAllAnswers(array $answers): static;
}