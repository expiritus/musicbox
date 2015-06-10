<?php

namespace Music\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', 'text', array(
                'attr' => array(
                    'placeholder' => 'Введите слово или букву',
                    'class' => 'search_input'
                ),
                'label' => false,
                'required' => false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Music\AdminBundle\Entity\Category'
//        ));
//    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'music_adminbundle_search';
    }
}