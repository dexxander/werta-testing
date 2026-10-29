@extends('community::layout')
@section('community-content')
<a href="{{ $replyUrl }}" class="d-inline-block mb-4">&larr; {{ __('community::community.cancel_edit') }}</a>
<section class="community-card">
    <h1>{{ __('community::community.edit_reply') }}</h1>
    <p>{{ $post->title }}</p>
    <form method="post" action="{{ route('community.reply.update', ['post' => $post, 'reply' => $reply]) }}">
        @csrf
        @method('PATCH')
        <label for="reply-body">{{ __('community::community.message') }}</label>
        <textarea id="reply-body" name="body" rows="6" maxlength="5000" required class="form-control mb-3">{{ old('body', $reply->body) }}</textarea>
        <button class="community-button">{{ __('community::community.save_reply') }}</button>
    </form>
</section>
@endsection
