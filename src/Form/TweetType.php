<?php

namespace App\Form;

use App\Entity\Tweet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class TweetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tweet',TextareaType::class)
            ->add('image',FileType::class , [
                'mapped' => false,
                'required' =>false
            ])
            ->add('Post' , SubmitType::class , [
                 'attr' => [
                     'class' => 'btn btn-success float-right'
                 ]
             ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tweet::class,
        ]);
    }
}
