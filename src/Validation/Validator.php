<?php

namespace Framework\Base\Validation;

use Framework\Base\Application\ApplicationAwareTrait;
use Framework\Base\Application\Exception\ValidationException;
use Framework\Base\Validation\Validations\AlphabeticValidation;
use Framework\Base\Validation\Validations\AlphaDashValidation;
use Framework\Base\Validation\Validations\AlphaNumericValidation;
use Framework\Base\Validation\Validations\ArrayValidation;
use Framework\Base\Validation\Validations\BooleanValidation;
use Framework\Base\Validation\Validations\EmailValidation;
use Framework\Base\Validation\Validations\FloatValidation;
use Framework\Base\Validation\Validations\IntegerValidation;
use Framework\Base\Validation\Validations\NonEmptyValidation;
use Framework\Base\Validation\Validations\NumericValidation;
use Framework\Base\Validation\Validations\StringValidation;
use Framework\Base\Validation\Validations\UniqueValidation;
use Framework\Base\Validation\Validations\ValidationInterface;

/**
 * Class Validator
 * @package Framework\Base\Validation
 */
class Validator implements ValidatorInterface
{
    use ApplicationAwareTrait;

    /**
     * @var ValidationInterface[]
     */
    private $validations = [];

    /**
     * List of values and Validations that failed
     * @var array
     */
    private $failed = [];

    /**
     * Translation string to fully qualified Class name
     * @var array
     */
    private static $translator = [
        'string' => StringValidation::class,
        'int' => IntegerValidation::class,
        'integer' => IntegerValidation::class,
        'float' => FloatValidation::class,
        'bool' => BooleanValidation::class,
        'boolean' => BooleanValidation::class,
        'alphabetic' => AlphabeticValidation::class,
        'array' => ArrayValidation::class,
        'nonempty' => NonEmptyValidation::class,
        'email' => EmailValidation::class,
        'unique' => UniqueValidation::class,
        'alpha_numeric' => AlphaNumericValidation::class,
        'numeric' => NumericValidation::class,
        'alpha_dash' => AlphaDashValidation::class,
    ];

    /**
     * @param        $value
     * @param string $rule
     *
     * @return ValidatorInterface
     */
    public function addValidation($value, string $rule): ValidatorInterface
    {
        $validation = $this->translate(strtolower($rule));
        $validationInstance = new $validation($value);

        if ($validationInstance instanceof UniqueValidation) {
            $validationInstance->setApplication($this->getApplication());
        }

        $this->validations[] = $validationInstance;

        return $this;
    }

    /**
     * Checks if selected validation rule exists
     *
     * @param string $type
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function translate(string $type): string
    {
        if (isset(self::$translator[$type]) === false) {
            throw new \InvalidArgumentException('Validation rule not supported');
        }

        return self::$translator[$type];
    }

    /**
     * Checks validity of each Validation Rule selected, throws ValidationException with all failed rules
     *
     * @return ValidatorInterface
     * @throws ValidationException
     */
    public function validate(): ValidatorInterface
    {
        foreach ($this->getValidations() as $validation) {
            if ($validation->isValid() === false) {
                $this->setFailed($validation);
            }
        }

        if (count($this->getFailed()) !== 0) {
            $exception = new ValidationException('Validation failed');
            $exception->setFailedValidations($this->getFailed());
            throw $exception;
        }

        return $this;
    }

    /**
     * @return ValidationInterface[]
     */
    public function getValidations(): array
    {
        return $this->validations;
    }

    /**
     * @return array
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * @param ValidationInterface $validation
     *
     * @return ValidatorInterface
     */
    public function setFailed(ValidationInterface $validation): ValidatorInterface
    {
        $this->failed[$validation->getRuleName()] = $validation->getValue();

        return $this;
    }
}
