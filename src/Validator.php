<?php

namespace Lithe\Base;

use Lithe\Support\Log;

class Validator
{
    protected array $data; // Data to be validated
    protected array $rules; // Validation rules
    protected array $error = []; // Stores validation errors
    protected array $errorCode = [
        'required' => 1001,
        'email' => 1002,
        'url' => 1003,
        'ip' => 1004,
        'number' => 1005,
        'integer' => 1006,
        'boolean' => 1007,
        'min' => 1009,
        'max' => 1010,
        'range' => 1011,
        'dateFormat' => 1012,
        'alphanumeric' => 1013,
        'name' => 1014, // New error code for name validation
        'in' => 1015,   // New error code for 'in' validation
    ];

    // Constructor initializes the validator with data and rules
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    // Check if all validation rules pass
    public function passed(): bool
    {
        foreach ($this->rules as $field => $rules) {
            $rulesArray = explode('|', $rules);
            foreach ($rulesArray as $rule) {
                $ruleParams = explode(':', $rule);
                $ruleName = $ruleParams[0];
                $params = $ruleParams[1] ?? '';

                $methodName = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $methodName)) {
                    if (!$this->$methodName($field, $params)) {
                        $this->addError($field, $ruleName);
                    }
                } else {
                    $message = "Validation rule {$ruleName} not supported.";
                    Log::error($message);
                    throw new \Exception($message);
                }
            }
        }
        return empty($this->error); // Return true if no errors are found
    }

    // Add an error to the list of errors
    protected function addError(string $field, string $rule): void
    {
        $code = $this->errorCode[$rule] ?? 9999; // Use 9999 for unknown errors
        $this->error[$field][] = $code;
    }

    // Get all validation errors
    public function errors(): array
    {
        return $this->error;
    }

    // Validate email format
    protected function validateEmail(string $field, $params): bool
    {
        return filter_var($this->data[$field], FILTER_VALIDATE_EMAIL) !== false;
    }

    // Check if the field is required and not empty
    protected function validateRequired(string $field, $params): bool
    {
        return isset($this->data[$field]) && !empty($this->data[$field]);
    }

    // Validate URL format
    protected function validateUrl(string $field, $params): bool
    {
        return filter_var($this->data[$field], FILTER_VALIDATE_URL) !== false;
    }

    // Validate IP address format
    protected function validateIp(string $field, $params): bool
    {
        return filter_var($this->data[$field], FILTER_VALIDATE_IP) !== false;
    }

    // Validate if the field is a numeric value
    protected function validateNumber(string $field, $params): bool
    {
        return is_numeric($this->data[$field]);
    }

    // Validate if the field is an integer
    protected function validateInteger(string $field, $params): bool
    {
        return filter_var($this->data[$field], FILTER_VALIDATE_INT) !== false;
    }

    // Validate if the field is a boolean value
    protected function validateBoolean(string $field, $params): bool
    {
        return is_bool(filter_var($this->data[$field], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }

    // Validate minimum length of the field value
    protected function validateMin(string $field, $min): bool
    {
        return strlen($this->data[$field]) >= (int)$min;
    }

    // Validate maximum length of the field value
    protected function validateMax(string $field, $max): bool
    {
        return strlen($this->data[$field]) <= (int)$max;
    }

    // Validate if the field value is within a specified range
    protected function validateRange(string $field, $params): bool
    {
        [$min, $max] = explode(',', $params);
        $value = $this->data[$field];
        return is_numeric($value) && $value >= (int)$min && $value <= (int)$max;
    }

    // Validate date format
    protected function validateDateFormat(string $field, $format): bool
    {
        $d = \DateTime::createFromFormat($format, $this->data[$field]);
        return $d && $d->format($format) === $this->data[$field];
    }

    // Validate if the field value is alphanumeric
    protected function validateAlphanumeric(string $field, $params): bool
    {
        return ctype_alnum($this->data[$field]);
    }

    // Validate if the field value contains only letters and spaces
    protected function validateName(string $field, $params): bool
    {
        return preg_match('/^[\p{L}\s]+$/u', $this->data[$field]) === 1;
    }

    // Validate if the field value is one of the allowed values
    protected function validateIn(string $field, $params): bool
    {
        $allowedValues = explode(',', $params);
        return in_array($this->data[$field], $allowedValues, true);
    }
}
