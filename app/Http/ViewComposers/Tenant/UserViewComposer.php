<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\User;

class UserViewComposer
{
    public function compose($view)
    {
        $view->vc_user = User::first();
    }
}