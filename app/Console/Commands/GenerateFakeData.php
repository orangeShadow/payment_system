<?php

namespace App\Console\Commands;

use App\Http\Requests\AddBalanceRequest;
use App\Http\Requests\AddCurrencyRateRequest;
use App\Http\Requests\ExchangeRequest;
use App\Http\Requests\UserCreateRequest;
use App\Payment;
use App\Purse;
use App\Repositories\BankRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Console\Command;

class GenerateFakeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:fake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fake data';

    /**
     * @var BankRepository
     */
    protected $bankRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BankRepository $bankRepository, UserRepository $userRepository)
    {
        $this->bankRepository = $bankRepository;
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    protected $rates = [
        ['currency' => 'EUR', 'rate' => '0.87'],
        ['currency' => 'RUB', 'rate' => '65.45'],
        ['currency' => 'CHF', 'rate' => '0.99']
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->rates as $rate) {
            try {
                $this->bankRepository->createRate(new AddCurrencyRateRequest($rate));
            } catch (\Throwable $e) {

            }
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Payment::truncate();
        Purse::truncate();
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(User::class, 1000)->make()->each(function ($user) {
            $userArray = $user->toArray();
            $userArray['currency'] = (array_random($this->rates, 1)[0])['currency'];
            $user = $this->userRepository->create(new UserCreateRequest($userArray));
            $this->bankRepository->addBalance(new AddBalanceRequest([
                'user_id' => $user->id,
                'amount'  => '15000000'
            ]));
        });

        factory(Payment::class, 100000)->make()->each(function ($payment) {
            try {
                $purseFrom = Purse::find($payment->purse_from);
                $purseTo = Purse::find($payment->purse_to);

                $this->bankRepository->remittance(new ExchangeRequest([
                    'user_from' => $purseFrom->user->id,
                    'user_to'   => $purseTo->user->id,
                    'amount'    => (string)$payment->amount_to,
                    'currency'  => $purseTo->getCurrency()
                ]));
            } catch (\Throwable $exception) {

            }
        });

    }
}
