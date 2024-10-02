# Lithe Validator

A simple validation class for PHP designed to validate various types of input data according to specified rules.

## Installation

You can install the Lithe Validator module via Composer. Run the following command in your project directory:

```bash
composer require lithemod/validator
```

## Usage

### Step 1: Include Autoloader

Make sure to include the Composer autoloader in your PHP script:

```php
require 'vendor/autoload.php';
```

### Step 2: Initialize the Validator

Create an instance of the `Validator` class by passing the data and the validation rules you want to apply. 

```php
use Lithe\Base\Validator;

$data = [
    'email' => 'example@example.com',
    'name' => 'John Doe',
    'age' => '25',
];

$rules = [
    'email' => 'required|email',
    'name' => 'required|name',
    'age' => 'required|integer|min:18|max:65',
];

$validator = new Validator($data, $rules);
```

### Step 3: Check Validation

You can check if all validation rules pass using the `passed()` method:

```php
if ($validator->passed()) {
    echo "Validation passed!";
} else {
    echo "Validation failed!";
    print_r($validator->errors());
}
```

### Supported Validation Rules

- **`required`**: Checks if the field is present and not empty.  
  **Error Code**: 1001
- **`email`**: Validates the format of an email address.  
  **Error Code**: 1002
- **`url`**: Validates the format of a URL.  
  **Error Code**: 1003
- **`ip`**: Validates the format of an IP address.  
  **Error Code**: 1004
- **`number`**: Checks if the field is a numeric value.  
  **Error Code**: 1005
- **`integer`**: Checks if the field is an integer.  
  **Error Code**: 1006
- **`boolean`**: Validates if the field is a boolean value.  
  **Error Code**: 1007
- **`min`**: Validates the minimum length of the field value.  
  **Error Code**: 1009
- **`max`**: Validates the maximum length of the field value.  
  **Error Code**: 1010
- **`range`**: Checks if the field value is within a specified range.  
  **Error Code**: 1011
- **`dateFormat`**: Validates the date format.  
  **Error Code**: 1012
- **`alphanumeric`**: Checks if the field value is alphanumeric.  
  **Error Code**: 1013
- **`name`**: Validates that the field contains only letters and spaces.  
  **Error Code**: 1014
- **`in`**: Checks if the field value is one of the allowed values.  
  **Error Code**: 1015

## Example

```php
$data = [
    'email' => 'user@example.com',
    'name' => 'Alice',
    'age' => '30',
];

$rules = [
    'email' => 'required|email',
    'name' => 'required|name',
    'age' => 'required|integer|min:18|max:65',
];

$validator = new Validator($data, $rules);

if ($validator->passed()) {
    echo "All validations passed!";
} else {
    echo "There were validation errors:";
    print_r($validator->errors());
}
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.