<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{

		$builder
			->add('subject', TextType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'subject is required',
					]),
					new Length([
						'min' => 2,
						'max' => 255,
						'minMessage' => 'Contact subject is too short',
						'maxMessage' => 'Contact subject is too long',
					])
				],
			])
			->add('email', EmailType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'email is required',
					]),
                    new Email([
						'message' => 'email is not valid'
					])
				],
			])
			->add('message', textareaType::class, [
				'constraints' => [
					new NotBlank([
						'message' => 'message is required',
					]),
				],
			]);
            
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Contact::class,
		]);
    }
}