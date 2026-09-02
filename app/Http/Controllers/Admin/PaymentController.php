<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberCard;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $payments,
        private PaymentService $paymentService,
    ) {
    }

    public function index(Request $request): View
    {
        $payments = $this->payments->paginateWithRelations($request->query('q'), $request->query('status'), 15);

        return view('admin.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $members = User::whereHas('role', fn ($q) => $q->where('name', 'member'))->orderBy('name')->get();
        $cards = MemberCard::where('status', 'active')->with('user', 'membership')->latest()->get();

        return view('admin.payments.create', compact('members', 'cards'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'member_card_id' => ['nullable', 'exists:member_cards,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'method' => ['required', 'string', 'max:30'],
            'status' => ['required', 'in:paid,pending,failed,refunded'],
        ]);

        $user = User::findOrFail($data['user_id']);
        $card = isset($data['member_card_id']) ? MemberCard::find($data['member_card_id']) : null;

        $this->paymentService->record(
            $user,
            $card,
            (float) $data['amount'],
            $data['method'],
            $data['status'],
        );

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
