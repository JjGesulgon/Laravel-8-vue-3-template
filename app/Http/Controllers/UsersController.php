<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * User repository.
     *
     * @var App\Repositories\UserRepository
     */
    protected $userRepository;

    /**
     * Create new instance of user controller.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = new UserCollection(
            $this->userRepository->paginateWithFilters(
                request(),
                request()->per_page,
                request()->order_by
            )
        );

        if (! $users) {
            return response()->json([
                'message' => 'Failed to retrieve users'
            ], 400);
        }

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        if (! $this->userRepository->store($request)) {
            return response()->json([
                'message' => 'Failed to save user'
            ], 500);
        }

        return response()->json([
            'message' => 'User successfully saved'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! $user = $this->userRepository->findOrFail($id)) {
            return response()->json([
                'message' => 'User does not exist'
            ], 400);
        }

        return response()->json([
            'message' => 'User successfully retrieve',
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        if (! $this->userRepository->update($request, $id)) {
            return response()->json([
                'message' => 'Failed to update user'
            ], 500);
        }

        return response()->json([
            'message' => 'User successfully updated'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->userRepository->findOrFail($id)->delete()) {
            return response()->json([
                'message' => 'Failed to delete user'
            ], 400);
        }

        return response()->json([
            'message' => 'User successfully deleted'
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! $this->userRepository->restore($id)) {
            return response()->json([
                'message' => 'Failed to restore user'
            ], 400);
        }

        return response()->json([
            'message' => 'User successfully restored'
        ], 200);
    }

    /**
     * Forcefully remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($id)
    {
        if (! $this->userRepository->forceDestroy($id)) {
            return response()->json([
                'message' => 'Failed to permanently delete user'
            ], 400);
        }

        return response()->json([
            'message' => 'User successfully deleted permanently'
        ], 200);
    }

    /**
     * Search the specified data from the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $users = $this->userRepository->search($request);

        if (! $users) {
            return response()->json([
                'message' => 'Failed to retrieve users'
            ], 400);
        }

        return $users;
    }
}
