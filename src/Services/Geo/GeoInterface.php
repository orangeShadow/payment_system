<?php
declare(strict_types=1);


namespace OrangeShadow\Payments\Services\Geo;


interface GeoInterface
{
    public function getCountries(string $name): array;
    public function getCities(string $name, string $countryIso = ''): array;
}
