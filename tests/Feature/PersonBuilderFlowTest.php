<?php

namespace Tests\Feature;

use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonBuilderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function basePayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'gender' => 'unknown',
            'life_status' => 'unknown',
            'birth_date_precision' => 'unknown',
            'death_date_precision' => 'unknown',
            'summary_note' => 'Тестовая персона',
        ], $overrides);
    }

    public function test_can_add_first_person_to_tree(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $user->id, 'title' => 'Test Tree']);

        $response = $this->actingAs($user)
            ->postJson('/trees/'.$tree->id.'/people', $this->basePayload());

        $response
            ->assertCreated()
            ->assertJsonPath('person.name', 'Иванов Иван')
            ->assertJsonPath('message', 'Персона добавлена.');

        $this->assertDatabaseCount('people', 1);
        $this->assertDatabaseHas('people', [
            'tree_id' => $tree->id,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
        ]);
    }

    public function test_can_add_second_person_to_tree(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $user->id, 'title' => 'Test Tree']);

        $this->actingAs($user)->postJson('/trees/'.$tree->id.'/people', $this->basePayload());
        $this->actingAs($user)->postJson('/trees/'.$tree->id.'/people', $this->basePayload([
            'first_name' => 'Мария',
            'last_name' => 'Иванова',
        ]))->assertCreated()->assertJsonPath('person.name', 'Иванова Мария');

        $this->assertDatabaseCount('people', 2);
    }

    public function test_validation_error_when_first_name_is_empty(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $user->id, 'title' => 'Test Tree']);

        $this->actingAs($user)
            ->postJson('/trees/'.$tree->id.'/people', $this->basePayload(['first_name' => '']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('first_name');
    }
}
