<?php
//src/Service/SwapiService.php
namespace App\Service;

use App\Base\AbstractService;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class SwapiService extends AbstractService
{
    const DEFAULT_PAGE_NUMBER = 1;
    const PEOPLE_ITEMS_PER_PAGE = 10;

    protected $client;
    protected $cache;
    protected $page;

    /**
     * Search Star Wars people by a page number
     *
     * @param $pageNumber
     * @return array
     */
    public function searchPeople($pageNumber): array
    {
        $content = [];
        $this->page = \is_numeric($pageNumber) ? $pageNumber : self::DEFAULT_PAGE_NUMBER;

        $cacheItem = $this->getCacheItem();
        if ($cacheItem->isHit()) {
            $content = $cacheItem->get();
        } else {
            $content = $this->getAllPeopleData();
        }

        $cacheItem->set($content);
        $cacheItem->expiresAfter($this->config['cacheTime']);
        $this->cache->save($cacheItem);

        return $this->filterResults($content);
    }

    /**
     * Gets the homeworld by id
     * 
     * @param int $id
     * @return array
     */
    public function getHomeWorldById(int $id): array
    {
        $cacheItem = $this->cache->getItem(\sprintf('planet-%s', $id));

        if ($cacheItem->isHit()) {
            $content = $cacheItem->get();
        } else {
            try {
                $response = $this->client->request('GET', \sprintf('%s/%s/', $this->config['planetService'], $id));
                $content = \json_decode($response->getBody()->getContents(), true);
            } catch (\Throwable $th) {
                return[];
            }
        }

        $cacheItem->set($content);
        $this->cache->save($cacheItem);

        return $content;
    }

    /**
     * Gets the species by id
     * 
     * @param array $ids
     * @return array
     */
    public function getSpeciesById(array $ids): array
    {
        $result = [];

        foreach ($ids as $id) {
            $cacheItem = $this->cache->getItem(\sprintf('species-%s', $id));

            if ($cacheItem->isHit()) {
                $content = $cacheItem->get();
            } else {
                try {
                    $response = $this->client->request('GET', \sprintf('%s/%s/', $this->config['speciesService'], $id));
                    $content = \json_decode($response->getBody()->getContents(), true);
                } catch (\Throwable $th) {
                    $content = [];
                }
            }
    
            $cacheItem->set($content);
            $this->cache->save($cacheItem);

            $result[] = $content;
        }
        
        return $result;
    }

    /**
     * Gets the cacheItem
     *
     * @return Symfony\Component\Cache\CacheItem
     */
    protected function getCacheItem()
    {
        return $this->cache->getItem($this->configName);
    }

    /**
     * Filters the results
     *
     * @param array $content
     * @return array
     */
    protected function filterResults(array $content): array
    {
        $data = $content['results'] ?? null;
        if ($data === null) {
            return [];
        }

        $start = ($this->page * self::PEOPLE_ITEMS_PER_PAGE) - self::PEOPLE_ITEMS_PER_PAGE;

        $content['results'] = \array_splice($data, $start, self::PEOPLE_ITEMS_PER_PAGE);
        return $content;
    }

    /**
     * Gets all people data
     *
     * @return array
     */
    protected function getAllPeopleData(): array
    {
        $count = null;
        $pages = null;
        $result = [];

        $peopleService = $this->config['peopleService'];

        try {
            $response = $this->client->request('GET', $peopleService);
            $data = \json_decode($response->getBody()->getContents(), true);

            if (isset($data['results'])) {
                $result = \array_map(function ($element) {
                    return $element;
                }, $data['results']);

                $count = (int) $data['count'];
                $pages = $count / self::PEOPLE_ITEMS_PER_PAGE;
            }
        } catch (\Throwable $th) {
            return [];
        }

        if ($pages > 1) {
            $promises = [];
            for ($i = 2; $i <= $pages + 1; $i++) {
                $promises[] = $this->client->getAsync($peopleService, ['query' => \sprintf('page=%s', $i)]);
            }

            try {
                $promisesResult = Promise\unwrap($promises);

                \array_walk($promisesResult, function ($response) use (&$result) {
                    $result = \array_merge($result, \json_decode($response->getBody(), true)['results']);
                });
            } catch (\Throwable $th) {
                return [];
            }
        }

        \usort($result, function ($a, $b) {
            return $a['name'] > $b['name'];
        });

        return \array_merge(
            [
                'count' => $count,
                'results' => $result
            ]);
    }

    /**
     * Sets config name
     *
     * @return void
     */
    protected function setConfigName()
    {
        $this->configName = 'swapi';
    }

    /**
     * Sets custom params
     *
     * @return void
     */
    protected function setCustomParams()
    {
        $this->client = new Client(['base_uri' => $this->config['serviceUrl']]);
        $this->cache = new FilesystemAdapter();
    }
}
