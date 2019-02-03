<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->get('all')) {
            $users = $this->userRepository->search($request)->get();
        } else {
            $users = $this->userRepository->search($request)->paginate(10);
        }

        return UserResource::collection($users);
    }

    /**
     * @param UserCreateRequest $request
     * @return ResponseFactory|Response
     */
    public function store(UserCreateRequest $request)
    {
        try {
            $user = $this->userRepository->create($request);

            return new UserResource($user);
        } catch (\Throwable $exception) {
            \Log::error('User create error', ['exception' => $exception]);

            return response(['message' => 'Sorry, We have an error!'], 500);
        }
    }
}
