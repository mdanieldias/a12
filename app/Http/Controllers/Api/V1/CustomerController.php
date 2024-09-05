<?php

namespace App\Http\Controllers\Api\V1;

use Log;
use App\Models\City;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Api\V1\CustomerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    /**
     * Retorna uma lista paginada de clientes.
     *
     * Este método recupera uma lista paginada de clientes do banco de dados
     * e a retorna como uma resposta JSON ou uma resposta vazia.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = Customer::with('city')->paginate(10);

        return response()->json(
            [
                'success' => true,
                'data' => $customers
            ],
            200
        );
    }

    /**
     * Cria novo cliente com os dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\StoreCustomerRequest  $request O objeto de requisição contendo os dados do cliente a ser criado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCustomerRequest $request)
    {
        DB::beginTransaction();

        try {
            // Primeiro, verifica se a cidade já existe
            $city = City::firstOrCreate([
                'nome' => $request->input('cidade'),
                'uf' => $request->input('uf'),
            ]);

            // Cria o cliente associado à cidade
            $customer = Customer::create([
                'name' => $request->input('nome'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'cellphone' => $request->input('celular'),
                'city_id' => $city->id
            ]);

            DB::commit();

            return response()->json(
                [
                    'success' => true,
                    'data' => new CustomerResource($customer->load('city'))
                ],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Erro ao cadastrar cliente",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibe os detalhes de um cliente.
     *
     * Este método retorna os detalhes de um cliente específico em formato JSON.
     *
     * @param  $id O id do cliente a ser exibido
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $customer = Customer::with('city')->find($id);

        if (!$customer) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Cliente não encontrado'
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => new CustomerResource($customer)
            ],
            200
        );
    }

    /**
     * Atualiza o cliente com os dados fornecidos na requisição.
     * 
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request O objeto de requisição contendo os dados do cli a ser atualizado.
     * @param  $id O id do cliente a ser atualizado
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCustomerRequest $request, string $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Cliente não encontrado'
                ],
                204
            );
        }

        DB::beginTransaction();

        try {
            // Primeiro, verifica se a nova cidade já existe ou cria uma nova
            $city = City::firstOrCreate([
                'nome' => $request->input('cidade'),
                'uf' => $request->input('uf'),
            ]);

            // Atualiza o cliente
            $customer->update([
                'name' => $request->input('nome'),
                'email' => $request->input('email'),
                'cpf' => $request->input('cpf'),
                'cellphone' => $request->input('celular'),
                'city_id' => $city->id
            ]);

            DB::commit();

            return response()->json(
                [
                    'success' => true,
                    'data' => new CustomerResource($customer->load('city'))
                ],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Erro ao atualizar cliente",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Excluir cliente no banco de dados.
     * 
     * @param  $id O id do cliente a ser excluído
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Cliente não encontrado'
                ],
                204
            );
        }

        DB::beginTransaction();

        try {
            $customer->delete();
            DB::commit();

            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => 'Cliente excluído com sucesso'
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => "Erro ao excluir cliente",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna uma lista paginada de clientes.
     *
     * Este método recupera uma lista paginada de clientes do banco de dados ao consultar por uma cidade
     * e a retorna como uma resposta JSON ou uma resposta vazia.
     * @param  \App\Http\Requests\Request  $request O objeto de requisição contendo os dados da consulta.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCity(Request $request): JsonResponse
    {
        $cidade = $request->query('cidade');

        if (!$cidade) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Por favor, informe o nome da cidade'
                ],
                200
            );
        }

        $customers = Customer::whereHas('city', function ($query) use ($cidade) {
            $query->where('nome', 'like', '%' . $cidade . '%');
        })->with('city')->paginate(10);

        if ($customers->isEmpty()) {
            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Nenhum cliente encontrado na cidade de ' . $cidade
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => true,
                'data' => $customers
            ],
            200
        );
    }

    /**
     * Trunca a tabela customers.
     *
     * Este método trunca a tabela customers do banco de dados. Utilizado somente para realizar testes no fronte-end.
     * @return \Illuminate\Http\JsonResponse
     */
    public function truncateTable(): JsonResponse
    {
        try {
            DB::table('customers')->truncate();

            return response()->json(
                [
                    'success' => true,
                    'data' => [],
                    'message' => 'Tabela customers truncada com sucesso!'
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {

            return response()->json(
                [
                    'success' => false,
                    'data' => [],
                    'message' => 'Erro ao truncar a tabela customers.',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
