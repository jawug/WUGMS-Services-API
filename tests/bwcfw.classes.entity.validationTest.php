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
    public function testvalidateStringLength(): void {
        $this->assertEquals(
                'abcd', (new entity_validation())->validateStringLength('abcd', 4)
        );
    }

    public function testInvalidateStringLength(): void {
        $this->expectException(Exception::class);
        (new entity_validation())->validateStringLength('abcd', 3);
    }

}
