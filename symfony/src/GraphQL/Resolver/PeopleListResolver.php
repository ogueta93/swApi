<?php
//src/GraphQL/Resolver/CharacterResolver.php
namespace App\GraphQL\Resolver;

use App\Service\SwapiService;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class PeopleListResolver implements ResolverInterface, AliasedInterface
{
    protected $swApi = null;
    protected $mappingFields = [
        'birthYear' => 'birth_year',
        'hairColor' => 'hair_color',
        'skinColor' => 'skin_color'
    ];

    public function __construct(SwapiService $swapiService)
    {
        $this->swApi = $swapiService;
    }

    /**
     * Returs data for the resolver
     * 
     * @param Argument $args
     * @return array
     */
    public function resolve(Argument $args)
    {
        $page = $args['page'] ?? 1;
        $data = $this->transformData($this->swApi->searchPeople($page));

        return [
            'character' => $data['results'],
            'page' => $page,
            'totalCount' => $data['count']
        ];
    }

    public static function getAliases(): array
    {
        return [
            'resolve' => 'people_list'
        ];
    }

    /**
     * Transforms the data
     * 
     * @param array $data
     * @return array
     */
    protected function transformData(array $data): array
    {
        $results = $data['results'] ?? null;
        if($results === null) {
            return [];
        }

        foreach($results as &$element) {
            $element['id'] = (int) $this->extractIdFromUrl($element['url']);
            $element['homeworld'] = $this->swApi->getHomeWorldById($this->extractIdFromUrl($element['homeworld']));

            $species = \array_map([$this, 'extractIdFromUrl'], $element['species']);
            $element['species'] = $this->swApi->getSpeciesById($species);

            foreach ($this->mappingFields as $key => $value) {
                $element[$key] = $element[$value];
            }
        }

        $data['results'] = $results;

        return $data; 
    }

    /** 
     * Extracts the id from an url
     * 
     * @param string $url
     * @return $id
    */
    protected function extractIdFromUrl(string $url) 
    {
        $explodeData = \explode("/", $url);
        $positions = \count($explodeData);

        return $explodeData[$positions - 2] ?? null;
    }
}
