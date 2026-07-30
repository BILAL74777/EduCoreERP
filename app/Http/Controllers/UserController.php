<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * User Page
     */
    public function index()
    { 
        return Inertia::render('Master/Users/Index');
    }

    /**
     * Get All Users
     */
    public function getAll(Request $request)
    {
        $users = $this->userService->getAll(
            $request->search,
            $request->status
        );

        return response()->json([
            'users' => $users,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Store User
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    /**
     * Update User
     */
    public function update(UserRequest $request, User $user)
    {
        $user = $this->userService->update(
            $user,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user,
        ]);
    }

    /**
     * Delete User
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}