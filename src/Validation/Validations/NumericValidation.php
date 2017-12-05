<?php

namespace Framework\Base\Validation\Validations;

/**
 * Class NumericValidation
 * @package Framework\Base\Validation\Validations
 */
class NumericValidation extends Validation
{
    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return is_numeric($this->getValue()) === true;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return 'isNumeric';
    }
}
