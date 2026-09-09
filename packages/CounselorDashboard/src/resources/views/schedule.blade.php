@extends('counselor-dashboard::layout')

@section('content')
<div class="mb-6 sm:mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-dark">Schedule & Slots</h1>
    <p class="text-sm text-gray-500 mt-1">Manage your weekly availability and upcoming client sessions.</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 sm:gap-8">
    
    <div class="xl:col-span-1 space-y-6">
        
    <div class="bg-white rounded-2xl p-5 border border-gold/20 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-dark">{{ $month->format('F Y') }}</h2>
            <div class="flex gap-3 text-gray-400">
                <a href="{{ route('counselor.schedule', ['month' => $prevMonth]) }}" class="hover:text-gold transition-colors"><i class="bi bi-chevron-left"></i></a>
                <a href="{{ route('counselor.schedule', ['month' => $nextMonth]) }}" class="hover:text-gold transition-colors"><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
        
        <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2 font-bold text-gray-400 uppercase">
            <div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div><div>Su</div>
        </div>
        
        <div class="grid grid-cols-7 gap-1 text-center text-sm">
            @foreach($calendarWeeks as $week)
                @foreach($week as $day)
                    @if(is_null($day))
                        <div></div>
                    @else
                        @php
                            $isToday = $day->isSameDay($today);
                            $hasBooking = $bookedDates->contains($day->format('Y-m-d'));
                        @endphp
                        <div class="py-1.5 rounded-lg cursor-pointer transition-colors relative
                            {{ $isToday ? 'bg-gold text-white font-bold shadow-sm' : 'hover:bg-cream hover:text-primary' }}">
                            {{ $day->day }}
                            @if($hasBooking)
                                <span class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1 h-1 {{ $isToday ? 'bg-white' : 'bg-red-400' }} rounded-full"></span>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-dark">Default Availability</h2>
                <button class="text-sm font-semibold text-gold hover:text-primary transition-colors">Save</button>
            </div>

            <div class="space-y-4">
                @foreach($availability as $dayItem)
                    <div class="flex items-center justify-between p-3 rounded-lg {{ $dayItem->enabled ? 'bg-gray-50 border border-gray-100' : 'border border-gray-100 opacity-50' }}">
                        <div class="flex items-center gap-3">
                            <input type="checkbox"
                                name="availability[{{ $dayItem->day }}][enabled]"
                                {{ $dayItem->enabled ? 'checked' : '' }}
                                class="w-4 h-4 text-gold border-gray-300 rounded focus:ring-gold">
                            <span class="font-semibold text-sm text-gray-700">{{ $dayItem->day }}</span>
                        </div>
                        @if($dayItem->enabled)
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <input type="time" name="availability[{{ $dayItem->day }}][start]" value="{{ $dayItem->start }}" class="border border-gray-200 rounded px-2 py-1 bg-white outline-none focus:border-gold">
                                <span>-</span>
                                <input type="time" name="availability[{{ $dayItem->day }}][end]" value="{{ $dayItem->end }}" class="border border-gray-200 rounded px-2 py-1 bg-white outline-none focus:border-gold">
                            </div>
                        @else
                            <span class="text-sm font-medium text-gray-400 italic">Unavailable</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl p-6 border border-gold/20 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-dark">Booked Slots</h2>
                <div class="flex gap-2">
                    <select class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 bg-gray-50 outline-none focus:border-gold">
                        <option>Selected Day</option>
                        <option>This Week</option>
                    </select>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($bookedSlots as $slot)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-gold/30 hover:shadow-sm transition-all gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gold/10 text-gold flex flex-col items-center justify-center shrink-0">
                                <span class="text-xs font-bold uppercase">{{ $slot->month_short }}</span>
                                <span class="text-lg font-extrabold leading-none">{{ $slot->day }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-dark">{{ $slot->client_label }}</h4>
                                <p class="text-xs text-gray-500 mt-1"><i class="bi bi-clock mr-1"></i> {{ $slot->time_range }}</p>
                                <span class="inline-block mt-2 px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase rounded">{{ $slot->session_type }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 sm:flex-col sm:items-end">
                            <button class="px-4 py-1.5 bg-gold hover:bg-primary text-white text-sm font-semibold rounded-lg transition-colors">Join Call</button>
                            <button class="px-4 py-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold rounded-lg transition-colors">Reschedule</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-12">
                        <i class="bi bi-calendar-x text-4xl block mb-2"></i>
                        No booked slots for this day.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection