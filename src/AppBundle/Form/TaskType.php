<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Controller\DefaultController as Controller;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        Permet de changer les index numériques en index associatifs pour la gestion du menu déroualant
        foreach ($options['tache_etat'] as $index => $option) {
            $options['tache_etat'][$option] = $option;
            unset($options['tache_etat'][$index]);
        }
        

        $builder->add('tache')->add('etat', ChoiceType::class, array(
        'choices'  => $options['tache_etat']))->add('echeance');
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task'
        ));

        $resolver->setRequired('tache_etat');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_task';
    }
}
