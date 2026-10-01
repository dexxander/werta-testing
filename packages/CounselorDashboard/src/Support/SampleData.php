<?php

namespace CounselorDashboard\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;

final class SampleData
{
    public static function clients(): Collection
    {
        $today = Carbon::today();

        return collect([
            (object) [
                'client_label'      => 'C-1042',
                'display_name'      => 'C-1042',
                'subtitle'          => 'Individual · Online',
                'last_session_date' => $today->copy()->subDays(3)->format('j M Y'),
                'primary_focus'     => 'Academic Anxiety',
                'status'            => 'Active',
                'has_report'        => true,
                'duration'          => '50 mins',
                'modality'          => 'Video call',
                'clinical_notes'    => 'Discussed the upcoming exam timetable and built a weekly study plan.',
                'interventions'     => 'Goal setting, structured schedule review, and breathing exercise.',
                'next_steps'        => 'Apply the 25-minute study intervals and note any focus blocks.',
            ],
            (object) [
                'client_label'      => 'C-1057',
                'display_name'      => 'C-1057',
                'subtitle'          => 'Individual · In person',
                'last_session_date' => $today->copy()->subDays(6)->format('j M Y'),
                'primary_focus'     => 'Depression & Mood',
                'status'            => 'Active',
                'has_report'        => true,
                'duration'          => '50 mins',
                'modality'          => 'In person',
                'clinical_notes'    => 'Reviewed weekly mood tracking journal and daily physical movement.',
                'interventions'     => 'Activity scheduling and morning routine planning.',
                'next_steps'        => 'Aim for a 15-minute morning walk three times this week.',
            ],
            (object) [
                'client_label'      => 'C-1063',
                'display_name'      => 'C-1063',
                'subtitle'          => 'Individual · Online',
                'last_session_date' => $today->copy()->subDays(10)->format('j M Y'),
                'primary_focus'     => 'Career Transitions',
                'status'            => 'Active',
                'has_report'        => false,
                'duration'          => null,
                'modality'          => null,
                'clinical_notes'    => null,
                'interventions'     => null,
                'next_steps'        => null,
            ],
            (object) [
                'client_label'      => 'G-07',
                'display_name'      => 'G-07',
                'subtitle'          => 'Group (4 members) · Online',
                'last_session_date' => $today->copy()->subDays(8)->format('j M Y'),
                'primary_focus'     => 'Academic Anxiety',
                'status'            => 'Active',
                'has_report'        => true,
                'duration'          => '75 mins',
                'modality'          => 'Video call',
                'clinical_notes'    => 'Facilitated group discussion on managing deadlines and peer expectations.',
                'interventions'     => 'Peer sharing reflection and guided group relaxation exercise.',
                'next_steps'        => 'Each member to identify one supportive habit to test before next group meet.',
            ],
            (object) [
                'client_label'      => 'C-0988',
                'display_name'      => 'C-0988',
                'subtitle'          => 'Individual · Online',
                'last_session_date' => $today->copy()->subDays(45)->format('j M Y'),
                'primary_focus'     => 'Relationship Counseling',
                'status'            => 'Discharged',
                'has_report'        => true,
                'duration'          => '50 mins',
                'modality'          => 'Video call',
                'clinical_notes'    => 'Final review session. Discussed sustained boundary setting and communication habits.',
                'interventions'     => 'Progress reflection and maintenance planning.',
                'next_steps'        => 'Discharge complete; client welcome to check in on an as-needed basis.',
            ],
            (object) [
                'client_label'      => 'C-1071',
                'display_name'      => 'C-1071',
                'subtitle'          => 'Individual · In person',
                'last_session_date' => $today->copy()->subDays(2)->format('j M Y'),
                'primary_focus'     => 'Depression & Mood',
                'status'            => 'Active',
                'has_report'        => false,
                'duration'          => null,
                'modality'          => null,
                'clinical_notes'    => null,
                'interventions'     => null,
                'next_steps'        => null,
            ],
        ]);
    }

    public static function appointments(): Collection
    {
        $today = Carbon::today();

        return collect([
            (object) [
                'date'         => $today->copy(),
                'time'         => '10:00 AM',
                'time_range'   => '10:00 AM – 10:50 AM',
                'client_label' => 'C-1042',
                'type'         => 'Online',
                'session_type' => 'Online',
                'status'       => 'Confirmed',
                'month_short'  => $today->format('M'),
                'day'          => $today->format('j'),
            ],
            (object) [
                'date'         => $today->copy(),
                'time'         => '3:30 PM',
                'time_range'   => '3:30 PM – 4:20 PM',
                'client_label' => 'C-1057',
                'type'         => 'In person',
                'session_type' => 'In person',
                'status'       => 'Confirmed',
                'month_short'  => $today->format('M'),
                'day'          => $today->format('j'),
            ],
            (object) [
                'date'         => $today->copy()->addDay(),
                'time'         => '11:00 AM',
                'time_range'   => '11:00 AM – 12:15 PM',
                'client_label' => 'G-07',
                'type'         => 'Group · Online',
                'session_type' => 'Group · Online',
                'status'       => 'Confirmed',
                'month_short'  => $today->copy()->addDay()->format('M'),
                'day'          => $today->copy()->addDay()->format('j'),
            ],
            (object) [
                'date'         => $today->copy()->addDays(3),
                'time'         => '2:00 PM',
                'time_range'   => '2:00 PM – 2:50 PM',
                'client_label' => 'C-1063',
                'type'         => 'Online',
                'session_type' => 'Online',
                'status'       => 'Pending',
                'month_short'  => $today->copy()->addDays(3)->format('M'),
                'day'          => $today->copy()->addDays(3)->format('j'),
            ],
        ]);
    }

    public static function availability(): Collection
    {
        return collect([
            (object) [
                'day'     => 'Mon',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '17:00',
            ],
            (object) [
                'day'     => 'Tue',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '17:00',
            ],
            (object) [
                'day'     => 'Wed',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '17:00',
            ],
            (object) [
                'day'     => 'Thu',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '17:00',
            ],
            (object) [
                'day'     => 'Fri',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '17:00',
            ],
            (object) [
                'day'     => 'Sat',
                'enabled' => true,
                'start'   => '09:00',
                'end'     => '13:00',
            ],
            (object) [
                'day'     => 'Sun',
                'enabled' => false,
                'start'   => null,
                'end'     => null,
            ],
        ]);
    }
}
