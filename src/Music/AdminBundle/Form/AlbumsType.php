<?php

namespace Music\AdminBundle\Form;

use Music\AdminBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbumsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = new Category();
        $builder
            ->add('group_name')
            ->add('track_name')
            ->add('category')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Music\AdminBundle\Entity\Albums'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'music_adminbundle_albums';
    }
}
