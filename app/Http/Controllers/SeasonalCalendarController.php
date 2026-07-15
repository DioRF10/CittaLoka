<?php

namespace App\Http\Controllers;

use App\Models\SeasonalEvent;
use Illuminate\Http\Request;

class SeasonalCalendarController extends Controller
{
    /**
     * Halaman utama Seasonal Calendar.
     * Route: GET /seasonal-calendar
     */
    public function index(Request $request)
    {
        $year   = (int) $request->input('year', now()->year);
        $locale = app()->getLocale();

        // Ambil semua event aktif dalam tahun yang dipilih
        $events = SeasonalEvent::active()
            ->where(function ($q) use ($year) {
                $q->whereYear('start_date', $year)
                  ->orWhereYear('end_date', $year);
            })
            ->orderBy('start_date')
            ->get();

        // Kelompokkan per bulan (1-12)
        $eventsByMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $eventsByMonth[$m] = $events->filter(function ($event) use ($m, $year) {
                $start      = $event->start_date;
                $end        = $event->end_date ?? $event->start_date;
                $monthStart = \Carbon\Carbon::create($year, $m, 1)->startOfMonth();
                $monthEnd   = \Carbon\Carbon::create($year, $m, 1)->endOfMonth();

                return $start->lte($monthEnd) && $end->gte($monthStart);
            })->values();
        }

        // Event terdekat dari sekarang
        $upcomingEvent = SeasonalEvent::active()
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->first();

        return view('pages.seasonal-calendar', compact(
            'eventsByMonth', 'year', 'locale', 'upcomingEvent'
        ));
    }

    /**
     * Detail satu event.
     * Route: GET /seasonal-calendar/{id}
     */
    public function show(int $id)
    {
        $event  = SeasonalEvent::with(['experiences' => function($q) {
            $q->where('status', 'active');
        }])->active()->findOrFail($id);
        $locale = app()->getLocale();

        return view('pages.seasonal-calendar-detail', compact('event', 'locale'));
    }
}