<?php

namespace App\Services;

use App\Models\User;
use App\Models\Upload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UploadService $uploadService
    ) {}

    public function getAll(?string $search = null, ?string $status = null)
    {
        return User::query()
            ->with('image')
            ->when(
                $search,
                fn($q) => $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
            )
            ->when(
                $status !== null,
                fn($q) => $q->where('status', $status)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            if (isset($data['image'])) {

                $upload = $this->uploadService->store(
                    $data['image'],
                    'users'
                );

                $data['upload_id'] = $upload->id;
            }

            $data['password'] = Hash::make($data['password']);

            unset($data['image']);

            return User::create($data);
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (isset($data['image'])) {

                $oldUpload = $user->image;

                $upload = $this->uploadService->replace(
                    $oldUpload,
                    $data['image'],
                    'users'
                );

                $data['upload_id'] = $upload->id;
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            unset($data['image']);

            $user->update($data);

            return $user->fresh('image');
        });
    }

    public function delete(User $user): bool
    {
        DB::transaction(function () use ($user) {

            if ($user->image) {
                $this->uploadService->delete($user->image);
            }

            $user->delete();
        });

        return true;
    }
}