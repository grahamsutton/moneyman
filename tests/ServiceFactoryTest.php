<?php

namespace MoneyManTests;

use MoneyMan\ServiceFactory;
use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase
{
    public function getServiceReturnsSwapObjectDataProvider()
    {
        return [
            [ServiceFactory::FIXER],
            [ServiceFactory::GOOGLE],
            [ServiceFactory::YAHOO]
        ];
    }

    /**
     * @dataProvider getServiceReturnsSwapObjectDataProvider
     * @param $service_name
     */
    public function testGetServiceReturnsSwapObject($service_name)
    {
        $service = ServiceFactory::getService($service_name);
        $this->assertInstanceOf(\Swap\Swap::class, $service);
    }

    /**
     * @expectedException \MoneyMan\Exception\UnsupportedExchangeServiceProvidedException
     */
    public function testGetServiceThrowsExceptionWhenInvalidServiceNameIsProvided()
    {
        $service = ServiceFactory::getService('thisisnotright');
    }
}
