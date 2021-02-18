<?php
namespace AmanySaad\GithubSearchApi\Tests\Api\Repository;

use AmanySaad\GithubSearchApi\Api\Repository;
use AmanySaad\GithubSearchApi\Tests\AbstractTestCase;

class RepositoryTest extends AbstractTestCase
{
    private $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->createHttpClientMock();
    }


    public function testPopulateWithExampleData()
    {
        $repository = new Repository();
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        $this->assertEquals(1296269, $repository->getId());
        $this->assertEquals('Hello-World', $repository->getName());
        $this->assertFalse($repository->isPrivate());
        $this->assertFalse($repository->isFork());
        $this->assertEquals('This your first repo!', $repository->getDescription());
        $this->assertEquals('git@github.com:octocat/Hello-World.git', $repository->getSshUrl());
        $this->assertEquals('octocat/Hello-World', $repository->getFullName());
        $this->assertEquals('master', $repository->getDefaultBranch());
    }

   
}
