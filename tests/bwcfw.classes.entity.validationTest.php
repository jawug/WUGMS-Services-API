<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

/**
 * @covers entity_validation
 */
final class entity_validationTest extends TestCase {

    public function testValidateDate(): void {
        $this->assertEquals(
                '2017-08-11', (new entity_validation())->validateDate('2017-08-11')
        );
    }

    public function testInvalidateDate(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateDate('aaaaaa');
    }

//validateCheckNumeric

    public function testValidateCheckNumeric(): void {
        $this->assertEquals(
                '2017', (new entity_validation())->validateCheckNumeric('2017')
        );
    }

    public function testInvalidateCheckNumeric(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateCheckNumeric('aaaaaa');
    }

//validateStringNotEmpty    

    public function testValidateStringNotEmpty(): void {
        $this->assertEquals(
                'String is not empty', (new entity_validation())->validateStringNotEmpty('String is not empty')
        );
    }

    public function testInvalidateStringNotEmpty(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringNotEmpty(NULL);
    }

//validateStringLength
    public function testValidateStringLength(): void {
        $this->assertEquals(
                'abcd', (new entity_validation())->validateStringLength('abcd', 4)
        );
    }

    public function testInvalidateStringLength(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringLength('abcd', 3);
    }

//validateNumberBTV
    public function testValidateNumberBTV(): void {
        $this->assertEquals(
                TRUE, (new entity_validation())->validateNumberBTV(5, 1, 10)
        );
    }

    public function testInvalidateNumberBTV(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateNumberBTV(5, 10, 20);
    }

    //StartDateLTTEndDate
    public function testStartDateLTTEndDate(): void {
        $this->assertEquals(
                TRUE, (new entity_validation())->StartDateLTTEndDate('2017-08-20', '2017-08-21')
        );
    }

    public function testInvalidateStartDateLTTEndDate(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->StartDateLTTEndDate('2017-08-21', '2017-08-20');
    }

//    validateStringRegex
    public function testValidateStringRegex(): void {
        $this->assertEquals(
                'abcd abcd abcd', (new entity_validation())->validateStringRegex('abcd abcd abcd', '/^.{10,100}$/i')
        );
    }

    public function testInvalidateStringRegex(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringRegex('abcd', '/^.{10,100}$/i');
    }

    //    validateStringLengthMinMax
    public function testValidateStringLengthMinMax(): void {
        $this->assertEquals(
                'abcdabcd', (new entity_validation())->validateStringLengthMinMax('abcdabcd', 5, 10)
        );
    }

    public function testInvalidateStringLengthMinMax(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringLengthMinMax('abcd', 5, 10);
    }

    //    validateStringUnique
    public function testValidateStringUnique(): void {
        $this->assertEquals(
                TRUE, (new entity_validation())->validateStringUnique('abcdabcd', 'abcd')
        );
    }

    public function testInvalidateStringUnique(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringUnique('abcdabcd', 'abcdabcd');
    }

    //    validateStringSame
    public function testValidateStringSame(): void {
        $this->assertEquals(
                TRUE, (new entity_validation())->validateStringSame('abcdabcd', 'abcdabcd')
        );
    }

    public function testInvalidateStringSame(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringSame('abc', 'xyz');
    }

    //    validateEmail
    public function testValidateEmail(): void {
        $this->assertEquals(
                'address@somewhere.com', (new entity_validation())->validateEmail('address@somewhere.com')
        );
    }

    public function testInvalidateEmail(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateEmail('abc');
    }

}
