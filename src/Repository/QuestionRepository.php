<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Question\Question;
use App\Entity\Question\QuestionInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

	/**
	 * @param int $limit
	 * @param int $offset
	 * @return QuestionInterface[]
	 */
	public function findAllByLimitAndOffset(int $limit = 10, int $offset = 0): array
	{
		return $this->findBy([], null, $limit, $offset);
	}
}
