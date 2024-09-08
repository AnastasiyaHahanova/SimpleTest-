<?php

declare(strict_types=1);

namespace App\Entity;

interface QuestionInterface
{
	public function getId(): ?int;

	public function getContent(): ?string;

	public function setContent(string $content): static;

	public function getCorrectAnswers(): array;

	public function setCorrectAnswers(array $correctAnswers): static;

	public function getWrongAnswers(): array;

	public function setWrongAnswers(array $wrongAnswers): static;

}