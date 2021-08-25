<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\Therapist;
use App\Helper\DateTimeHelper;
use App\Repository\ServiceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TherapistType extends AbstractType
{
    const MON = 1;
    const TUE = 2;
    const WED = 3;
    const THU = 4;
    const FRI = 5;

    const DAYS = [
        self::MON => 'Monday',
        self::TUE => 'Tuesday',
        self::WED => 'Wednesday',
        self::THU => 'Thursday',
        self::FRI => 'Friday',
    ];

    /**
     * @var ServiceRepository
     */
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('bio', TextareaType::class)
            ->add('services', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'title',
                'choices' => $this->serviceRepository->findAll(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('days', ChoiceType::class, [
                'choices' => array_flip(self::DAYS),
                'expanded' => true,
                'multiple' => true,
                'choice_attr' => function($choice, $key, $value){
                    return ['style' => 'display: none;'];
                },
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Therapist::class
        ]);
    }
}