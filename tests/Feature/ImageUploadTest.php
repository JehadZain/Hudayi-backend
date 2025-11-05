<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_image_can_be_uploaded_and_compressed()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/api/upload-image', [
            'image' => $file,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message',
            'image_name',
            'compressed_image_name',
        ]);

        $jsonResponse = $response->json();

        Storage::disk('public')->assertExists('images/'.$jsonResponse['image_name']);
        Storage::disk('public')->assertExists('images/'.$jsonResponse['compressed_image_name']);
    }
}
