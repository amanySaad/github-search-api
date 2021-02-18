<?php
namespace AmanySaad\GithubSearchApi\Tests;

use AmanySaad\GithubSearchApi\TemplateUrlGenerator;

class TemplateUrlGeneratorTest extends \PHPUnit\Framework\TestCase
{
    public function urlDataProvider()
    {
        return [
            [
                'https://api.github.com/repos/laravel/laravel/collaborators',
                'https://api.github.com/repos/laravel/laravel/collaborators{/collaborator}',
                ['collaborator' => null]
            ],
        ];
    }

    /**
     * @dataProvider urlDataProvider
     */
    public function testUrl($expected, $url, $data)
    {
        $this->assertEquals($expected, TemplateUrlGenerator::generate($url, $data));
    }
}
