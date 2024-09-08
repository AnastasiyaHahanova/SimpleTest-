<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TestForm;

class TestResultController extends AbstractController
{
	public function __construct(private readonly QuestionRepository $questionRepository)
	{
	}

	#[Route('/test', name: 'test_result')]
	public function test(Request $request): Response
	{
		$questions = $this->questionRepository->findAllByLimitAndOffset();
		shuffle($questions);
		$form = $this->createForm(TestForm::class, null, ['questions' => $questions]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid())
		{
			$passedQuestionIds = [];
			$failedQuestionIds = [];
			foreach ($form->getData() as $question => $answers)
			{
				$questionId = (int)str_replace('question_', '', $question);

			}
			dd($form->getData());
			return $this->redirectToRoute('task_success');
		}

		return $this->render('test.html.twig', [
			'form' => $form,
			'questions' => $questions,
			'test_number' => 1
		]);
	}

}