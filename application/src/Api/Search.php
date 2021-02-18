<?php
namespace AmanySaad\GithubSearchApi\Api;

use AmanySaad\GithubSearchApi\Api\AbstractApi;
use AmanySaad\GithubSearchApi\Api\Paginator;
use AmanySaad\GithubSearchApi\Api\Repository;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class Search extends AbstractApi
{
    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';
    const SORT_BY_STARS = 'stars';
    const SORT_BY_FORKS = 'forks';
    const SORT_BY_LAST_UPDATED = 'updated';
    const SORT_BY_BEST_MATCH = null;

    /**
     * @param $keywords
     * @param $per_page
     * @param null $sort
     * @param string $order
     * @param string $date
     * @throws \InvalidArgumentException
     * @return Repository[]
     */
    public function findRepositories($keywords, $per_page, $sort = self::SORT_BY_BEST_MATCH, $order = self::ORDER_DESC,$date=false)
    {
        $query = ['q' => $keywords];
        if ($sort !== self::SORT_BY_BEST_MATCH) {
            $allowed = [self::SORT_BY_STARS, self::SORT_BY_FORKS, self::SORT_BY_LAST_UPDATED];
            if (!in_array($sort, $allowed)) {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid sort argument %s. Should be one of %s',
                    $sort,
                    implode(', ', $allowed)
                ));
            }

            $query['sort'] = $sort;
        }
        if($date){
            $query['created'] = $date;
        }

        if ($order !== self::ORDER_DESC) {
            $query['order'] = $order;
        }
	$query['per_page'] = $per_page;	
       
        $request = $this->client->request('GET','/search/repositories',[
			'query' => $query
		])->getBody()->getContents();
        $response = json_decode($request, true);
        
        $repositories = [];
        foreach ($response['items'] as $data) {
            $repository = new Repository($this->client);
            $repository->populate($data);
            $repositories[] = $repository;
        }

        return $repositories;

}
}