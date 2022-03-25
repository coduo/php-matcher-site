<?php

declare(strict_types=1);

namespace App\Controller\Form;

use Norzechowicz\AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class MatcherForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('GET')
            ->add('value', AceEditorType::class, ['height' => 450, 'font_size' => 14,])
            ->add('pattern', AceEditorType::class, ['height' => 450, 'font_size' => 14,])
            ->add('match', SubmitType::class, ['label' => 'Match']);
    }

    public function getBlockPrefix()
    {
        return 'form';
    }
}