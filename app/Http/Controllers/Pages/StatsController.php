<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Page\PageProtection;
use App\Models\Page\PageVersion;
use App\Models\Subject\SubjectCategory;
use App\Models\Subject\TimeChronology;
use App\Models\Subject\TimeDivision;
use App\Models\User\User;
use App\Services\PageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class StatsController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Subject Page Controller
    |--------------------------------------------------------------------------
    |
    | Handles subject pages.
    |
    */

    /**
     * Shows a page.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getStatsPage($id) {
        $page = Page::visible(Auth::check() ? Auth::user() : null)->where('id', $id)->first();
        if (!$page) {
            abort(404);
        }

        return view('pages.stats', [
            'page' => $page,
        ] + ($page->category->subject['key'] == 'people' || $page->category->subject['key'] == 'time' ? [
            'dateHelper' => new TimeDivision,
        ] : []));
    }
}