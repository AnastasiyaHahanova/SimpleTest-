<?php

declare(strict_types=1);

namespace App\Entity\Question;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question implements QuestionInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 255, options: ['default' => ''])]
	private string $hash = '';

	#[ORM\Column(length: 255, options: ['default' => ''])]
	private string $content = '';

	#[ORM\Column]
	private array $correctAnswers = [];

	#[ORM\Column]
	private array $wrongAnswers = [];

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): static
	{
		$this->content = $content;

		return $this;
	}

	public function getCorrectAnswers(): array
	{
		return $this->correctAnswers;
	}

	public function setCorrectAnswers(array $correctAnswers): static
	{
		$this->correctAnswers = $correctAnswers;

		return $this;
	}

	public function getWrongAnswers(): array
	{
		return $this->wrongAnswers;
	}

	public function setWrongAnswers(array $wrongAnswers): static
	{
		$this->wrongAnswers = $wrongAnswers;

		return $this;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

	public function setHash(string $hash): static
	{
		$this->hash = $hash;

		return $this;
	}
}
