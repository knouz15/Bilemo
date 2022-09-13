<?php

namespace App\Serializer\Normalizer;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
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
     * @param SlidingPagination $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;
        $data = [];
        foreach($object->getItems() as $i => $item)
        {
            $data['items'] [$i]= $this->objectNormalizer->normalize($item, $format, $context);
            $data['items'] [$i]['_links']['self'] = $this->urlGenerator->generate('detailPhone', [
                'id' => $item->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
    
        $data['_links']['FirstPage'] = $this->urlGenerator->generate($object->getRoute(), [
            'page' => 1,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($object->getCurrentPageNumber() > 1)
            $data['_links']['PreviousPage'] = $this->urlGenerator->generate($object->getRoute(), [
            'page' => $object->getCurrentPageNumber()-1,
            ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['CurrentPage'] = $this->urlGenerator->generate($object->getRoute(), [
            'page' => $object->getCurrentPageNumber(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        if ($object->getCurrentPageNumber() < $object->getPageCount())
            $data['_links']['NextPage'] = $this->urlGenerator->generate($object->getRoute(), [
            'page' => $object->getCurrentPageNumber()+1,
            ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_links']['LastPage'] = $this->urlGenerator->generate($object->getRoute(), [
            'page' => $object->getPageCount(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['_totalItems'] = $object->getTotalItemCount();

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof  SlidingPagination;
    }

}
