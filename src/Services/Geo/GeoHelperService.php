<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Services\Geo;

use OrangeShadow\Payments\Services\Geo\Client\GeoHelperClientInterface;

class GeoHelperService implements GeoInterface
{
    /**
     * @var GeoHelperClientInterface $client
     */
    protected $client;

    /**
     * @var string $langIso ISO length 2 chars
     */
    protected $langIso;

    /**
     * @var string $apiToken
     */
    protected $apiKey;

    /**
     * GeoHelperService constructor.
     * @param GeoHelperClientInterface $client
     * @param string $apiKey
     * @param string $langIso
     */
    public function __construct(GeoHelperClientInterface $client, string $apiKey, $langIso = "en")
    {
        $this->client = $client;
        $this->langIso = $langIso;
        $this->apiKey = $apiKey;

    }

    /**
     * @param string $name
     * @return array
     */
    public function getCountries(string $name): array
    {
        $result = $this->client->getCountry($this->apiKey, $name, $this->langIso);

        $newResult = [];

        foreach ($result as $item) {
            $newResult[] = new Country($item['id'], $item['iso'], $item["name"]);
        }

        return $newResult;
    }

    /**
     * @param string $name
     * @param string $countryIso
     * @return array
     */
    public function getCities(string $name, string $countryIso = ''): array
    {
        $result = $this->client->getCity($this->apiKey, $name, $countryIso, $this->langIso);

        $newResult = [];

        foreach ($result as $item) {
            $newResult[] = new City($item['id'], $item["name"], $item['area'] ?? '');
        }

        return $newResult;
    }
}
