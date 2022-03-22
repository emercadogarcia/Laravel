<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        //$this->withoutExceptionHandling(); //muestra en detalle el error

        $response = $this->json('POST', '/api/posts', [
            'title' => 'El post de prueba'
        ]);

        $response->assertJsonStructure(['id','title','created_at', 'updated_at'])
        ->assertJson(['title' => 'El post de prueba'])
        ->assertStatus(201);   //Ok, creado un recurso

        $this->assertDatabaseHas('posts', ['title' => 'El post de prueba']);
    }

    public function test_validate_title()
    {
        $response = $this->json('POST', '/api/posts', [
            'title' => ''
        ]);

        //Sttatus  HTTP 422
        $response->assertstatus(422)
            ->AssertJsonValidationErrors('title');

    }

    public function test_show()
    {
        //$post = factory(Post::class)->create(); //laravel 6
        $post = Post::factory()->create();

        $response = $this->json('GET', "/api/posts/$post->id"); //id=1

        $response->assertJsonStructure(['id','title','created_at', 'updated_at'])
        ->assertJson(['title' => $post->title])
        ->assertStatus(200);   //Ok
    }

    public function test_404_show()
    {
        $response = $this->json('GET', '/api/posts/1000');

        $response->assertStatus(404); //not found
    }


    public function test_update()
    {
        //$this->withoutExceptionHandling(); //muestra en detalle el error
        $post = Post::factory()->create();

        $response = $this->json('PUT', "/api/posts/$post->id", [
            'title' => 'Nuevo'
        ]);

        $response->assertJsonStructure(['id','title','created_at', 'updated_at'])
        ->assertJson(['title' => 'Nuevo'])
        ->assertStatus(200);   //Ok

        $this->assertDatabaseHas('posts', ['title' => 'Nuevo']);
    }

    public function test_delete()
    {
        //$this->withoutExceptionHandling(); //muestra en detalle el error
        $post = Post::factory()->create();

        $response = $this->json('DELETE', "/api/posts/$post->id");

        $response->assertSee(null)
            ->assertStatus(204);   //Sin contenido...

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}

