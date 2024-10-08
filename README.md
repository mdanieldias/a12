## Sobre o Projeto

Implementar um formulário de cadastro contendo os seguintes campos: Nome, CPF, Celular, E-mail, Cidade e UF.

## Objetivo

Avaliar a capacidade do candidato de criar uma solução completa que inclua a estrutura de banco de dados, uma API para manipulação dos dados e um formulário web que consuma essa API. O teste considera além da estrutura, a aplicação dos princípios de usabilidade, acessibilidade e estética.

## Requisitos

- PHP >= 8.3
- Composer
- MySQL ou outro banco de dados compatível
- Node.js e npm (para o frontend)

## Instalação

Siga os passos abaixo para configurar e executar o projeto localmente:

### 1. Clone o Repositório

Clone o repositório do projeto para sua máquina local:

```bash
git clone https://github.com/mdanieldias/a12.git
cd nome-do-projeto
```

### 2. Instale as Dependências PHP

```bash
composer install
```

### 3. Configure o Ambiente

```bash
cp .env.example .env
```
Abra o arquivo .env e configure as seguintes variáveis de ambiente de acordo com seu ambiente:

```bash
APP_NAME="A12 - Família dos Devotos"
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost:8000
APP_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= Nome do banco de dados
DB_USERNAME= Nome de usuário do banco de dados
DB_PASSWORD= Senha do banco de dados
```

Ainda no arquivo .env, adicione a seguinte variável de amabiente. Ela será responsável por carregar a url base do endpoint da nossa API;

```bash
VITE_API_BASE_URL ="${APP_URL}/api/v1/" 
```

### 4. Gere a Chave de Aplicação
Gere uma chave de aplicação para o Laravel:

```bash
php artisan key:generate
```

### 5. Execute as Migrações
Primeiramente, certifique-se de ter criado o banco de dados. Depois, execute as migrações para criar as tabelas do banco de dados.

```bash
php artisan migrate
```

### 6. Instale as Dependências de Frontend
Instale as dependências de frontend usando npm ou yarn:

```bash
npm install
# ou
yarn install
```

### 7. Compile os Assets
Compile os assets do frontend:

```bash
npm run dev
# ou
yarn dev
```

### 8. Execute o Servidor Local
Inicie o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

## Uso
Acesse o projeto no navegador em http://localhost:8000

## Testes
Nessa etapa serão realizados os teste de API e aplicação.
### Realizar o teste da API e banco de dados
```bash
php artisan test
```
### Executar E2E com Cypress em modo headless (sem interface gráfica)
```bash
npx cypress run
```
Isso executará os testes no terminal, sem abrir a interface gráfica. O Cypress rodará os testes de E2E de maneira silenciosa e exibirá os resultados no terminal.

### Executar E2E com interface gráfica do Cypress
```bash
npx cypress open
```
Isso abrirá a interface gráfica do Cypress, onde você pode selecionar os arquivos de teste e executá-los.
- Navegue até a aba "E2E Testing".
- Selecione o navegador em que deseja executar os testes (por exemplo, Chrome).
- Na lista de testes que aparece na interface, clique no arquivo **form_test.spec.js**

## Contribuição

## Licença