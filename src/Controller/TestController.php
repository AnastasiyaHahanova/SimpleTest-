<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestResult\TestResult;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TestForm;

class TestController extends AbstractController
{
	public function __construct(
		private readonly QuestionRepository     $questionRepository,
		private readonly EntityManagerInterface $entityManager
	)
	{
	}

	#[Route('/test', name: 'test')]
	public function test(Request $request): Response
	{
		$questions = $this->questionRepository->findByLimitAndOffset();
		shuffle($questions);
		$form = $this->createForm(TestForm::class, null, ['questions' => $questions]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid())
		{
			$passedQuestionIds = [];
			$failedQuestionIds = [];
			$allAnswers = [];
			$data = $form->getData();
			foreach ($questions as $question)
			{
				$userAnswers = $data[$question->getHash()];
				$questionAnswers = $question->getAllAnswersByType();
				$countCorrectAnswers = 0;
				foreach ($userAnswers as $answerCode => $isSelected)
				{
					if (!$isSelected)
					{
						continue;
					}

					[$answerType, $answerIndex] = explode('_', $answerCode);

					$allAnswers[$question->getHash()]['question'] = $question->getContent();
					$allAnswers[$question->getHash()]['correct_answers'] = $question->getCorrectAnswers();
					$allAnswers[$question->getHash()]['user_answers'][] = $questionAnswers[$answerType][(int)$answerIndex];

					if ($answerType === 'wrong')
					{
						$failedQuestionIds[] = $question->getId();
						continue 2;
					}

					$countCorrectAnswers++;
				}

				if ($countCorrectAnswers > 0)
				{
					$passedQuestionIds[] = $question->getId();
				}
			}

			$result =
				(new TestResult())
					->setResult(sprintf('%s/%s', count($passedQuestionIds), count($questions)))
					->setAllAnswers($allAnswers)
					->setPassedQuestions($passedQuestionIds)
					->setFailedQuestions($failedQuestionIds);

			$this->entityManager->persist($result);
			$this->entityManager->flush();

			return $this->redirectToRoute('test_result', ['id' => $result->getId()]);
		}

		return $this->render('test.html.twig', [
			'form' => $form,
			'questions' => $questions,
		]);
	}

}