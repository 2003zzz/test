<?php

namespace App\Services;

use App\Services\AuthService;
use Illuminate\Support\Facades\Log;

class CommonService
{
    private $currentUser = null;

    public function getCurrentUser()
    {
        if (!$this->currentUser) {
            $data = app(AuthService::class)->getUserRole();
            $this->currentUser = $data;
        }
        return $this->currentUser;
    }
}
