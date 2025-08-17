<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_upload_cr_document()
    {
        $user = User::factory()->company()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/documents/upload', [
            'document' => $file,
            'type' => 'cr_document'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'filename',
                        'path',
                        'url',
                        'size',
                        'mime_type',
                        'type'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'cr_document_path' => $response->json('data.path')
        ]);
    }

    public function test_user_can_upload_vat_document()
    {
        $user = User::factory()->company()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $file = UploadedFile::fake()->create('vat.pdf', 100, 'application/pdf');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/documents/upload', [
            'document' => $file,
            'type' => 'vat_document'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'filename',
                        'path',
                        'url',
                        'size',
                        'mime_type',
                        'type'
                    ]
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'vat_document_path' => $response->json('data.path')
        ]);
    }

    public function test_upload_rejects_invalid_file_types()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $file = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/documents/upload', [
            'document' => $file,
            'type' => 'cr_document'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['document']);
    }

    public function test_upload_rejects_large_files()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $file = UploadedFile::fake()->create('document.pdf', 6000, 'application/pdf');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/documents/upload', [
            'document' => $file,
            'type' => 'cr_document'
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['document']);
    }

    public function test_user_can_delete_document()
    {
        $user = User::factory()->company()->create([
            'cr_document_path' => 'documents/test.pdf'
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/documents/delete', [
            'type' => 'cr_document'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Document deleted successfully'
                ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'cr_document_path' => null
        ]);
    }

    public function test_user_can_get_document_info()
    {
        $user = User::factory()->company()->create([
            'cr_document_path' => 'documents/test.pdf'
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/documents/info?type=cr_document');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'type',
                        'filename',
                        'url',
                        'size',
                        'exists',
                        'uploaded_at'
                    ]
                ]);
    }

    public function test_unauthorized_user_cannot_upload_document()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/documents/upload', [
            'document' => $file,
            'type' => 'cr_document'
        ]);

        $response->assertStatus(401);
    }
}
