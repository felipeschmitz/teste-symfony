<?php

namespace App\Form;

use App\Entity\Phone;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class, ['label' => 'Usuários', 'class' => User::class, 'choice_label' => 'name'])
            ->add('ddd', null, ['label' => 'DDD'])
            ->add('number', null, ['label' => 'Telefone'])
            ->add('created_at', null, ['label' => 'Cadastrado em'])
            ->add('updated_at', null, ['label' => 'Alterado em'])
            ->add('submit', SubmitType::class, ['label' => 'Salvar'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
        ]);
    }
}
