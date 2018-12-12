<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{





    /**
     * allows to easly configure labels and placeholders in ->add()
     * merges label & placeholder with options array(empty by default).
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function labelPlaceholderConfig($label, $placeholder, $options=[]){
        return array_merge([
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ], $options);
    }





    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                 TextType::class,
                  $this->labelPlaceholderConfig('Titre', 'Définissez le titre de l\'offre')
                  )
                  //sluf feild unneeded since slug is automatically generated with title.
           /* ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Tapez l'adresse du site (automatique)", [
                    'required' => false
                ])
            )*/
            ->add(
                'introduction',
                 TextType::class,
                  $this->labelPlaceholderConfig('Introduction', 'Décrivez une brève introduction de l\'offre')
                  )
            ->add(
                'content',
                 TextareaType::class,
                  $this->labelPlaceholderConfig('Contenu', 'Décrivez les détails de l\'offre')
                  )
            ->add(
                'coverImage',
                 UrlType::class,
                  $this->labelPlaceholderConfig('Url Photo', 'Url de la photo principale')
                  )
            ->add(
                'daysPerMission',
                 IntegerType::class,
                  $this->labelPlaceholderConfig('Nombre de jours', 'Durée totale de la mission (en jours)')
                  )
            ->add(
                'hoursPerDay',
                 IntegerType::class,
                  $this->labelPlaceholderConfig('Durée quotidienne', 'Durée de travail par jour (en heures)')
                  ) 
            ->add( //created new form class ImageType 
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                    
                ]
                )
        ;
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
