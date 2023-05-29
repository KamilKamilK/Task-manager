<?php
declare(strict_types=1);
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;


class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextareaType::class,
                ['label' => 'Task title',
                    'required' => false,
                    'attr' => [
                        'name' => 'title,'
                    ]
                ])
            ->add('details', TextareaType::class,
                ['label' => 'Task details',
                    'required' => false,
                    'attr' => [
                        'name' => 'details,'
                    ]
                ])
            ->add('deadline', DateType::class,
                ['label' => 'Task deadline',
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => [
                        'name' => 'deadline,'
                    ]
                ])
            ->add('completed', ChoiceType::class,
                ['label' => 'Completed : true/false',
                    'required' => false,
                    'choices' => [
                        'true' => 'true',
                        'false' => 'false',
                    ],
                    'attr' => [
                        'name' => 'completed,'
                    ]
                ])
            ->add('submit', SubmitType::class,
                ['label' => 'Search']);
    }

}