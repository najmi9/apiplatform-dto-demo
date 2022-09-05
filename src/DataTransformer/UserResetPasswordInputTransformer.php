<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\UserResetPasswordInput;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use UnexpectedValueException;

final class UserResetPasswordInputTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly UserRepository $userRepository
    ){
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $input = $context['input'] ?? null;
        $inputClass = $input['class'] ?? null;

        return User::class === $to && UserResetPasswordInput::class === $inputClass;
    }

    public function transform($object, string $to, array $context = []): User
    {
        if (!$object instanceof UserResetPasswordInput) {
            throw new UnexpectedValueException('Transformation operation not allowed');
        }

        $this->validator->validate($object);

        $user = $this->userRepository->findOneByEmail($object->getEmail());

        if (!$user instanceof UserInterface) {
            $message = sprintf('User with the email "%s" not found', $object->getEmail());
            throw new UnprocessableEntityHttpException($message);
        }

        // Send an email to reset the password here.

        return $user;
    }
}
