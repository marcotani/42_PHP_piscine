<?php
namespace App\ex09\Form;

use App\ex09\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('enable', CheckboxType::class, [
                'label' => 'Enabled',
                'required' => false,
            ])
            ->add('birthdate', DateTimeType::class, [
                'label' => 'Birthdate',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('maritalStatus', ChoiceType::class, [
                'label' => 'Marital Status',
                'choices' => [
                    'Single' => 'single',
                    'Married' => 'married',
                    'Widower' => 'widower',
                ],
                'required' => false,
                'placeholder' => 'Select status...',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
