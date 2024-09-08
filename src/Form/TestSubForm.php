<?php

declare(strict_types=1);

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestSubForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		foreach ($options['answers'] as $name => $label)
		{
			$builder->add(
				$name,
				CheckboxType::class,
				[
					'label' => $label
				]
			);
		}
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'answers' => [],
		]);
	}


}