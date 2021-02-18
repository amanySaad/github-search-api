<?php
namespace AmanySaad\GithubSearchApi\Api;

use AmanySaad\GithubSearchApi\Api\AbstractModelApi;
use AmanySaad\GithubSearchApi\Api\Repository;
use AmanySaad\GithubSearchApi\TemplateUrlGenerator;

class User extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    private $organizations;
    private $repositories;
    private $followers;

    protected function load()
    {
        if ($this->isAttributeLoaded('url')) {
            $url = $this->getAttribute('url');
        } elseif ($this->isAttributeLoaded('login')) {
            $url = TemplateUrlGenerator::generate('/users/{/user}', ['login' => $this->getLogin()]);
        } else {
            throw new \RuntimeException('Unable to guess user url, not enough informations given.');
        }

        $this->populate($this->get($url));
    }

    /**
     * @return string The login name of the github account, e.g. "octocat"
     */
    public function getLogin()
    {
        return $this->getAttribute('login');
    }

    /**
     * @return string The avatar url, e.g. "https://github.com/images/error/octocat_happy.gif"
     */
    public function getAvatarUrl()
    {
        return $this->getAttribute('avatar_url');
    }

    /**
     * @return string The gravatar id
     * @link https://gravatar.com/
     */
    public function getGravatarId()
    {
        return $this->getAttribute('gravatar_id');
    }

    /**
     * @return integer The user id, e.g. 42
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $type Can be 'html' or 'api'
     * @return string The url, e.g. https://github.com/octocat (if $type is html)
     * @throws \InvalidArgumentException
     */
    public function getUrl($type = 'html')
    {
        switch ($type) {
            case 'html':
                return $this->getAttribute('html_url');
            case 'api':
                return $this->getAttribute('url');
        }

        throw new \InvalidArgumentException(sprintf(
            'Invalid url type "%s", expected one of "%s"',
            $type,
            implode(', ', ['html', 'api'])
        ));
    }

    /**
     * @return string The real name of the user, e.g. "monalisa octocat"
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @link https://docs.github.com/en/rest/reference/repos#list-organization-repositories
     * List all public organizations for an unauthenticated user.
     * Lists private and public organizations for authenticated users.
     *
     * @return Organization[] The public organizations the user is member of
     */
    public function getOrganizations()
    {
        if ($this->organizations === null) {
            $this->organizations = $this->loadOrganizations();
        }

        return $this->organizations;
    }

    protected function loadOrganizations()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('organizations_url'), []);
        return $this->createPaginationIterator($url, Organization::CLASS_NAME);
    }

    /**
     * @link https://docs.github.com/en/rest/reference/users
     * "List public repositories for the specified user."
     *
     * @param string $type Can be one of all, owner, member. Default: owner
     * @throws \InvalidArgumentException In case the $type is not valid
     * @return Repository[]
     */
    public function getRepositories($type = 'owner')
    {
        $validTypes = ['all', 'owner', 'member'];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid type, expected one of "%s"',
                implode(', ', $validTypes)
            ));
        }

        if (!isset($this->repositories[$type])) {
            $this->repositories[$type] = $this->loadRepositories($type);
        }

        return $this->repositories[$type];
    }

    protected function loadRepositories($type)
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('repos_url'), []);
        return $this->createPaginationIterator($url, Repository::CLASS_NAME, ['type' => $type]);
    }

    /**
     * The returned email is the user’s publicly visible email address (or null if the user has not specified a public
     * email address in their profile).
     *
     * @return string|null The users publicly visible email address or null if not specified by the user
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    protected function loadFollowers()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('followers_url'), []);
        return $this->createPaginationIterator($url, User::CLASS_NAME);
    }

    /**
     * @link https://docs.github.com/en/rest/reference/users#list-followers-of-a-user
     * List the user's followers
     *
     * @return User[]
     */
    public function getFollowers()
    {
        if ($this->followers === null) {
            $this->followers = $this->loadFollowers();
        }

        return $this->followers;
    }
}
