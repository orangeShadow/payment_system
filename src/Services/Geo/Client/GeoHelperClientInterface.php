<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Services\Geo\Client;

/**
 * Interface GeoHelperInterface
 * @package OrangeShadow\Payments\Services\GeoHelper
 */
interface GeoHelperClientInterface
{
    /**
     * @param string $apiKey
     * @param string $filterName
     * @param string $langIso
     * @return mixed
     */
    public function getCountry(string $apiKey, string $filterName, string $langIso = 'en'): ?array;

    /**
     * @param string $apiKey
     * @param string $countryIso
     * @param string $filterName
     * @param string $langIso
     *
     * @return mixed
     */
    public function getCity(string $apiKey, string $filterName, string $countryIso='', string $langIso = 'en'): ?array;
}
