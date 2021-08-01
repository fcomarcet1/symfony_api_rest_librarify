<?php

// Normalizer = tranform entity to Json

namespace App\Serializer;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BookNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private UrlHelper $urlHelper;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($book, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($book, $format, $context);

        // Añadimos campos a normalizar
        // $data['messageTest'] = 'Hola Mundo';
        if (!empty($book->getImage())) {
            // return complete image path
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/images/books/'.$book->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Book;
    }
}
