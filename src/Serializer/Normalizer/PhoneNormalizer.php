<?php

namespace App\Serializer\Normalizer;

use App\Entity\Phone;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PhoneNormalizer implements ContextAwareNormalizerInterface
{
    
    
    private const ALREADY_CALLED = 'PHONE_NORMALIZER_ALREADY_CALLED';
  
    
    public function __construct(
        private UrlGeneratorInterface $urlGenerator, 
        private ObjectNormalizer $objectNormalizer)
    {

    }

        /**
     * @param Phone $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        $data['_links']['self'] = $this->urlGenerator->generate('detailPhone', [
            'id' => $object->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        
        return $data instanceof Phone ;//\App\Entity\Phone;
    }

}
