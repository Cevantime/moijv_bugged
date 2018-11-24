<?php

namespace App\Form;

use App\DataTransformers\TagTransformer;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Uerka\TranslationFormBundle\Form\Type\TranslationsType;

class ProductType extends AbstractType
{
    
    private $tagTransformer;
    
    public function __construct(TagTransformer $tagTranformer)
    {
        $this->tagTransformer = $tagTranformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('translations',TranslationsType::class, [
                'required_locales' => ['en'],
                'fields' => [
                    'title' => [
                        'widget_class' => TextType::class, // optional, default TextType::class
                        'options' => [ // will be passed to field's options
                            'label' => 'form_product.label.title',
                        ],
                    ],
                    'description' => [
                        'widget_class' => TextareaType::class,
                        'options' => [
                            'label' => 'form_product.label.description'
                        ]
                    ],
                ],
            ])
            ->add('image', FileType::class,[
                'required' => false,
                'label' => 'form_product.label.image'
            ])
            ->add('tags', TextType::class, [
                'label' => 'form_product.label.tags'
            ])
            ->get('tags')
                ->addModelTransformer($this->tagTransformer)
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
