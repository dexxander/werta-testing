<?php

namespace CommunityForum\Tests;

use CommunityForum\Models\CommunityPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityForumTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_browse_all_languages_but_cannot_publish(): void
    {
        foreach (['en', 'ms', 'zh'] as $locale) {
            $this->withSession(['locale' => $locale])->get('/community')->assertOk()
                ->assertDontSee('community::community.');
        }
        $this->post('/community', ['topic' => 'general', 'title' => 'Hello', 'body' => 'Welcome'])->assertForbidden();
        $post = $this->discussion();
        $this->post('/community/'.$post->id.'/replies', ['body' => 'Hello'])->assertForbidden();
        $this->assertDatabaseCount('community_replies', 0);
    }

    public function test_members_can_publish_and_reply_with_server_selected_authors(): void
    {
        foreach (['client', 'parent', 'counselor'] as $role) {
            $this->flushSession();
            $this->withSession([$role.'_logged_in' => true, $role.'_profile' => ['username' => 'Member', 'picture' => 'bi-person-circle']])
                ->post('/community', ['topic' => 'general', 'title' => 'Hello', 'body' => 'Welcome', 'author_name' => 'Forged'])
                ->assertRedirect();
            $post = CommunityPost::latest('id')->firstOrFail();
            $this->assertSame('Member', $post->author_name);
            $this->post('/community/'.$post->id.'/replies', ['body' => 'Thanks', 'author_name' => 'Forged'])->assertRedirect();
            $this->assertSame('Member', $post->replies()->firstOrFail()->author_name);
            $this->get('/community/'.$post->id)->assertOk()->assertSee('Thanks');
        }
    }

    public function test_input_validation_search_and_escaped_content(): void
    {
        $this->withSession(['client_logged_in' => true]);
        $this->post('/community', ['topic' => 'invalid', 'title' => '', 'body' => ''])->assertSessionHasErrors(['topic', 'title', 'body']);
        $post = $this->discussion(['title' => 'Study habits', 'topic' => 'study', 'body' => '<script>alert(1)</script>']);
        $this->discussion(['title' => 'Family time', 'topic' => 'parenting']);
        $this->get('/community?topic=study&q=habits')->assertOk()->assertSee('Study habits')->assertDontSee('Family time');
        $this->get('/community/'.$post->id)->assertOk()->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $this->post('/community/'.$post->id.'/replies', ['body' => str_repeat('x', 5001)])->assertSessionHasErrors('body');
        $this->get('/community/999999')->assertNotFound();
    }

    public function test_reply_redirects_to_its_page(): void
    {
        $post = $this->discussion();
        for ($i = 0; $i < 20; $i++) {
            $post->replies()->create(['body' => 'Earlier reply', 'author_name' => 'Member']);
        }
        $response = $this->withSession(['client_logged_in' => true])->post('/community/'.$post->id.'/replies', ['body' => 'Latest reply']);
        $reply = $post->replies()->latest('id')->firstOrFail();
        $response->assertRedirect(route('community.show', ['post' => $post, 'page' => 2]).'#reply-'.$reply->id);
        $this->get('/community/'.$post->id.'?page=2')->assertOk()->assertSee('Latest reply');
    }

    public function test_members_can_reply_to_a_reply_and_keep_context_across_pages(): void
    {
        $post = $this->discussion();
        $parent = $post->replies()->create(['body' => '<script>Original reply</script>', 'author_name' => 'Original member']);
        for ($i = 0; $i < 19; $i++) {
            $post->replies()->create(['body' => 'Other reply', 'author_name' => 'Member']);
        }
        $this->withSession(['parent_logged_in' => true]);
        $this->get(route('community.show', ['post' => $post, 'reply_to' => $parent->id]))
            ->assertOk()->assertSee('name="parent_reply_id" value="'.$parent->id.'"', false)
            ->assertSee('Replying to Original member');
        $this->post(route('community.reply', $post), ['body' => 'A direct response', 'parent_reply_id' => $parent->id])
            ->assertRedirect();
        $reply = $post->replies()->latest('id')->firstOrFail();
        $this->assertEquals($parent->id, $reply->parent_reply_id);
        foreach (['en', 'ms', 'zh'] as $locale) {
            $this->withSession(['locale' => $locale])->get(route('community.show', ['post' => $post, 'page' => 2]))
                ->assertOk()->assertSee('A direct response')->assertSee('Original member')
                ->assertSee('&lt;script&gt;Original reply&lt;/script&gt;', false)
                ->assertDontSee('<script>Original reply</script>', false)->assertDontSee('community::community.');
        }
        $this->post(route('community.reply', $post), ['body' => 'Another level', 'parent_reply_id' => $reply->id])->assertRedirect();
        $this->assertEquals($reply->id, $post->replies()->latest('id')->firstOrFail()->parent_reply_id);
    }

    public function test_reply_targets_must_exist_in_the_same_discussion(): void
    {
        $post = $this->discussion();
        $other = $this->discussion()->replies()->create(['body' => 'Other discussion', 'author_name' => 'Member']);
        $this->withSession(['client_logged_in' => true]);
        foreach ([$other->id, 999999, 'invalid', [$other->id]] as $target) {
            $this->post(route('community.reply', $post), ['body' => 'Response', 'parent_reply_id' => $target])
                ->assertSessionHasErrors('parent_reply_id');
        }
        $this->assertSame(0, $post->replies()->count());
        $this->get(route('community.show', $post))->assertOk();
    }

    public function test_validation_preserves_the_selected_reply_and_message(): void
    {
        $post = $this->discussion();
        $parent = $post->replies()->create(['body' => 'Original', 'author_name' => 'Member']);
        $url = route('community.show', ['post' => $post, 'reply_to' => $parent->id]);
        $this->withSession(['client_logged_in' => true])->from($url)
            ->post(route('community.reply', $post), ['body' => str_repeat('x', 5001), 'parent_reply_id' => $parent->id])
            ->assertRedirect($url)->assertSessionHasErrors('body');
        $this->get($url)->assertOk()->assertSee('name="parent_reply_id" value="'.$parent->id.'"', false)
            ->assertSee(str_repeat('x', 5001));
    }

    public function test_post_titles_bodies_and_replies_are_filtered_before_saving(): void
    {
        config(['community.filtered_words' => ['shit', 'bodoh']]);
        $this->withSession(['client_logged_in' => true])->post('/community', [
            'topic' => 'general', 'title' => 'SHIT! A discussion',
            'body' => 'Do not say bodoh or ShIt.',
        ])->assertRedirect();

        $post = CommunityPost::latest('id')->firstOrFail();
        $this->assertSame('****! A discussion', $post->title);
        $this->assertSame('Do not say ***** or ****.', $post->body);
        $this->post('/community/'.$post->id.'/replies', ['body' => 'BODOH? shit!'])
            ->assertRedirect();
        $this->assertSame('*****? ****!', $post->replies()->firstOrFail()->body);
        $this->get('/community/'.$post->id)->assertOk()->assertSee('*****? ****!');
    }

    public function test_word_filter_preserves_other_words_and_counts_unicode_characters(): void
    {
        config(['community.filtered_words' => ['ass', 'bête', 'a+b', '']]);
        $filter = new \CommunityForum\Support\WordFilter;
        $this->assertSame('class passage assert ****! *** ***', $filter->filter('class passage assert BÊTE! ass a+b'));
        $this->assertSame('Hello, world!', $filter->filter('Hello, world!'));
        config(['community.filtered_words' => []]);
        $this->assertSame('ass', $filter->filter('ass'));
    }

    public function test_default_word_list_covers_all_configured_terms(): void
    {
        $filter = new \CommunityForum\Support\WordFilter;

        foreach (config('community.filtered_words') as $word) {
            $this->assertSame(str_repeat('*', mb_strlen($word, 'UTF-8')), $filter->filter($word), $word);
        }

        $this->assertSame('************* ***** ********', $filter->filter('MOTHERFUCKERS BODOH PUKIMAKK'));
        $this->assertSame('class assignment classic', $filter->filter('class assignment classic'));
    }

    public function test_chinese_is_filtered_in_unspaced_sentences_and_longest_phrases_win(): void
    {
        $filter = new \CommunityForum\Support\WordFilter;
        $this->assertSame('你这个**不要再说***了。', $filter->filter('你这个混蛋不要再说他妈的了。'));
        $this->assertSame('你這個**不要再說***了。', $filter->filter('你這個混蛋不要再說他媽的了。'));
        $this->assertSame('他说****，她說****。', $filter->filter('他说狗娘养的，她說屌你老母。'));
        $this->assertSame('我在操作电脑，靠近窗口。', $filter->filter('我在操作电脑，靠近窗口。'));

        config(['community.filtered_words' => ['婊子', '婊子养的']]);
        $this->assertSame('****', $filter->filter('婊子养的'));
    }

    public function test_members_can_edit_their_reply_with_filtering_and_validation(): void
    {
        $post = $this->discussion();
        foreach (['client', 'parent', 'counselor'] as $role) {
            $this->flushSession();
            $this->withSession([$role.'_logged_in' => true])->post(route('community.reply', $post), ['body' => 'Original']);
            $reply = $post->replies()->latest('id')->firstOrFail();
            $edit = route('community.reply.edit', ['post' => $post, 'reply' => $reply]);
            $update = route('community.reply.update', ['post' => $post, 'reply' => $reply]);
            $this->assertNotNull($reply->owner_id);
            foreach (['en', 'ms', 'zh'] as $locale) {
                $this->withSession(['locale' => $locale])->get($edit)->assertOk()->assertSee('Original')->assertDontSee('community::community.');
                $this->get(route('community.show', $post))->assertOk()->assertSee($edit, false);
            }
            foreach (['', str_repeat('x', 5001)] as $body) {
                $this->from($edit)->patch($update, ['body' => $body])->assertRedirect($edit)->assertSessionHasErrors('body');
                $this->assertSame('Original', $reply->fresh()->body);
            }
            $this->patch($update, ['body' => 'SHIT! Updated', 'owner_id' => 'forged', 'author_name' => 'Forged'])
                ->assertRedirect(route('community.show', ['post' => $post, 'page' => 1]).'#reply-'.$reply->id);
            $this->assertSame('****! Updated', $reply->fresh()->body);
            $this->assertSame($reply->owner_id, $reply->fresh()->owner_id);
            $this->assertSame($reply->author_name, $reply->fresh()->author_name);
        }
    }

    public function test_other_sessions_guests_and_legacy_replies_cannot_be_modified(): void
    {
        $post = $this->discussion();
        $this->withSession(['client_logged_in' => true, 'client_profile' => ['username' => 'Same name', 'picture' => 'bi-person-circle']])
            ->post(route('community.reply', $post), ['body' => 'Protected']);
        $reply = $post->replies()->firstOrFail();
        $edit = route('community.reply.edit', ['post' => $post, 'reply' => $reply]);
        $url = route('community.reply.update', ['post' => $post, 'reply' => $reply]);
        $this->flushSession();
        foreach ([[], ['client_logged_in' => true, 'client_profile' => ['username' => 'Same name', 'picture' => 'bi-person-circle']]] as $session) {
            $this->withSession($session)->get($edit)->assertForbidden();
            $this->patch($url, ['body' => 'Hacked', 'owner_id' => $reply->owner_id])->assertForbidden();
            $this->delete($url)->assertForbidden();
            $this->get(route('community.show', $post))->assertOk()->assertDontSee($edit, false);
        }
        $legacy = $post->replies()->create(['body' => 'Legacy', 'author_name' => 'Same name']);
        $legacyUrl = route('community.reply.update', ['post' => $post, 'reply' => $legacy]);
        $this->patch($legacyUrl, ['body' => 'Hacked'])->assertForbidden();
        $this->delete($legacyUrl)->assertForbidden();
        $this->assertSame('Protected', $reply->fresh()->body);
    }

    public function test_delete_keeps_child_replies_and_clamps_pagination_and_checks_discussion(): void
    {
        $post = $this->discussion();
        for ($i = 0; $i < 20; $i++) {
            $post->replies()->create(['body' => 'Earlier', 'author_name' => 'Member']);
        }
        $this->withSession(['client_logged_in' => true])->post(route('community.reply', $post), ['body' => 'Mine']);
        $reply = $post->replies()->latest('id')->firstOrFail();
        $other = $this->discussion();
        $wrongUrl = route('community.reply.update', ['post' => $other, 'reply' => $reply]);
        $this->patch($wrongUrl, ['body' => 'Wrong discussion'])->assertNotFound();
        $this->delete($wrongUrl)->assertNotFound();
        $this->patch(route('community.reply.update', ['post' => $post, 'reply' => $reply]), ['body' => 'Updated'])
            ->assertRedirect(route('community.show', ['post' => $post, 'page' => 2]).'#reply-'.$reply->id);
        $child = $post->replies()->firstOrFail();
        $child->update(['parent_reply_id' => $reply->id]);
        $this->delete(route('community.reply.delete', ['post' => $post, 'reply' => $reply]))
            ->assertRedirect(route('community.show', ['post' => $post, 'page' => 1]));
        $this->assertDatabaseMissing('community_replies', ['id' => $reply->id]);
        $this->assertNull($child->fresh()->parent_reply_id);
        $this->assertSame(20, $post->replies()->count());
    }

    public function test_logout_clears_reply_ownership(): void
    {
        $this->withSession(['client_logged_in' => true, 'community_owner_id' => 'private-owner']);
        $this->get('/client/logout')->assertRedirect('/')->assertSessionMissing('community_owner_id');
    }

    private function discussion(array $attributes = []): CommunityPost
    {
        return CommunityPost::create($attributes + ['title' => 'Welcome', 'topic' => 'general', 'body' => 'A friendly discussion', 'author_name' => 'Member']);
    }
}
