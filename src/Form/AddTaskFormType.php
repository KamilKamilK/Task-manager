<?php
declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;


class AddTaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextareaType::class,
                ['label' => 'Task title',
                    'required' => false,
                    'attr' => [
                        'name' => 'title',
                        'autocomplete' => 'off'
                    ]
                ])
            ->add('details', TextareaType::class,
                ['label' => 'Task details',
                    'required' => false,
                    'attr' => [
                        'name' => 'details',
                        'autocomplete' => 'off'
                    ]
                ])
            ->add('tools', CollectionType::class, [
                'entry_type' => ToolsFormType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('deadline', DateType::class,
                ['label' => 'Task deadline',
                    'required' => false,
                    'widget' => 'single_text',
                    'attr' => [
                        'name' => 'deadline',
                        'autocomplete' => 'off'
                    ],
                    'constraints' => [
                        new Assert\GreaterThanOrEqual('now')
                    ]
                ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if (empty($data['title'])) {
                $form->get('title')->addError(new FormError('This field cannot by empty'));
            }

            if (empty($data['details'])) {
                $form->get('details')->addError(new FormError('This field cannot by empty'));
            }

            if (empty($data['deadline'])) {
                $form->get('deadline')->addError(new FormError('This field cannot by empty'));
            }
        });
    }
}