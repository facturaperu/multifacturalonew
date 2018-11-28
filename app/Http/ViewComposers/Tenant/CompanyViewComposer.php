<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\Company;

class CompanyViewComposer
{
    public function compose($view)
    {
        $view->vc_company = Company::first();// (int) cache('selected_user_id');
    }
}