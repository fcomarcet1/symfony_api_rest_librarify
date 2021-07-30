<?php

namespace App\Form\Type;

use App\Form\Model\CategoryDto;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add('name', TextType::class);

        $builder->get('id')->addModelTransformer(new CallbackTransformer(
                function ($id) {
                    if (null === $id) {
                        return '';
                    }

                    return $id->toString();
                },
                function ($id) {
                    return null === $id ? null : Uuid::fromString($id);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategoryDto::class,
            'csrf_protection' => false,
        ]);
    }

    // soluciona tener que enviar el nombre del form de symfony form_book
    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}
