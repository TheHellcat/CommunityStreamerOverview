<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddScheduleForm
 * @package AppBundle\Form
 */
class AddScheduleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->add('dayOfWeek', ChoiceType::class, [
                'label' => 'Day',
                'required' => true,
                'choices' => [
                    'Monday' => 0,
                    'Tuesday' => 1,
                    'Wednesday' => 2,
                    'Thursday' => 3,
                    'Friday' => 4,
                    'Saturday' => 5,
                    'Sunday' => 6,
                ]
            ])
            ->add('timeStart', ChoiceType::class, [
                'label' => 'Start Time',
                'required' => true,
                // TODO: generate this in a neat loop
                'choices' => [
                    '00' => 0,
                    '01' => 1,
                    '02' => 2,
                    '03' => 3,
                    '04' => 4,
                    '05' => 5,
                    '06' => 6,
                    '07' => 7,
                    '08' => 8,
                    '09' => 9,
                    '10' => 10,
                    '11' => 11,
                    '12' => 12,
                    '13' => 13,
                    '14' => 14,
                    '15' => 15,
                    '16' => 16,
                    '17' => 17,
                    '18' => 18,
                    '19' => 19,
                    '20' => 20,
                    '21' => 21,
                    '22' => 22,
                    '23' => 23
                ]
            ])
            ->add('timeEnd', ChoiceType::class, [
                'label' => 'End Time',
                'required' => true,
                // TODO: generate this in a neat loop
                'choices' => [
                    '00' => 0,
                    '01' => 1,
                    '02' => 2,
                    '03' => 3,
                    '04' => 4,
                    '05' => 5,
                    '06' => 6,
                    '07' => 7,
                    '08' => 8,
                    '09' => 9,
                    '10' => 10,
                    '11' => 11,
                    '12' => 12,
                    '13' => 13,
                    '14' => 14,
                    '15' => 15,
                    '16' => 16,
                    '17' => 17,
                    '18' => 18,
                    '19' => 19,
                    '20' => 20,
                    '21' => 21,
                    '22' => 22,
                    '23' => 23
                ]
            ])
            ->add('topic', TextType::class, [
                'label' => 'Topic'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Model\Form\AddSchedule'
        ));
    }
}
