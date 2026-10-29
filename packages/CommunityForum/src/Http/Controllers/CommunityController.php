<?php

namespace CommunityForum\Http\Controllers;

use CommunityForum\Models\CommunityPost;
use CommunityForum\Models\CommunityReply;
use CommunityForum\Support\WordFilter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CommunityController extends Controller
{
    public const TOPICS = ['general', 'wellbeing', 'study', 'relationships', 'parenting'];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'topic' => ['nullable', Rule::in(self::TOPICS)],
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $posts = CommunityPost::query()->withCount('replies')
            ->when($filters['topic'] ?? null, fn ($query, $topic) => $query->where('topic', $topic))
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where(function ($query) use ($term) {
                $query->where('title', 'like', '%'.$term.'%')->orWhere('body', 'like', '%'.$term.'%');
            }))
            ->latest('id')->paginate(12)->withQueryString();

        return view('community::index', [
            'posts' => $posts, 'topics' => self::TOPICS, 'filters' => $filters,
            'author' => $this->author($request),
        ]);
    }

    public function show(Request $request, CommunityPost $post)
    {
        $request->validate(['reply_to' => ['nullable', 'integer']]);
        $targetId = $request->old('parent_reply_id', $request->query('reply_to'));
        $replyTarget = is_scalar($targetId) && ctype_digit((string) $targetId)
            ? $post->replies()->find($targetId) : null;

        return view('community::show', [
            'post' => $post, 'replies' => $post->replies()->with('parentReply')->oldest('id')->paginate(20),
            'replyTarget' => $replyTarget,
            'ownerId' => $this->author($request) !== null ? $request->session()->get('community_owner_id') : null,
            'author' => $this->author($request),
        ]);
    }

    public function store(Request $request, WordFilter $wordFilter)
    {
        $author = $this->author($request);
        abort_if($author === null, 403);
        $data = $request->validate([
            'topic' => ['required', Rule::in(self::TOPICS)],
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'max:10000'],
        ]);
        $data['title'] = $wordFilter->filter($data['title']);
        $data['body'] = $wordFilter->filter($data['body']);
        $post = CommunityPost::create($data + ['author_name' => $author]);

        return redirect()->route('community.show', $post)->with('success', __('community::community.posted'));
    }

    public function reply(Request $request, CommunityPost $post, WordFilter $wordFilter)
    {
        $author = $this->author($request);
        abort_if($author === null, 403);
        $data = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'parent_reply_id' => ['nullable', 'integer', Rule::exists('community_replies', 'id')
                ->where('community_post_id', $post->id)],
        ]);
        $data['body'] = $wordFilter->filter($data['body']);
        if (! $request->session()->has('community_owner_id')) {
            $request->session()->put('community_owner_id', (string) Str::uuid());
        }
        $reply = $post->replies()->create($data + [
            'author_name' => $author,
            'owner_id' => $request->session()->get('community_owner_id'),
        ]);
        $page = (int) ceil($post->replies()->where('id', '<=', $reply->id)->count() / 20);

        return redirect()->to(route('community.show', ['post' => $post, 'page' => $page]).'#reply-'.$reply->id)
            ->with('success', __('community::community.replied'));
    }

    public function editReply(Request $request, CommunityPost $post, CommunityReply $reply)
    {
        $this->authorizeReply($request, $post, $reply);

        return view('community::edit-reply', ['post' => $post, 'reply' => $reply, 'replyUrl' => $this->replyUrl($post, $reply)]);
    }

    public function updateReply(Request $request, CommunityPost $post, CommunityReply $reply, WordFilter $wordFilter)
    {
        $this->authorizeReply($request, $post, $reply);
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);
        $reply->update(['body' => $wordFilter->filter($data['body'])]);

        return redirect()->to($this->replyUrl($post, $reply))->with('success', __('community::community.reply_updated'));
    }

    public function deleteReply(Request $request, CommunityPost $post, CommunityReply $reply)
    {
        $this->authorizeReply($request, $post, $reply);
        $position = $post->replies()->where('id', '<=', $reply->id)->count();
        $reply->delete();
        $page = max(1, (int) ceil(min($position, $post->replies()->count()) / 20));

        return redirect()->route('community.show', ['post' => $post, 'page' => $page])
            ->with('success', __('community::community.reply_deleted'));
    }

    private function authorizeReply(Request $request, CommunityPost $post, CommunityReply $reply): void
    {
        abort_unless((int) $reply->community_post_id === (int) $post->id, 404);
        abort_unless($this->author($request) !== null && $reply->owner_id !== null
            && $reply->owner_id === $request->session()->get('community_owner_id'), 403);
    }

    private function replyUrl(CommunityPost $post, CommunityReply $reply): string
    {
        $page = max(1, (int) ceil($post->replies()->where('id', '<=', $reply->id)->count() / 20));

        return route('community.show', ['post' => $post, 'page' => $page]).'#reply-'.$reply->id;
    }

    private function author(Request $request): ?string
    {
        foreach (['client', 'parent', 'counselor'] as $role) {
            if ($request->session()->get($role.'_logged_in')) {
                return mb_substr((string) $request->session()->get($role.'_profile.username',
                    $request->session()->get($role.'_name', ucfirst($role).' User')), 0, 255);
            }
        }

        return null;
    }
}
