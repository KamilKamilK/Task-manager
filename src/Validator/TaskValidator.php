<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskValidator
{

//    public function rules(): array
//    {
//        return [
//            'title' => new Assert\NotBlank(),
//            'details' => new Assert\NotBlank(),
//            'deadline' => new Assert\NotBlank(),
//        ];
//    }

//    private RequestStack $requestStack;
//    private ValidatorInterface $validator;

//    public function __construct(RequestStack $requestStack, ValidatorInterface $validator) {
//
//        $this->requestStack = $requestStack;
//        $this->validator = $validator;
//        parent::__construct();
//    }
    public function validate(array $params): bool
    {
//        $request = $this->requestStack->getCurrentRequest();

        $constraints = ( [
            'title' => new Assert\NotBlank(null, 'This field should not be empty'),
            'details' => new Assert\NotBlank(null, 'This field should not be empty'),
            'deadline' => new Assert\NotBlank(null, 'This field should not be empty'),
        ]);

        $errors = $this->validator->validate($params,$constraints);

        return count($errors) === 0;
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }
}