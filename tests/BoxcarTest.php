<?php

use MX\Push\PushFactory;

ini_set('error_reporting', 2147483647);
ini_set('display_errors', '1');

class BoxcarTest extends PHPUnit_Framework_TestCase
{
    public function testPayloadIsCorrectlyGenerated()
    {
        $push = PushFactory::make('Boxcar2');
        $tokens = array(str_repeat('0', 20));
        $testTitle = 'test';
        $testMessage = 'test';

        $payload = $push->prepare($tokens, $testTitle, $testMessage);
        $payload = $payload[0];

        $this->assertArrayHasKey('user_credentials', $payload);
        $this->assertInternalType('string', $payload['user_credentials']);

        $this->assertArrayHasKey('notification', $payload);
        $this->assertInternalType('array', $payload['notification']);
        $this->assertArrayHasKey('title', $payload['notification']);
        $this->assertInternalType('string', $payload['notification']['title']);
        $this->assertArrayHasKey('long_message', $payload['notification']);
        $this->assertInternalType('string', $payload['notification']['long_message']);
        $this->assertArrayHasKey('sound', $payload['notification']);
        $this->assertInternalType('string', $payload['notification']['sound']);

    }

    // public function testSendPayloadToMax13()
    // {
    //     $push = PushFactory::make('Boxcar2');
    //     $tokens = array('eTxjO7N54cfCbQyvOT8');

    //     $payload = $push->prepare($tokens, 'Title', 'THIS IS A LONGER MESSAGE');

    //     $push->send($payload[0]);
    //     $this->assertEquals(201, $push->lastApiCode());
    // }
}
