<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookForm extends AbstractType
{
    // Skapar formuläret med fyra fält
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($options);
        $builder->add('title');
        $builder->add('isbn');
        $builder->add('author');
        $builder->add('image');
    }

    // Sätter vilken datatyp som formuläret jobbar med
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
