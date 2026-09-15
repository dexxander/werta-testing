<?php

namespace CounselorDashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Which month to show — defaults to current month, or ?month=2026-08 from prev/next links
        $monthParam = $request->query('month');
        $month = $monthParam
            ? Carbon::createFromFormat('Y-m', $monthParam)->startOfMonth()
            : Carbon::now()->startOfMonth();

        $today = Carbon::now()->startOfDay();

        // Build a 6-row x 7-col grid, padding leading/trailing days with null
        $firstOfMonth = $month->copy()->startOfMonth();
        $startOffset  = ($firstOfMonth->dayOfWeek + 6) % 7;
        $daysInMonth  = $month->daysInMonth;

        $cells = [];
        for ($i = 0; $i < $startOffset; $i++) {
            $cells[] = null;
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $cells[] = $month->copy()->setDay($d);
        }
        while (count($cells) % 7 !== 0) {
            $cells[] = null;
        }
        $calendarWeeks = array_chunk($cells, 7);
        // TODO: pull real saved values from an Availability model once DB exists.
        // For now this seeds all 7 days as blank/unchecked defaults.
        $availability = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->map(fn($day) => (object) [
                'day'       => $day,
                'enabled'   => false,
                'start'     => null,
                'end'       => null,
            ]);
        
        $bookedSlots  = collect();
        // Dates that have booked slots, for showing a dot marker — empty for now
        $bookedDates = collect(); // e.g. ['2026-08-14', '2026-08-20']

        return view('counselor-dashboard::schedule', [
            'month'         => $month,
            'today'         => $today,
            'calendarWeeks' => $calendarWeeks,
            'prevMonth'     => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth'     => $month->copy()->addMonth()->format('Y-m'),
            'availability'  => $availability,
            'bookedSlots'   => $bookedSlots,
            'bookedDates'   => $bookedDates,
        ]);
    }
}