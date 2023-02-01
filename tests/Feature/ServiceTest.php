<?php

namespace Tests\Feature;

use App\Http\Services\MaskService;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * Проверка правильности работы проверки маски на серийном номере (верный результат)
     *
     * @return void
     */
    public function test_try_to_check_correct_serial()
    {
        $serialNumber = '1ABCD2@345';
        $mask = 'NAAAAXZXXX';

        $actual = MaskService::checkSerial($serialNumber, $mask);

        $this->assertTrue($actual);
    }

    /**
     * Проверка правильности работы проверки маски на серийном номере (не верный результат)
     *
     * @return void
     */
    public function test_try_to_check_incorrect_serial()
    {
        $serialNumber = '1xBCD2@345';
        $mask = 'NAAAAXZXXX';

        $actual = MaskService::checkSerial($serialNumber, $mask);

        $this->assertNotTrue($actual);
    }
}
