<?php
namespace AmanySaad\GithubSearchApi;

use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client;
use AmanySaad\GithubSearchApi\Api\Repository;
use AmanySaad\GithubSearchApi\Api\Search;
use AmanySaad\GithubSearchApi\Exception\GithubException;

class SearchGithub
{
    private $client;
    private $search;

    /**
     * @see SearchGithub::create()
     *
     * @param Client $client The http client
     */
    public function __construct(Client $client)
    {
		
        $this->client = $client;
    }

    /**
     *
     * @return SearchGithub
     */
    public static function create()
    {
        $client = new Client(['base_uri' => 'https://api.github.com/']);
        return new SearchGithub($client);
    }

    /**
     * @return Search
     */
    public function getSearch()
    {
        if ($this->search === null) {
            $this->search = new Search($this->client);
        }

        return $this->search;
    }

 

    /**
     * @param $owner string The login name of the repository owner, e.g. "laravel"
     * @param $name string The repository name, e.g. "Hello-World"
     * @throws Exception\GithubException In case the repository was not found
     * @return Repository
     */
    public function getRepository($owner, $name)
    {
        $repository = new Repository($this->client);
        $repository->populate(['owner' => ['login' => $owner], 'name' => $name]);

        try {
            $repository->getId();
        } catch (GithubException $e) {
            throw new GithubException(sprintf('Repository %s was not found.', $name), 0, $e);
        }

        return $repository;
    }
}