<?php

namespace Framework\Base\Validation;

use Framework\Base\Validation\Validations\ValidationInterface;

/**
 * Interface ValidatorInterface
 * @package Framework\Base\Validation
 */
interface ValidatorInterface
{
    /**
     * @param        $value
     * @param string $rule
     *
     * @return ValidatorInterface
     */
    public function addValidation($value, string $rule): ValidatorInterface;

    /**
     * @return ValidatorInterface
     */
    public function validate(): ValidatorInterface;

    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array;

    /**
     * @return array
     */
    public function getFailed(): array;

    /**
     * @param ValidationInterface $validation
     *
     * @return ValidatorInterface
     */
    public function setFailed(ValidationInterface $validation): ValidatorInterface;
}
