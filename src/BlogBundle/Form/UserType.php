<?php
namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: Maxence
 * Date: 30/05/2017
 * Time: 17:09
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('password');
        //parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array('data_class' => 'BlogBundle\Entity\User',));
        // parent::configureOptions($resolver); // TODO: Change the autogenerated stub
    }
}