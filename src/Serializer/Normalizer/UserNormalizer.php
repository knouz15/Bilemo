<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    
    
    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';
  
    
    public function __construct(
        private UrlGeneratorInterface $urlGenerator, 
        private ObjectNormalizer $objectNormalizer)
    {

    }

        /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        $data['_links']['self'] = $this->urlGenerator->generate('detailUser', [
            'id' => $object->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['delete'] = $this->urlGenerator->generate('deleteUser', [
            'id' => $object->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['add'] = $this->urlGenerator->generate('createUser', [
            
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof User ;
    }

}
