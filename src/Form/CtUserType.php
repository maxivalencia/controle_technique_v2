<?php

namespace App\Form;

use App\Entity\CtUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $enable = [
            'Oui' => true,
            'Non' => false
        ];
        $roles = [
            'Super-admin' => '[SUPERADMIN]',
            'Admin' => '[ADMIN]',
            'Staff' => '[STAFF]',
            'Appro' => '[APPROVISIONNEMENT]',
            'Regisseur' => '[REGISSEUR]',
            'RT' => '[RECEPTION]',
            'VT' => '[VISITE]',
            'CAD' => '[CONSTATATION]',
            'VT-RT' => '[VISITE-RECEPTION]',
            'VT-CAD' => '[VISITE-CONSTATATION]',
            'RT-CAD' => '[RECEPTION-CONSTATATION]',
            'VT-RT-CAD' => '[VISITE-RECEPTION-CONSTATATION]',
        ];
        $builder
            ->add('username', null, [
                'label' => 'Nom d\'utilisateur',
            ])
            /* ->add('rolesimple', null, [
                'label' => 'Rôles',
                'choices' => $roles,
            ]) */
            ->add('ct_role_id', null, [
                'label' => 'Rôles',
            ])
            ->add('password', RepeatedType::class, [
                'label' => 'Mot de passe',
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de pass sont différent.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Veuillez entrer le Mot de passe'],
                'second_options' => ['label' => 'Veuillez repeter le Mot de passe'],
            ])
            ->add('usr_enable', ChoiceType::class, [
                'label' => 'Est-active',
                'choices' => $enable,
                'data' => false,
            ])
            ->add('usr_last_login', null, [
                'label' => 'Dernière connexion',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('usr_mail', null, [
                'label' => 'E-mail',
            ])
            ->add('usr_nom', null, [
                'label' => 'Nom',
            ])
            ->add('usr_adresse', null, [
                'label' => 'Adresse',
            ])
            ->add('usr_created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('usr_updated_at', DateTimeType::class, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('usr_telephone', null, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('usr_nbr_connexion', null, [
                'label' => 'Nombre de connexion',
                'data' => 0,
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtUser::class,
        ]);
    }
}
