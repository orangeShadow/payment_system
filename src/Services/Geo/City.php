<?php
declare(strict_types=1);

namespace OrangeShadow\Payments\Services\Geo;


class City implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $area;

    /**
     * City constructor.
     * @param int $id
     * @param string $name
     * @param string $area
     */
    public function __construct(int $id, string $name, string $area)
    {
        $this->id = $id;
        $this->name = $name;
        $this->area = $area;
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'area' => $this->area
        ];
    }
}
