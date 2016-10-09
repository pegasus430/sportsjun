<?php

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;

interface Composer
{
    /**
     * @param View $view
     */
    public function compose(View $view);
}
