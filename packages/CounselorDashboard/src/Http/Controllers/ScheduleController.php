<?php

namespace CounselorDashboard\Http\Controllers;

use CounselorDashboard\Support\SampleData;
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
        
        $availability = SampleData::availability();
        $bookedSlots  = SampleData::appointments();
        $bookedDates  = $bookedSlots->map(fn($slot) => $slot->date->format('Y-m-d'));

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