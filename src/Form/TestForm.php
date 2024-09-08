<?php

declare(strict_types=1);

use App\Entity\QuestionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		/**
		 * @var QuestionInterface $question
		 */
		foreach ($options['questions'] as $question)
		{
			$builder->add('question_' . $question->getId(),
				TestSubForm::class,
				[
					'answers' => $this->getShuffledAnswers($question)
				]
			);

		}
	}

	private function getShuffledAnswers(QuestionInterface $question): array
	{
		$answers = [];
		foreach ($question->getCorrectAnswers() as $index => $answer)
		{
			$key = sprintf('correct_answer_%s', $index);
			$answers[$key] = $answer;
		}

		foreach ($question->getWrongAnswers() as $index => $answer)
		{
			$key = sprintf('wrong_answer_%s', $index);
			$answers[$key] = $answer;
		}

		$keys = array_keys( $answers );
		shuffle( $keys );
		return array_merge( array_flip( $keys ) , $answers );

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'questions' => [],
		]);
	}


}