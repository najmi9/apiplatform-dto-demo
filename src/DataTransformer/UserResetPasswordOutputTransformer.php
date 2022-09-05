<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\UserResetPasswordOutput;
use App\Entity\User;
use UnexpectedValueException;

final class UserResetPasswordOutputTransformer implements DataTransformerInterface
{
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $output = $context['output'] ?? null;
        $outputClass = $output['class'] ?? null;

        return $data instanceof User && UserResetPasswordOutput::class === $outputClass;
    }

    public function transform($object, string $to, array $context = []): ?UserResetPasswordOutput
    {
        if (!$object instanceof User) {
            throw new UnexpectedValueException('Transformation operation not allowed');
        }

        return (new UserResetPasswordOutput())
            ->setEmail($object->getEmail())
            ->setId($object->getId())
        ;
    }
}
