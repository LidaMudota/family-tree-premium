<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_link_person_to_itself(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $user->id, 'title' => 'T']);
        $person = Person::create(['tree_id' => $tree->id, 'first_name' => 'A', 'gender' => 'unknown', 'life_status' => 'unknown', 'birth_date_precision' => 'unknown', 'death_date_precision' => 'unknown']);

        $this->actingAs($user)->post('/trees/'.$tree->id.'/relationships', [
            'person_id' => $person->id,
            'relative_id' => $person->id,
            'type' => 'father',
        ])->assertSessionHasErrors('relative_id');
    }
}
