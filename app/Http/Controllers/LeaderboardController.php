<?php

namespace App\Http\Controllers;

use App\Models\LeaderboardEntry;

class LeaderboardController extends Controller
{
    public function index()
    {
        $entries = LeaderboardEntry::active()
            ->orderByRaw('rank IS NULL')
            ->orderBy('rank')
            ->orderByDesc('score')
            ->paginate(20);

        return view('leaderboard.index', compact('entries'));
    }
}
