<div role="group" aria-label="{{ __('nav.language') }}" style="display:flex;align-items:center;justify-content:center;gap:4px;flex-wrap:wrap;margin:8px 0;">
    @foreach(['ms' => 'BM', 'en' => 'BI', 'zh' => '中文'] as $code => $label)
        <a href="{{ route('language.switch', $code) }}" lang="{{ $code }}"
           aria-label="{{ __('nav.language_names.'.$code) }}"
           @if(app()->getLocale() === $code) aria-current="true" @endif
           style="padding:5px 9px;border:1px solid #7b6b35;border-radius:5px;font-size:13px;line-height:1.4;text-decoration:none;background:{{ app()->getLocale() === $code ? '#7b6b35' : '#fdfaf4' }};color:{{ app()->getLocale() === $code ? '#fff' : '#574b22' }};">{{ $label }}</a>
    @endforeach
</div>
