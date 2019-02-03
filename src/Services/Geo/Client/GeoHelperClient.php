<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Services\Geo\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use OrangeShadow\Payments\Services\Geo\Exceptions\BadResponseException;

class GeoHelperClient implements GeoHelperClientInterface
{
    const URI = 'http://geohelper.info';

    /**
     * @var Client $client
     */
    protected $client;

    /**
     * @var integer $paginationLimit
     */
    protected $paginationLimit = 20;

    /**
     * @var float
     */
    protected $timeout = 3.00;

    /**
     * GeoHelperClient constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * @param string $apiKey
     * @param string $filterName
     * @param string $langIso
     * @return mixed
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCountry(string $apiKey, string $filterName, string $langIso = 'en'): ?array
    {
        $query = http_build_query([
            'apiKey'     => $apiKey,
            'filter'     => ['name' => $filterName],
            'locale'     => ['lang' => $langIso],
            'pagination' => ['limit' => $this->paginationLimit]
        ]);

        $request = new Request(
            'GET',
            self::URI . '/api/v1/countries?' . $query,
            ['connect_timeout' => $this->timeout]
        );

        return $this->send($request);
    }

    /**
     * @param string $apiKey
     * @param string $countryIso
     * @param string $filterName
     * @param string $langIso
     *
     * @return array
     * @throws BadResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCity(string $apiKey, string $filterName, string $countryIso = '', string $langIso = 'en'): ?array
    {
        $params = [
            'apiKey'     => $apiKey,
            'filter'     => ['name' => $filterName],
            'locale'     => ['lang' => $langIso],
            'pagination' => ['limit' => $this->paginationLimit]
        ];

        if (!empty($countryIso)) {
            $params['filter']['countryIso'] = $countryIso;
        }

        $query = http_build_query($params);

        $request = new Request(
            'GET',
            self::URI . '/api/v1/cities?' . $query,
            ['connect_timeout' => $this->timeout]
        );

        return $this->send($request);
    }

    /**
     * @param Request $request
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws BadResponseException
     */
    private function send(Request $request): array
    {
        $response = $this->client->send($request);
        $content = (string)$response->getBody();
        $data = \json_decode($content, true);

        if (\json_last_error()) {
            throw new BadResponseException(\json_last_error_msg());
        }

        if (!isset($data['result'])) {
            throw new BadResponseException('Field result not Found!');
        }

        return $data['result'];
    }
}
