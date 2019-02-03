<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Services\Geo;


class Country implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $iso;

    /**
     * @var string
     */
    protected $name;


    /**
     * Country constructor.
     *
     * @param $id
     * @param $iso
     * @param $name
     */
    public function __construct($id, $iso, $name)
    {
        $this->id = $id;
        $this->iso = $iso;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIso(): string
    {
        return $this->iso;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'   => $this->id,
            'iso'  => $this->iso,
            'name' => $this->name
        ];
    }
}
