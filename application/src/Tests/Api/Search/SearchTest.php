<?php
namespace AmanySaad\GithubSearchApi\Tests\Api\Search;

use AmanySaad\GithubSearchApi\Api\Repository;
use AmanySaad\GithubSearchApi\SearchGithub;
use AmanySaad\GithubSearchApi\Tests\AbstractTestCase;

class SearchTest extends AbstractTestCase
{
    public function testFindRepositories()
    {
        
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_repositories.json')));
        $result=SearchGithub::create()->getSearch()->findRepositories('laravel',1);

        $this->assertCount(1, $result);

        foreach ($result as $repo) {
            $this->assertInstanceOf(Repository::CLASS_NAME, $repo);
        }

    }
}
