<?php
declare(strict_types=1);


namespace App\Reports;

use App\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

abstract class AbstractReport implements Responsable
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Collection
     */
    protected $payments;

    /**
     * @var Collection
     */
    protected $total;

    public function __construct(User $user, Collection $payments, Collection $total)
    {
        $this->user = $user;
        $this->payments = $payments;
        $this->total = $total;
    }
}
