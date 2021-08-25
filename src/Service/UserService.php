<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\RegisterException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws RegisterException
     */
    public function registerUser(Request $request): bool
    {
        $firstName = $request->get('first-name');
        $lastName = $request->get('last-name');
        $email = $request->get('email');
        $password = $request->get('password');
        $confirmPassword = $request->get('confirm-password');

        if(!$firstName || !$lastName || !$email || !$password || !$confirmPassword) {
            throw new RegisterException('Some fields were empty');
        }

        if ($password !== $confirmPassword) {
            throw new RegisterException('Your passwords do not match');
        }

        $user = new User();
        $user->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setRoles(['ROLE_USER'])
            ->setPassword($password);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new RegisterException($errors[0]->message);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();


        return true;
    }
}