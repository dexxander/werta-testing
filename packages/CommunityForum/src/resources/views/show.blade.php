@extends('community::layout')
@section('community-content')
<a href="{{ route('community.index') }}" class="d-inline-block mb-4">&larr; {{ __('community::community.back') }}</a>
<article class="community-card">
    <span class="community-topic">{{ __('community::community.topics.'.$post->topic) }}</span>
    <h1>{{ $post->title }}</h1>
    <div class="community-meta"><span>{{ $post->author_name }}</span><time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time></div>
    <div class="community-body">{{ $post->body }}</div>
</article>
<section aria-labelledby="replies-heading">
    <h2 id="replies-heading" class="my-4">{{ __('community::community.reply_count', ['count' => $replies->total()]) }}</h2>
    @forelse($replies as $reply)
        <article class="community-card" id="reply-{{ $reply->id }}" style="scroll-margin-top:100px">
            <div class="community-meta"><strong>{{ $reply->author_name }}</strong><time datetime="{{ $reply->created_at->toIso8601String() }}">{{ $reply->created_at->diffForHumans() }}</time></div>
            @if($reply->parentReply)
                <blockquote class="community-reply-context">
                    <strong>{{ __('community::community.replying_to', ['name' => $reply->parentReply->author_name]) }}</strong>
                    <div class="community-body">{{ \Illuminate\Support\Str::limit($reply->parentReply->body, 240) }}</div>
                </blockquote>
            @endif
            <div class="community-body">{{ $reply->body }}</div>
            @if($author !== null)
                <a class="d-inline-block mt-3" href="{{ route('community.show', ['post' => $post, 'page' => $replies->currentPage(), 'reply_to' => $reply->id]) }}#reply-form">{{ __('community::community.reply_action') }}</a>
            @endif
            @if($ownerId !== null && $reply->owner_id === $ownerId)
                <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                    <a href="{{ route('community.reply.edit', ['post' => $post, 'reply' => $reply]) }}">{{ __('community::community.edit_reply') }}</a>
                    <form method="post" action="{{ route('community.reply.delete', ['post' => $post, 'reply' => $reply]) }}" onsubmit="return confirm({{ json_encode(__('community::community.confirm_delete_reply')) }})">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('community::community.delete_reply') }}</button>
                    </form>
                </div>
            @endif
        </article>
    @empty
        <p class="community-muted">{{ __('community::community.no_replies') }}</p>
    @endforelse
    @include('community::pagination', ['paginator' => $replies])
</section>
<section class="community-card" id="reply-form" style="scroll-margin-top:100px">
    <h2>{{ __('community::community.add_reply') }}</h2>
    @if($author !== null)
        <p class="community-muted">{{ __('community::community.posting_as', ['name' => $author]) }}</p>
        <form method="post" action="{{ route('community.reply', $post) }}">
            @csrf
            @if($replyTarget)
                <input type="hidden" name="parent_reply_id" value="{{ $replyTarget->id }}">
                <blockquote class="community-reply-context">
                    <strong>{{ __('community::community.replying_to', ['name' => $replyTarget->author_name]) }}</strong>
                    <div class="community-body">{{ \Illuminate\Support\Str::limit($replyTarget->body, 240) }}</div>
                    <a href="{{ route('community.show', ['post' => $post, 'page' => $replies->currentPage()]) }}#reply-form">{{ __('community::community.cancel_reply') }}</a>
                </blockquote>
            @endif
            <label for="reply-body">{{ __('community::community.message') }}</label>
            <textarea id="reply-body" name="body" rows="5" maxlength="5000" required class="form-control mb-3">{{ old('body') }}</textarea>
            <button class="community-button">{{ __('community::community.send_reply') }}</button>
        </form>
    @else
        @include('community::sign-in')
    @endif
</section>
@endsection
