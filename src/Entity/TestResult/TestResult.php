<?php

declare(strict_types=1);

namespace App\Entity\TestResult;

use App\Repository\TestResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestResultRepository::class)]
class TestResult implements TestResultInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $passedQuestions = [];

    #[ORM\Column]
    private array $failedQuestions = [];

	#[ORM\Column]
	private array $allAnswers = [];

    #[ORM\Column(length: 255, options: ['default' => ''])]
    private string $result = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassedQuestions(): array
    {
        return $this->passedQuestions;
    }

    public function setPassedQuestions(array $passedQuestions): static
    {
        $this->passedQuestions = $passedQuestions;

        return $this;
    }

    public function getFailedQuestions(): array
    {
        return $this->failedQuestions;
    }

    public function setFailedQuestions(array $failedQuestions): static
    {
        $this->failedQuestions = $failedQuestions;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): static
    {
        $this->result = $result;

        return $this;
    }

	public function getAllAnswers(): array
	{
		return $this->allAnswers;
	}

	public function setAllAnswers(array $answers): static
	{
		$this->allAnswers = $answers;

		return $this;
	}
}
