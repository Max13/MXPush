<?php

use MX\Push\PushFactory;

ini_set('error_reporting', 2147483647);
ini_set('display_errors', '1');

class UrbanAirshipTest extends PHPUnit_Framework_TestCase
{
    public function testIosPayloadIsCorrectlyGenerated()
    {
        $push = PushFactory::make('UrbanAirship', array(
            'baseUrl',
            'key',
            'master',
        ));
        $tokens = array(str_repeat('0', 64));
        $testMessage = 'test';

        $payload = $push->prepareIOS($tokens, $testMessage);

        $this->assertArrayHasKey('audience', $payload);
        $this->assertInternalType('array', $payload['audience']);
        $this->assertArrayHasKey('device_token', $payload['audience']);
        $this->assertInternalType('string', $payload['audience']['device_token']);

        $this->assertArrayHasKey('notification', $payload);
        $this->assertInternalType('array', $payload['notification']);
        $this->assertArrayHasKey('alert', $payload['notification']);
        $this->assertInternalType('string', $payload['notification']['alert']);

        $this->assertArrayHasKey('ios', $payload['notification']);
        $this->assertInternalType('array', $payload['notification']['ios']);
        $this->assertArrayHasKey('badge', $payload['notification']['ios']);
        $this->assertInternalType('string', $payload['notification']['ios']['badge']);
        $this->assertArrayHasKey('sound', $payload['notification']['ios']);
        $this->assertInternalType('string', $payload['notification']['ios']['sound']);

        $this->assertArrayHasKey('device_types', $payload);
        $this->assertInternalType('array', $payload['device_types']);
        $this->assertCount(1, $payload['device_types']);
        $this->assertEquals('ios', $payload['device_types'][0]);
    }

    // public function testWinPhonePayloadIsCorrectlyGenerated()
    // {
    //     $conf = Config::get('push');
    //     $push = Push::make('UrbanAirship', array(
    //         $conf['baseUrl'],
    //         $conf['key'],
    //         $conf['master'],
    //     ));
    //     $tokens = array(str_repeat('0', 36));

    //     $payload = $push->prepareWP($tokens, 'test', null, true);

    //     $this->assertTrue($push->validate($payload), $push->lastApiBody());
    //     $this->assertEquals(200, $push->lastApiCode());
    //     $this->assertJsonStringEqualsJsonString('{"ok":true}', $push->lastApiBody());
    // }

    // public function testWin8PayloadIsCorrectlyGenerated()
    // {
    //     $conf = Config::get('push');
    //     $push = Push::make('UrbanAirship', array(
    //         $conf['baseUrl'],
    //         $conf['key'],
    //         $conf['master'],
    //     ));
    //     $tokens = array(str_repeat('0', 36));

    //     $payload = $push->prepareWin8($tokens, 'test', null, true);

    //     $this->assertTrue($push->validate($payload), $push->lastApiBody());
    //     $this->assertEquals(200, $push->lastApiCode());
    //     $this->assertJsonStringEqualsJsonString('{"ok":true}', $push->lastApiBody());
    // }

    // public function testAllPlatformAreCorrectlyGenerated()
    // {

    // }

    // public function testSendPayloadToMax13()
    // {
    //     $conf = Config::get('push');
    //     $push = Push::make('UrbanAirship', array(
    //         $conf['baseUrl'],
    //         $conf['key'],
    //         $conf['master'],
    //     ));
    //     $tokens = array('616f98ba81fa554807f8fa7821d82d33981214000c545bbc346a43bd83e65a19');

    //     $payload = $push->prepareIos($tokens, 'test', null, true);

    //     $this->assertTrue($push->send($payload));
    // }
}
