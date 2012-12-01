<?php
class ContextSwitchControllerTest extends ControllerTestCase
{
    public function testReturnsDataInJsonFormat()
    {
        $this->dispatch('/common/contextSwith');
    }

}
