<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AddBalanceRequest;
use App\Http\Requests\AddCurrencyRateRequest;
use App\Http\Requests\RemittanceRequest;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Repositories\BankRepository;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OrangeShadow\Payments\Exceptions\PaymentsException;
use App\Http\Resources\UserResource;


class BankController extends Controller
{
    /**
     * @var BankRepository
     */
    protected $bankRepository;

    public function __construct(BankRepository $currencyRepository)
    {
        $this->bankRepository = $currencyRepository;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getCurrencies()
    {
        return response(['data' => $this->bankRepository->getCurrencies()]);
    }

    /**
     * @param AddCurrencyRateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addRate(AddCurrencyRateRequest $request)
    {
        try {
            return response($this->bankRepository->createRate($request));
        } catch (PaymentsException $exception) {
            return response([
                'errors'  => [
                    "currency" => [$exception->getMessage()]
                ],
                'message' => 'The given data was invalid.'
            ], 422);
        } catch (\Throwable $exception) {
            \Log::error('Add currency rate', ['exception' => $exception]);

            return response(['message' => 'Sorry, We have an error!'], 500);
        }
    }

    /**
     * @param AddBalanceRequest $request
     * @return UserResource|ResponseFactory|Response
     */
    public function addBalance(AddBalanceRequest $request)
    {
        try {
            $user = $this->bankRepository->addBalance($request);

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return response(['message' => 'Sorry user with this name is not found!'], 404);
        } catch (\Throwable $exception) {
            \Log::error('Add balance error', ['exception' => $exception]);

            return response(['message' => 'Sorry, We have an error!'], 500);
        }
    }

    /**
     * @param RemittanceRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function remittance(RemittanceRequest $request)
    {
        try {
            return response(new PaymentResource($this->bankRepository->remittance($request)));
        } catch (PaymentsException $exception) {
            return response([
                'errors'  => [
                    "user_from" => [$exception->getMessage()]
                ],
                'message' => 'The given data was invalid.'
            ], 422);
        } catch (ModelNotFoundException $exception) {
            if (strpos($exception->getMessage(), 'App\Rate') > 0) {
                return response(['errors'  => ['currency' => 'Sorry rate for this currency on current date not found!'],
                                 'message' => 'The given data was invalid.'], 422);
            }

            return response(['message' => 'Sorry user with this name is not found!'], 404);
        } catch (\Throwable $exception) {
            \Log::error('Remittance', ['exception' => $exception]);

            return response(['message' => 'Sorry, We have an error!'], 500);
        }
    }


    /**
     * @param PaymentRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function paymentsReport(PaymentRequest $request)
    {
        try {
            return $this->bankRepository->report($request);
        } catch (ModelNotFoundException $exception) {
            return response(['message' => 'Sorry user with this name is not found!'], 404);
        } catch (\Throwable $exception) {
            \Log::error('Payment report', ['exception' => $exception]);

            return response(['message' => 'Sorry, We have an error!'], 500);
        }
    }
}
