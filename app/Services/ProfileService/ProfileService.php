<?php

namespace App\Services\ProfileService;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function uploadAvatar(User $user, UploadedFile $file): array
    {
        $path = $file->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        $url  = Storage::disk('public')->url($path);
        return compact('url');
    }
}
