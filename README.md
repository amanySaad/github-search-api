# github-search-api
Asimple PHP script to use github api client

### Requirements ###
You need php 7.2 or higher .

### Features ###
* very easy to use 
* pure object oriented interface
* Extensively tested and documente

 
----

## Get started ##
Install via composer: `composer require amanySaad/github-search-api`
```json
{
    "require": {
        "amanySaad/github-search-api": "v0.1"
    }
}
```
#### with No authentication ####
You can use the public api without any authentication you can do this by just calling `SearchGithub::create` without any arguments.
```php
<?php

use AmanySaad\GithubSearchApi\SearchGithub;

$SearchGithub = SearchGithub::create();
```

### Search API ###
You can use the search api by calling `$SearchGithub->getSearch()`
```php
// this is equals to https://api.github.com/search/repositories?q=language%3Aphp+&type=Repositories&ref=searchresults
foreach (SearchGithub::create()->getSearch()->findRepositories('language:php') as $repo) {
    $repo->getName();
    // ...
}
```

### Repository API ###
```php
$repository = SearchGithub::create()->getRepository('amanySaad', 'github-search-api');
$repository->getName();
$repository->getCommits();
$repository->getBranches();
$repository->getLanguage();
$repository->getOwner(); // returns a user object
$repository->getOwner()->getName(); // chaining to display owner name

// list the collaborators of the repo
foreach ($repository->getCollaborators() as $collaborators) {
    echo $collaborators->getName();
}
```
### Example ###

You will find a Gui example at src/example

----


### Testing ###
```bash
$ phpunit
```
