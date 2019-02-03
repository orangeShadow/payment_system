<?php
declare(strict_types=1);


namespace App\Repositories;


use App\Http\Requests\UserCreateRequest;
use App\Purse;
use App\User;

use Illuminate\Http\Request;

class UserRepository
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return User::search($request)->with('purse');
    }

    /**
     * @param UserCreateRequest $request
     *
     * @return User
     * @throws \Throwable
     */
    public function create(UserCreateRequest $request): User
    {
        \DB::beginTransaction();
        try {
            $user = User::create($request->only('name', 'country', 'city'));
            $user->purse()->save(new Purse(['currency' => $request->get('currency'), 'amount' => 0]));
            \DB::commit();

            return $user;
        } catch (\Throwable $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }
}
