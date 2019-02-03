<?php
declare(strict_types=1);


namespace App\Repositories;

use App\Http\Requests\AddBalanceRequest;
use App\Http\Requests\ExchangeRequest;
use App\Http\Requests\PaymentRequest;
use App\Rate;
use App\Payment;
use App\Currency;
use App\Http\Requests\AddCurrencyRateRequest;
use App\User;
use OrangeShadow\Payments\Exceptions\CurrencyRateExistException;
use OrangeShadow\Payments\Contracts\BankInterface;
use OrangeShadow\Payments\Core\Money;
use OrangeShadow\Payments\Exceptions\InvalidCurrencyException;
use OrangeShadow\Payments\Exceptions\NotEnoughMoneyException;

class BankRepository
{
    protected $bank;

    /**
     * BankRepository constructor.
     * @param BankInterface $bank
     */
    public function __construct(BankInterface $bank)
    {
        $this->bank = $bank;
    }

    public function getCurrencies()
    {
        return Currency::all()->pluck('code');
    }

    /**
     * @param AddCurrencyRateRequest $request
     * @return Rate
     * @throws CurrencyRateExistException
     */
    public function createRate(AddCurrencyRateRequest $request): Rate
    {
        if ($request->get('date')) {
            $date = $request->get('date');
        } else {
            $date = (new \DateTime())->format('Y-m-d');
        }

        $rate = Rate::where('currency', $request->get('currency'))
            ->where('created_at', $date)->first();

        if (!empty($rate)) {
            throw new CurrencyRateExistException($date);
        }

        return Rate::create([
            'currency'   => $request->get('currency'),
            'rate'       => $request->get('rate'),
            'created_at' => $date
        ]);
    }

    /**
     * @param AddBalanceRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function addBalance(AddBalanceRequest $request)
    {
        \DB::beginTransaction();
        try {
            $user = User::findOrFail($request->get('user_id'));

            $money = new Money($request->get('amount'), $user->purse->getCurrency());
            $user->purse->addBalance($money);
            $user->purse->save();
            Payment::create([
                'purse_from'  => null,
                'purse_to'    => $user->purse->id,
                'amount_from' => null,
                'amount_to'   => $money->getAmount(),
                'amount_usd'  => $this->bank->exchange($money, 'USD')->getAmount()
            ]);
            \DB::commit();

            return $user;
        } catch (\Throwable $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param ExchangeRequest $request
     * @return Payment
     * @throws NotEnoughMoneyException
     * @throws \Throwable
     */
    public function remittance(ExchangeRequest $request): Payment
    {
        $userFrom = User::findOrFail($request->get('user_from'));
        $userTo = User::findOrFail($request->get('user_to'));

        $moneyUsed = new Money($request->get('amount'), $request->get('currency'));

        if ($moneyUsed->getCurrency() !== $userFrom->purse->getCurrency() ||
            $moneyUsed->getCurrency() !== $userTo->purse->getCurrency()
        ) {
            throw new InvalidCurrencyException("You can`t use {$moneyUsed->getCurrency()} currency!");
        }

        $moneyFrom = $this->bank->exchange($moneyUsed, $userFrom->purse->getCurrency());

        if ($this->bank->compare($moneyFrom, $userFrom->purse) === 1) {
            throw new NotEnoughMoneyException();
        }

        $moneyUsd = $this->bank->exchange($moneyUsed, 'USD');

        $moneyTo = $this->bank->exchange($moneyUsed, $userTo->purse->getCurrency());

        \DB::beginTransaction();

        try {
            \DB::commit();

            $userFrom->purse->subBalance($moneyFrom);
            $userFrom->purse->save();
            $userTo->purse->addBalance($moneyTo);
            $userTo->purse->save();

            return Payment::create([
                'purse_from'  => $userFrom->purse->id,
                'purse_to'    => $userTo->purse->id,
                'amount_from' => $moneyFrom->getAmount(),
                'amount_to'   => $moneyTo->getAmount(),
                'amount_usd'  => $moneyUsd->getAmount()
            ]);
        } catch (\Throwable $exception) {
            \DB::rollBack();

            throw $exception;
        }
    }

    /**
     *
     */
    public function report(PaymentRequest $request)
    {
        $user = User::where('name', $request->get('name'))->firstOrFail();
        $purse_id = $user->purse->id;

        $format = $request->get('format');

        $class = "App\\Reports\\" . ucfirst($format) . "Report";

        return new $class($user, $this->searchPayments($request, $purse_id), $this->getTotalPayment($request));
    }

    /**
     * @param PaymentRequest $request
     * @param int $purse_id
     * @return
     */
    protected function searchPayments(PaymentRequest $request, int $purse_id)
    {
        $startDt = $request->get('start_dt');
        $endDt = $request->get('end_dt');

        return Payment::where(function ($query) use ($purse_id) {
            return $query->where('purse_from', $purse_id)->orWhere('purse_to', $purse_id);
        })->when($startDt, function ($query, $startDt) {
            return $query->where('payments.created_at', '>=', $startDt);
        })->when($endDt, function ($query, $endDt) {
            return $query->where('payments.created_at', '<=', $endDt);
        })->with('purseFrom')->with('purseTo')->get();
    }

    /**
     * @param PaymentRequest $request
     * @return
     */
    protected function getTotalPayment(PaymentRequest $request)
    {

        $name = $request->get('name');
        $startDt = $request->get('start_dt');
        $endDt = $request->get('end_dt');

        $table1 = \DB::table('users as u')
            ->select(\DB::raw("u.name,
                   p.currency,
                   sum(pf.amount_from) as amountFrom,
                   sum(pf.amount_usd)  as amountUsdFrom,
                   0                   as amountTo,
                   0                   as amountUsdTo"))
            ->leftJoin('purses as p', 'u.id', '=', 'p.user_id')
            ->leftJoin('payments as pf', 'p.id', '=', 'pf.purse_from')
            ->when($name, function ($query, $name) {
                return $query->where('name', $name);
            })
            ->when($startDt, function ($query, $startDt) {
                return $query->where('pf.created_at', '>=', $startDt);
            })
            ->when($endDt, function ($query, $endDt) {
                return $query->where('pf.created_at', '<=', $endDt);
            })
            ->groupBy(['u.id', 'u.name', 'p.currency']);

        $table2 = \DB::table('users as u')
            ->select(\DB::raw("
                   u.name,
                   p.currency,
                   0                   as amountFrom,
                   0                   as amountUsdFrom,
                   sum(pt.amount_to)   as amountTo,
                   sum(pt.amount_usd)  as amountUsdTo"))
            ->leftJoin('purses as p', 'u.id', '=', 'p.user_id')
            ->leftJoin('payments as pt', 'p.id', '=', 'pt.purse_to')
            ->when($name, function ($query, $name) {
                return $query->where('name', $name);
            })
            ->when($startDt, function ($query, $startDt) {
                return $query->where('pt.created_at', '>=', $startDt);
            })
            ->when($endDt, function ($query, $endDt) {
                return $query->where('pt.created_at', '<=', $endDt);
            })
            ->groupBy(['u.id', 'u.name', 'p.currency']);

        $total = \DB::table(\DB::raw("({$table1->union($table2)->toSql()}) as t"))->select(
            \DB::raw('name, 
                currency, 
                sum(amountFrom) as totalFrom, 
                sum(amountUsdFrom) as totalFromUsd, 
                sum(amountTo) as totalTo,
                sum(amountUsdTo) as totalToUsd
            '))
            ->mergeBindings($table1->union($table2))
            ->groupBy(['t.name', 't.currency'])
            ->get();

        return $total;
    }
}
