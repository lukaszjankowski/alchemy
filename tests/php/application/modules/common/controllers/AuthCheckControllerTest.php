<?php
class AuthCheckControllerTest extends ControllerTestCase
{
    public function testReturnsDataInJsonFormat()
    {
        $this->dispatch('/common/authCheck');
//         $this->assertJsonResponse();
    }

}
