<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\QuestionRepository;
use App\Service\CheckTestServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TestForm;

class TestController extends AbstractController
{
	public function __construct(
		private readonly QuestionRepository        $questionRepository,
		private readonly EntityManagerInterface    $entityManager,
		private readonly CheckTestServiceInterface $checkService
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
			$data = $form->getData();
			$result = $this->checkService->check($questions, $data);
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