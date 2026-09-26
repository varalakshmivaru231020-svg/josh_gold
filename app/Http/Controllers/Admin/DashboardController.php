<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\GalleryPhoto;
use App\Models\MetalRate;
use App\Models\Office;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Latest published rate and the one before it (for the ▲/▼ change)
        $latest = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
            ->first();

        $previous = null;
        if ($latest) {
            $previous = MetalRate::where(function ($q) use ($latest) {
                $q->where('rate_date', '<', $latest->rate_date)
                  ->orWhere(function ($same) use ($latest) {
                      $same->where('rate_date', $latest->rate_date)->where('id', '<', $latest->id);
                  });
            })
            ->orderByDesc('rate_date')
            ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
            ->first();
        }

        // Which of today's two sessions (IST) already have a rate
        $today         = Carbon::now(MetalRate::TIMEZONE)->toDateString();
        $todaySessions = MetalRate::where('rate_date', $today)->pluck('session')->all();

        $recentRates = MetalRate::orderByDesc('rate_date')
            ->orderByRaw("CASE session WHEN 'evening' THEN 0 ELSE 1 END")
            ->take(8)
            ->get();

        $recentMessages = ContactMessage::latest()->take(5)->get();

        $counts = [
            'unread_messages' => ContactMessage::unread()->count(),
            'sample_rates'    => MetalRate::where('is_sample', true)->count(),
            'bank_accounts'   => BankAccount::count(),
            'services'        => Service::count(),
            'news'            => Blog::count(),
            'gallery'         => GalleryPhoto::count(),
            'offices'         => Office::count(),
        ];

        return view('admin.dashboard', compact(
            'latest', 'previous', 'todaySessions', 'recentRates', 'recentMessages', 'counts'
        ));
    }
}
