<?php namespace AmanySaad\GithubSearchApi\Tests;

use GuzzleHttp\Client;
abstract class AbstractTestCase extends \PHPUnit\Framework\TestCase
{
    protected function loadJsonFixture($name)
    {
        $class = new \ReflectionClass(get_class($this));
        $path = dirname($class->getFileName()) . '/' . $name;

        return json_decode(file_get_contents($path), true);
    }

    protected function createHttpClientMock()
    {
        return $this->getMockBuilder('GuzzleHttp\ClientInterface')->getMock();
    }

    protected function createRequestMockBuilder()
    {
        return $this->getMockBuilder('Psr\Http\Message\RequestInterface');
    }

    protected function createResponseMockBuilder()
    {
        return $this->getMockBuilder('GuzzleHttp\Psr7\Response');
    }

    protected function mockSimpleRequest($httpClientMock, $method, $responseBody, $url = null)
    {
        $response = $this->createResponseMockBuilder()->disableOriginalConstructor()->getMock();
        $request = $this->createRequestMockBuilder()->getMock();
        $request->expects($this->any())->will($this->returnValue(new Client()));
        $request->expects($this->once())->will($this->returnValue($response));
        $response->expects($this->any())->will($this->returnValue($responseBody));

        if ($url === null) {
            $httpClientMock->expects($this->once())->will($this->returnValue($request));
        } else {
            $httpClientMock->expects($this->once())->with($url)->will($this->returnValue($request));
        }
    }
    /**
     * Return a HttpMethods client mock.
     *
     * @param array $methods
     *
     * @return \Http\Client\Common\HttpMethodsClientInterface
     */
    protected function getHttpMethodsMock(array $methods = [])
    {
        $mock = $this->createMock(HttpMethodsClientInterface::class);

        $mock
            ->expects($this->any())
            ->method('sendRequest');

        return $mock;
    }
}
