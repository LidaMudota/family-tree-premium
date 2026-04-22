<?php

namespace Tests\Feature;

use App\Models\Tree;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_photo_upload_is_stored_on_private_disk(): void
    {
        Storage::fake('private');
        $user = User::factory()->create(['email_verified_at' => now()]);
        $tree = Tree::create(['user_id' => $user->id, 'title' => 'T']);

        $this->actingAs($user)->post('/trees/'.$tree->id.'/people', [
            'first_name' => 'Anton', 'gender' => 'male', 'life_status' => 'alive', 'birth_date_precision' => 'unknown', 'death_date_precision' => 'unknown',
            'photo' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect();

        $this->assertDatabaseHas('people', ['tree_id' => $tree->id, 'first_name' => 'Anton']);
    }
}
