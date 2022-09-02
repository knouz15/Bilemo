<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class UserNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface, CacheableSupportsMethodInterface
{
    
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'USER_NORMALIZER_ALREADY_CALLED';
    private  $router;
    
    public function __construct(UrlGeneratorInterface $router )
    {

        $this->router = $router;
        
    }

        /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;
        $data = $this->normalizer->normalize($object, $format, $context);

        // TODO: add, edit, or delete some data
        $data['href']['self'] = $this->router->generate('detailUser', [
            'id' => $user->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $data['href']['delete'] = $this->router->generate('deleteUser', [
            'id' => $user->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return false;
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof User ;//\App\Entity\User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
