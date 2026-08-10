<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Calendar\GetCalendarEventsAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CalendarController extends Controller
{
    /**
     * Display the check-in/check-out calendar page.
     */
    public function index()
    {
        return Inertia::render('Admin/Calendar/Index');
    }

    /**
     * Return check-in/check-out events for a cluster within a date range (used by FullCalendar).
     */
    public function events(Request $request)
    {
        $validated = $request->validate([
            'cluster_id' => 'required|integer|exists:clusters,id',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $events = app(GetCalendarEventsAction::class)->execute(
            (int) $validated['cluster_id'],
            $validated['start'],
            $validated['end'],
            Auth::user()
        );

        if ($events === null) {
            return response()->json([], 403);
        }

        return response()->json($events);
    }
}
