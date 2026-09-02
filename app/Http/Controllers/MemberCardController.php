<?php

namespace App\Http\Controllers;

use App\Models\MemberCard;
use Illuminate\View\View;

class MemberCardController extends Controller
{
    public function print(MemberCard $card): View
    {
        $user = auth()->user();

        abort_unless($user->isAdmin() || $card->user_id === $user->id, 403);

        $card->load('membership', 'user');

        return view('member-cards.print', compact('card'));
    }
}
