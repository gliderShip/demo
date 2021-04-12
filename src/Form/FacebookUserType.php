<?php

namespace App\Form;

use App\Entity\FacebookUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacebookUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('countryLeak')
            ->add('mobile')
            ->add('facebookId')
            ->add('firstName')
            ->add('lastName')
            ->add('sex')
            ->add('currentDistrict')
            ->add('currentCountry')
            ->add('currentState')
            ->add('hometownDistrict')
            ->add('hometownCountry')
            ->add('hometownState')
            ->add('relationshipStatus')
            ->add('workCompany')
            ->add('date10')
            ->add('email')
            ->add('birthDate')
            ->add('facebookCurrentAddress')
            ->add('facebookHometownAddress')
            ->add('facebookBirthDate')
            ->add('revWork')
            ->add('soundex')
            ->add('revSoundex')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FacebookUser::class,
        ]);
    }
}
