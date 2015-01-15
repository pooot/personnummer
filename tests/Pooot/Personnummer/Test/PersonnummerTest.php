<?php

namespace Pooot\Personnummer\Test;

use Pooot\Personnummer\Personnummer;

class PersonnummerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function control_digit()
    {
        $this->assertTrue(Personnummer::validate(6403273813));
        $this->assertTrue(Personnummer::validate('510818-9167'));
        $this->assertTrue(Personnummer::validate('19900101-0017'));
        $this->assertTrue(Personnummer::validate('19130401+2931'));
        $this->assertTrue(Personnummer::validate('196408233234'));
        $this->assertFalse(Personnummer::validate(640327381));
        $this->assertFalse(Personnummer::validate('510818-916'));
        $this->assertFalse(Personnummer::validate('19900101-001'));
        $this->assertFalse(Personnummer::validate('100101+001'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function wrong_type()
    {
        $this->assertFalse(Personnummer::validate(null));
        $this->assertFalse(Personnummer::validate([]));
        $this->assertFalse(Personnummer::validate(true));
        $this->assertFalse(Personnummer::validate(false));
        $this->assertFalse(Personnummer::validate(100101001));
        $this->assertFalse(Personnummer::validate('112233-4455'));
        $this->assertFalse(Personnummer::validate('19112233-4455'));
        $this->assertFalse(Personnummer::validate('9999999999'));
        $this->assertFalse(Personnummer::validate('199999999999'));
        $this->assertFalse(Personnummer::validate('9913131315'));
        $this->assertFalse(Personnummer::validate('9911311232'));
        $this->assertFalse(Personnummer::validate('9902291237'));
        $this->assertFalse(Personnummer::validate('19990919_3766'));
        $this->assertFalse(Personnummer::validate('990919_3766'));
        $this->assertFalse(Personnummer::validate('199909193776'));
        $this->assertFalse(Personnummer::validate('Just a string'));
        $this->assertFalse(Personnummer::validate('990919+3776'));
        $this->assertFalse(Personnummer::validate('990919-3776'));
        $this->assertFalse(Personnummer::validate('9909193776'));
    }

    /**
     * @test
     */
    public function corporate_identity_number()
    {
        $this->assertTrue(Personnummer::validate('701063-2391'));
        $this->assertTrue(Personnummer::validate('640883-3231'));
        $this->assertFalse(Personnummer::validate('900161-0017'));
        $this->assertFalse(Personnummer::validate('640893-3231'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function gender()
    {
        $this->assertEquals('M', Personnummer::gender('890213-7812'));
        $this->assertEquals('F', Personnummer::gender('890213-3506'));
        $this->assertFalse(Personnummer::gender('890213-3516'));
    }


}