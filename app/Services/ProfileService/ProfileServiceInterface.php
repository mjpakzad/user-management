<?php

namespace App\Services\ProfileService;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface ProfileServiceInterface
{
    public function uploadAvatar(User $user, UploadedFile $file): array;
}
