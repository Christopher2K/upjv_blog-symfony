<?php
/**
 * Created by PhpStorm.
 * User: christopher
 * Date: 11/06/2017
 * Time: 17:33
 */

namespace BlogBundle\Form;


use BlogBundle\Entity\UserTheme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $themes = $options['themes'];
        $builder
            ->add('theme', EntityType::class, [
                'class' => 'BlogBundle\Entity\Theme',
                'choices' => $themes,
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('themes');
        $resolver->setDefaults([
            'data_class' => UserTheme::class
        ]);
    }

}