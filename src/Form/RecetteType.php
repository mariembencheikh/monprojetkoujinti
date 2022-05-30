<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\TypeRecette;
use App\Form\TypeRecetteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
           
            ->add('image', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                       
                        'mimeTypesMessage' => "S'il vous plait telecharger une image valide",
                    ])
                ],
            ])
            ->add('typeRecette', EntityType::class, [
                // looks for choices from this entity
                'class' => TypeRecette::class,
                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
            ])
            ->add('description',TextareaType::class,[
                'required' => true,
                'attr' => array('cols' => '5', 'rows' => '12'),

                
            ])
            ->add('ingredients',TextareaType::class,[
                'required' => true,
                'attr' => array('cols' => '5', 'rows' => '6'),
                
            ])
            // ...
        ;
    }
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
