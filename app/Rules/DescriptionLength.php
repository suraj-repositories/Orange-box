<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DescriptionLength implements ValidationRule
{
    protected $maxLength;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct($maxLength = null)
    {
        $this->maxLength = $maxLength ?? config('validation_rules.description_max_length', 10000);;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if (strlen($value) > $this->maxLength) {
            $fail("The {$attribute} must not exceed {$this->maxLength} characters.");
        }
    }
}
