@php
    $statusLabels = [
        'submitted'            => ['label' => 'Waiting for Review', 'class' => 'bg-gray-100 text-gray-600'],
        'under_review'         => ['label' => 'Under Review', 'class' => 'bg-blue-50 text-blue-700'],
        'revisions_requested'  => ['label' => 'Revisions Requested', 'class' => 'bg-yellow-50 text-yellow-700'],
        'accepted'             => ['label' => 'Accepted', 'class' => 'bg-green-50 text-green-700'],
        'rejected'             => ['label' => 'Not Approved', 'class' => 'bg-red-50 text-red-700'],
        'published'            => ['label' => 'Approved', 'class' => 'bg-green-50 text-green-700'],
    ];
    $statusInfo = $statusLabels[$article->status] ?? ['label' => $article->status, 'class' => 'bg-gray-100 text-gray-600'];
@endphp

<a href="{{ $url }}" class="block bg-white rounded-2xl border border-gray-100 hover:border-[#C4A840]/30 hover:shadow-sm transition-all overflow-hidden group">
    <div class="h-40 overflow-hidden">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&size=400&background=C4A840&color=fff" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
    </div>
    <div class="p-4">
        @if($showStatus ?? false)
            <span class="inline-block mb-2 px-2 py-0.5 text-[10px] font-bold uppercase rounded {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
        @endif
        <h3 class="font-bold text-[#2C2416] mb-1 line-clamp-2">{{ $article->title }}</h3>
        @if($article->abstract)
            <p class="text-xs text-gray-500 line-clamp-2">{{ $article->abstract }}</p>
        @endif
        <div class="flex items-center gap-2 mt-3 text-[10px] text-gray-400 font-medium">
            <span class="px-2 py-0.5 bg-[#C4A840]/10 text-[#C4A840] rounded uppercase">{{ $article->access_type }}</span>
            <span>{{ $article->submitted_at->format('M d, Y') }}</span>
        </div>
    </div>
</a>