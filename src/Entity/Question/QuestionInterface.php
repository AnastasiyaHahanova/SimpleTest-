<?php

declare(strict_types=1);

namespace App\Entity\Question;

interface QuestionInterface
{
	public function getId(): ?int;

	public function getContent(): ?string;

	public function setContent(string $content): static;

	public function getCorrectAnswers(): array;

	public function setCorrectAnswers(array $correctAnswers): static;

	public function getWrongAnswers(): array;

	public function setWrongAnswers(array $wrongAnswers): static;

	public function getHash(): string;

	public function setHash(string $hash): static;
}