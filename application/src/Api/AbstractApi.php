<?php

namespace AmanySaad\GithubSearchApi\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;
use AmanySaad\GithubSearchApi\Exception\GithubException;
use AmanySaad\GithubSearchApi\ResponseDecoder;

class AbstractApi
{
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client;
    }

    protected function get($url, $query = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'please provide your client password %s?',
                get_class($this)
            ));
        }

        $request = $this->client->get($url);
		var_dump($request);die;
        $request->getQuery()->merge($query);

        $response = $this->sendRequest($request);
        return ResponseDecoder::decode($response);
    }

    protected function post($url, $payload = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'please provide your client password %s?',
                get_class($this)
            ));
        }

        $request = $this->client->post($url, null, json_encode($payload));
        $response = $this->sendRequest($request);

        return ResponseDecoder::decode($response);
    }

    protected function delete($url)
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'please provide your client password %s?',
                get_class($this)
            ));
        }

        $request = $this->client->delete($url);
        $this->sendRequest($request);
    }

    private function sendRequest(RequestInterface $request)
    {
        try {
            return $request->send();
        } catch (\Exception $e) {
            throw new GithubException('Unexpected response.', 0, $e);
        }
    }

    protected function createPaginationIterator($url, $class, $query = [])
    {
        $request = $this->client->get($url);
        foreach ($query as $key => $value) {
            $request->getQuery()->add($key, $value);
        }
        $request->getBody()->getContents();
        $response = json_decode($request, true);
        $models = [];
        foreach ($response as $data) {
            $model = new $class($this->client);
            $model->populate($data);
            $models[] = $model;
        }

        return $models;
        
    }
    
}