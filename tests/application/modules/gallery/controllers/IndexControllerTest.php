<?php
class IndexControllerTest extends ControllerTestCase
{
    public function testDefaultModuleIsGallery()
    {
        $this->dispatch('/');
        
        $this->assertModule('gallery');
        $this->assertController('index');
        $this->assertAction('index');
    }

}