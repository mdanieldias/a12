<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a listagem de clientes.
     */
    public function test_can_list_customers()
    {
        // Cria 10 clientes para testar a listagem
        Customer::factory()->count(10)->create();

        $response = $this->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['data' => [
                    '*' => ['id', 'name', 'email', 'cpf', 'cellphone', 'city']
                ]]
            ]);
    }

    /**
     * Testa a criação de um cliente.
     */
    public function test_can_create_customer()
    {
        $data = [
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'cpf' => '12345678901345',
            'celular' => '119876543214567',
            'cidade' => 'São Paulo',
            'uf' => 'SP'
        ];

        $response = $this->postJson('/api/v1/customers', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'cpf',
                    'cellphone',
                    'city'
                ]
            ]);

        $this->assertDatabaseHas('customers', ['email' => 'joao@example.com']);
    }

    /**
     * Testa a visualização de um cliente específico.
     */
    public function test_can_show_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email', 'cpf', 'cellphone', 'city']
            ]);
    }

    /**
     * Testa a atualização de um cliente.
     */
    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();
        $city = City::factory()->create();

        $data = [
            'nome' => 'Carlos Souza',
            'email' => 'carlos@example.com',
            'cpf' => '98765432100765',
            'celular' => '119999999999876',
            'cidade' => $city->nome,
            'uf' => $city->uf
        ];

        $response = $this->putJson("/api/v1/customers/{$customer->id}", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'carlos@example.com']);

        $this->assertDatabaseHas('customers', ['email' => 'carlos@example.com']);
    }

    /**
     * Testa a exclusão de um cliente.
     */
    public function test_can_delete_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Cliente excluído com sucesso']);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    /**
     * Testa a consulta de clientes por cidade.
     */
    public function test_can_get_customers_by_city()
    {
        $city = City::factory()->create(['nome' => 'São Paulo']);
        Customer::factory()->create(['city_id' => $city->id]);

        // Faz a requisição à API para obter os clientes da cidade
        $response = $this->getJson('/api/v1/customers/pesquisar?cidade=São Paulo');

        // Verifica se a resposta tem sucesso e possui a chave 'data'
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'data']);
    }

    /**
     * Testa a consulta de clientes não encontrados por cidade.
     */
    public function test_no_customers_found_for_city()
    {
        // Cria uma cidade sem clientes
        $city = City::factory()->create(['nome' => 'Rio de Janeiro']);

        // Faz a requisição à API para obter os clientes da cidade
        $response = $this->getJson('/api/v1/customers/pesquisar?cidade=Rio de Janeiro');

        // Verifica se a resposta tem sucesso e possui a chave 'data'
        $response->assertStatus(200);

        // Verifica se a estrutura da resposta contém uma chave 'success' e uma chave 'data'
        $response->assertJsonStructure(['success', 'data']);

        // Verifica se a resposta contém uma chave 'data' vazia ou uma estrutura que indica nenhum resultado encontrado
        $response->assertJson([
            'success' => false,
            'data' => []
        ]);
    }
}
