<?php

namespace Lithe\Tests\Base;

use Lithe\Base\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    protected array $data;
    protected array $rules;

    protected function setUp(): void
    {
        $this->data = [
            'email' => 'test@example.com',
            'url' => 'https://example.com',
            'ip' => '192.168.1.1',
            'number' => '123',
            'integer' => '42',
            'boolean' => 'true',
            'name' => 'John Doe',
            'range' => '10',
            'date' => '2024-09-16',
            'category' => 'admin',
        ];

        $this->rules = [
            'email' => 'required|email',
            'url' => 'required|url',
            'ip' => 'required|ip',
            'number' => 'required|number',
            'integer' => 'required|integer',
            'boolean' => 'required|boolean',
            'name' => 'required|name',
            'range' => 'required|range:5,15',
            'date' => 'required|dateFormat:Y-m-d',
            'category' => 'required|in:admin,user'
        ];
    }

    public function testValidations()
    {
        $validator = new Validator($this->data, $this->rules);
        $this->assertTrue($validator->passed(), 'Validator should pass with valid data.');
        $this->assertEmpty($validator->errors(), 'Validator should not have any errors.');
    }

    public function testValidationErrors()
    {
        // Test with invalid data
        $invalidData = [
            'email' => 'invalid-email',
            'url' => 'invalid-url',
            'ip' => 'invalid-ip',
            'number' => 'invalid-number',
            'integer' => 'invalid-integer',
            'boolean' => 'invalid-boolean',
            'name' => 'John123',
            'range' => '20',
            'date' => '2024/09/16',
            'category' => 'unknown'
        ];

        $validator = new Validator($invalidData, $this->rules);
        $this->assertFalse($validator->passed(), 'Validator should fail with invalid data.');
        $this->assertNotEmpty($validator->errors(), 'Validator should have errors.');
    }
}
