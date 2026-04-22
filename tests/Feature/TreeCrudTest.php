<?php

namespace Tests\Feature;

use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreeCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_delete_tree(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user)
            ->post('/trees', ['title' => 'Моя семья', 'description' => 'Описание'])
            ->assertRedirect();

        $tree = Tree::first();
        $this->assertNotNull($tree);

        $this->actingAs($user)->delete('/trees/'.$tree->id)->assertRedirect('/trees');
    }

    public function test_idor_protection_for_tree_access(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $owner->id, 'title' => 'Private']);

        $this->actingAs($other)->get('/trees/'.$tree->id)->assertForbidden();
    }
}
