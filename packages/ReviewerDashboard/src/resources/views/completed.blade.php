@extends('reviewerdashboard::layout')

@section('title', __('Completed reviews'))

@section('content')
<div>
    <div class="mb-6 sm:mb-8">
        <h1 class="font-display text-3xl sm:text-4xl text-dark">{{ __('Completed reviews') }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ __('Everything you have evaluated, newest first.') }}</p>
    </div>

    <div class="bg-white rounded-2xl p-4 sm:p-6 card-ring">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-cream text-primary font-bold border-b border-gold/20 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg">{{ __('Manuscript') }}</th>
                        <th class="px-4 py-3">{{ __('Category') }}</th>
                        <th class="px-4 py-3">{{ __('Recommendation') }}</th>
                        <th class="px-4 py-3">{{ __('Sale verdict') }}</th>
                        <th class="px-4 py-3">{{ __('Shown on article') }}</th>
                        <th class="px-4 py-3 rounded-tr-lg">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-dark"><a href="{{ route('reviewer.reviews.show', $review) }}" class="hover:text-primary">{{ $review->submission->title }}</a></td>
                            <td class="px-4 py-3">{{ $review->submission->category_label ? __($review->submission->category_label) : '—' }}</td>
                            <td class="px-4 py-3">
                                @if($review->status === 'declined')
                                    <span class="text-xs font-bold uppercase px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ __('Declined') }}</span>
                                @else
                                    {{ __($review->recommendationLabel() ?? '—') }}
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $review->suitable_for_sale === null ? '—' : ($review->suitable_for_sale ? __('Sell as premium') : __('Publish free')) }}</td>
                            <td class="px-4 py-3">{!! $review->show_on_article ? '<i class="bi bi-check-circle-fill text-green-600"></i>' : '<i class="bi bi-incognito text-gray-400"></i>' !!}</td>
                            <td class="px-4 py-3">{{ optional($review->submitted_at)->translatedFormat('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-14 text-center text-gray-400"><i class="bi bi-journal-check text-4xl block mb-2"></i>{{ __('No completed reviews yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
