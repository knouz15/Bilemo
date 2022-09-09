<?php

namespace App\Serializer\Normalizer;

use App\Entity\Phone;
// use App\Paginated;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class PaginatedNormalizer implements ContextAwareNormalizerInterface
{
    
    
    private const ALREADY_CALLED = 'PAGINATED_NORMALIZER_ALREADY_CALLED';
  
    
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

        $data['_links']['self'] = $this->urlGenerator->generate('page', [
            
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['FirstPage'] = $this->urlGenerator->generate(1, [
            
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $data['_links']['NextPage'] = $this->urlGenerator->generate('page'+1, [
            'id' => $object->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['PreviousPage'] = $this->urlGenerator->generate('page'-1, [
            'id' => $object->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['LastPage'] = $this->urlGenerator->generate('pageRange', [
            
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof Phone ;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
