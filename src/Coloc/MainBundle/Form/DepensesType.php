<?php

namespace Coloc\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DepensesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('paye_par', ChoiceType::class, array('choices' =>
            array('Eva' => 0, 'Robin' => 1, 'Sylvain' => 2) ))
            ->add('nom')->add('date')->add('nbPartRobin')->add('nbPartEva')->add('nbPartSylvain')
            ->add('nbPartAutres')->add('montant')->add('Enregistrer', SubmitType::class);;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Coloc\MainBundle\Entity\Depenses'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coloc_mainbundle_depenses';
    }


}
