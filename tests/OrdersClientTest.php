<?php

class OrdersClientTest extends \PHPUnit\Framework\TestCase {
    
    protected static $testOrder = [
        'attributes' => [
            'numShares' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
            'type' => 'sell',
        ],
        'asset' => 
            [
                'id' => 'FR008',
                'issuer' => 'TEMP',
                'name' => '141 South Meridian Street',
                'statusCode' => '1',
                'statusText' => 'open',
                'description' => 'Test desc',
            ],
        'user' => 
            [
                'email' => 'q@q.com',
                'phoneNumber' => '999',
                'displayName' => 'Qusai',
                'timezone' => 'UM12',
                'language' => 'English',
            ],
    ];

    public function testOrderIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com/brokerage', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode([self::$testOrder]))
        ));
        $orders = $cfx->orders->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(self::$testOrder))
        ));
        $orders = $cfx->orders->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders/FR008', $r->getUrl());
    }
}
