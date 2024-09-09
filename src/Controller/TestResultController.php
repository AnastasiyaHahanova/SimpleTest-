<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TestResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestResultController extends AbstractController
{
	public function __construct(private readonly TestResultRepository $resultRepository)
	{
	}

	#[Route('/test/result/{id}', name: 'test_result')]
	public function test(int $id): Response
	{

		return $this->render('test_result.html.twig', [
			'result' => $this->resultRepository->find($id)
		]);
	}

}