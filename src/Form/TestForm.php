<?php

declare(strict_types=1);

use App\Entity\QuestionInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$checkboxes = [];
		/**
		 * @var QuestionInterface $question
		 */
		foreach ($options['questions'] as $question)
		{
			foreach ($question->getCorrectAnswers() as $index => $answer)
			{
				$key = sprintf('question_%s_correct_answer_%s', $question->getId(), $index);
				$checkboxes [$key] = $answer;
			}

			foreach ($question->getWrongAnswers() as $index => $answer)
			{
				$key = sprintf('question_%s_wrong_answer_%s', $question->getId(), $index);
				$checkboxes [$key] = $answer;
			}
		}

		$keys = array_keys($checkboxes);
		shuffle($keys);

		foreach ($keys as $key)
		{
			$builder->add(
				$key,
				CheckboxType::class,
				[
					'label' => $checkboxes[$key]
				]
			);
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'questions' => [],
		]);
	}


}