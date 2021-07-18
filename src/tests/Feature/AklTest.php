<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class AklTest extends TestCase
{
    use DatabaseMigrations;

    protected $token;

    /**  @test  */
    public function register_new_user()
    {
        $response = $this->registerUser();

        $response->assertStatus(200);
    }

    /**  @test  */
    public function login_user_and_save_token()
    {
        $this->registerUser();
        $response = $this->loginUser();

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'token']);
    }

    /**  @test  */
    public function get_user_details()
    {
        $this->registerUser();
        $this->loginUser();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', '/api/me');
        $json = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertEquals('User Name Test', $json->user->name);
    }

    /**  @test  */
    public function create_first_item()
    {
        $this->registerUser();
        $this->loginUser();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 001',
            "description" => 'Item Description 001',
            "quantity" => 10
        ]);

        $json = json_decode($response->getContent());

        $response->assertStatus(200);
    }

    /**  @test  */
    public function list_all_items()
    {

        $this->registerUser();
        $this->loginUser();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 001',
            "description" => 'Item Description 001',
            "quantity" => 10
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 002',
            "description" => 'Item Description 002',
            "quantity" => 20
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 003',
            "description" => 'Item Description 003',
            "quantity" => 30
        ]);

        $items = Item::all();

        $response->assertStatus(200);
        $this->assertCount(3, $items);
    }

    /**  @test  */
    public function edit_item()
    {
        $this->registerUser();
        $this->loginUser();

        $insert = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 001',
            "description" => 'Item Description 001',
            "quantity" => 10
        ]);

        $json = json_decode($insert->getContent());
        $item_id = $json->data->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('PUT', '/api/items/' . $item_id, [
            "name" => 'Item Test Modified',
            "description" => 'Item Description Modified',
            "quantity" => 100
        ]);

        $response->assertStatus(200);
    }

    /**  @test  */
    public function edit_item_quantity()
    {
        $this->registerUser();
        $this->loginUser();

        $insert = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item Test 001',
            "description" => 'Item Description 001',
            "quantity" => 10
        ]);

        $json = json_decode($insert->getContent());
        $item_id = $json->data->id;

        $update = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('PUT', '/api/items/quantity/' . $item_id, [
            "quantity" => 200
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', '/api/items/' . $item_id);
        $json = json_decode($response->getContent());
        $quantity = $json->data->quantity;

        $response->assertStatus(200);
        $this->assertEquals(200, $quantity);
    }

    /**  @test  */
    public function delete_item()
    {
        $this->registerUser();
        $this->loginUser();

        $insert = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('POST', '/api/items', [
            "name" => 'Item to Delete 001',
            "description" => 'Item to Delete 001',
            "quantity" => 10
        ]);

        $json = json_decode($insert->getContent());
        $item_id = $json->data->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('DELETE', '/api/items/' . $item_id);

        $response->assertStatus(200);
    }

    /**  @test  */
    public function logout_user()
    {
        $this->registerUser();
        $this->loginUser();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', '/api/logout');
        $response->assertStatus(200);
    }

    private function registerUser()
    {
        return $this->json('POST', '/api/register', [
            'name' => 'User Name Test',
            'email' => 'user@email.com',
            'password' => '123456'
        ]);
    }

    private function loginUser()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'user@email.com',
            'password' => '123456'
        ]);

        if ($response->getStatusCode() == 200) {
            $json = json_decode($response->getContent());
            $this->token = $json->token;
        }

        return $response;
    }
}
