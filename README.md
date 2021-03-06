# Projeto Editora Virtual

## Introdução

Este projeto serve de base para o aprendizado de algumas funcionalidades do framework Laravel.

## Passos

### 1. Revisão da versão do PHP e módulos instalados

```bash
php -v # mostra a versão do PHP
php -m # lista os módulos instalados no PHP
```

```bash
PHP >= 7.1.3
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
Ctype PHP Extension
JSON PHP Extension
php-zip
php-xml
BCMath PHP Extension
```

### 2. Instalação do Laravel através do Composer

```bash
composer global require laravel/installer
```

### 3. Criando o Projeto

```bash
laravel new 'cadastro-assinantes'
# ou
composer create-project laravel/laravel editora-virtual --prefer-dist
```

### 4. Testando o projeto criado e visualizando a página inicial

```bash
php artisan serve
# accessar http://localhost:8000 no navegador
```

### 5. Configuração da base de dados MySQL

Fazer ajustes no arquivo `.env`.

### 6. Criação da model e migration

```bash
php artisan make:model Assinante -m
```

### 7. Adicionar campos necessários na migration e rodar a migration

```bash
php artisan migrate
```

### 8. Criação do controller

```bash
php artisan make:controller Assinante --resource --model=Assinante
```
### 9. Adicionar e testar a rota para o controller

```php
<?php

// routes/web.php
Route::resource('assinante', 'AssinanteController');
```

### 10. Criação de uma factory para a model

```bash
php artisan make:factory AssinanteFactory -m=Assinante
```
Dica colocar $faker = \Faker\Factory::create('pt_BR'); 
** traz alguns métodos e nomes em portugues
métodos importantes da nossa língua
```php
$faker->cpf
$faker->cnpj
$faker->stateAbbr
```

É necessário mudar a chamada da Factory 
usando o pt_BR
```php
$factory->define(App\Assinante::class, function () use ($faker) {
```
sem o uso do faker brasileiro
```php
$factory->define(App\User::class, function (Faker $faker) {    
```

### 11. Criação dos mutators e acessors (métodos getFooAttributes e setFooAttributes)

Estes métodos são necessários para converter o formato dos campos que vieram da requisição HTTP para o formato esperado pelo banco de dados e vice-versa.

```php
    public function getDataNascimentoAttribute($value)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $value);
        $dateString = $date->format('d/m/Y');
        return $dateString;
    }

    public function setDataNascimentoAttribute($value)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $value);
        $dateString = $date->format('Y-m-d');

        $this->attributes['data_nascimento'] = $dateString;
    }
```

### 12. Testar a factory no tinker

```bash
php artisan tinker
```

```bash
App\Assinante::make(1); # cria e exibe uma model na memória
App\Assinante::create(1); # cria e persiste uma model na base de dados
```
  
### 13. Criação dos seeds para população dos dados na base de dados. Não se esqueça de atualizar o arquivo DatabaseSeeder.php

```bash
php artisan make:seeder AssinantesTableSeeder
```

### 14. Criação do formulário de listagem

Construir um tabela com os dados criados pela factory model e adicionar botões de ação como criar, visualizar, editar e remover.

```html
<table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assinantes as $assinante)
            <tr>
                <td>{{ $assinante->id }}</td>
                <td>{{ $assinante->nome }}</td>
                <td>{{ $assinante->email }}</td>
                <td>
                    <a href="{{ route('assinantes.show', ['id' => $assinante->id]) }}" class="btn btn-outline-primary btn-sm">visualizar</a>
                    <a href="{{ route('assinantes.edit', ['id' => $assinante->id]) }}" class="btn btn-outline-primary btn-sm">editar</a>
                    <form action="{{ route('assinantes.destroy', ['id' => $assinante->id]) }}" method="POST" style="display: inline">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm" onclick="javascript: return confirm('Você deseja realmente apagar este assinante?')">remover</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

```

### 15. Adicionar estilos do Bootstrap na listagem

Utilizar classes do Bootstrap para o menu de navegação, botões e tabela.
  
### 16. Adicionar paginação na listagem

Adicionar o método `paginate(10)` na consulta e no template incluir `{{ $assinantes->links() }}` para adicionar a marcação necessária para o paginador.

### 17. Criação de um Repository

Pensar nas listas, combos, radios que o formulário terá. Foi criado um objeto para conter todos os estados, tipos de logradouro, interesses, de forma a simplificar o blade.

### 18. Criação do formulário de 'Inserção' com os tipos corretos de cada campo

Utilizar na `action` do formulário o helper `route()` do Laravel e como argumento o nome da rota `assinantes.store`.

Vale lembrar de utilizar o atributo `novalidate` do HTML para desativar a validação de formulário do navegador.

Lembrar de adicionar no formulário as diretivas `@csrf`.

```html
<form action="{{ route('assinantes.store') }}" method="POST" novalidate>
    @csrf
```

Utilizar as classes do Bootstrap para organizar os elementos do formulário na tela de uma forma mais agradável para o usuário.

```html
<div class="form-group row">
    <label for="nome" class="col-4 col-form-label">Nome</label>
    <div class="col-7">
        <input type="text" name="nome" id="nome" class="form-control" maxlength="50" value="{{ old('nome', $assinante->nome ?? '') }}">
    </div>
</div>
```

### 19. Criar validação de campos do formulário

Recomenda-se utilizar um objeto separado para fazer a validação. Isso permite a reutilização da regra de validação e torna o controller mais enxuto.

```bash
php artisan make:request StoreAssinanteRequest
```

A validação é incluída na classe `StoreAssinanteRequest` no método `rules()`.

Nesta validação foram colocados todas as regras a serem consideradas como `unique`, `max`, `array`, `required` entre outros.
> Configurar os Rules específicos para os campos em que o padrão nao é suportado
```sh
php artisan make:rule Cep
```
dica prestar atenção nas aspas duplas
     'codigo'    => "required|max:3|unique:revistas,codigo,$id,id|max:3|min:3"
     
### 20. Criar uma versão traduzida para o idioma `pt-br` das mensagens de validação

Criar arquivo em *resources/lang/pt-br/validation.php* e realizar as traduções.

### 21. Adicionar mensagens de feedback na página de 'Inserção'

Realizar uma verificação para checar a existência de erros e exibir cada um dos erros em uma lista.

```html
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### 22. Validar o fluxo de funcionamento da listagem e da página de 'Inserção'

Testar mensagens de erros, mensagens de retorno e redirecionamentos.
  
### 23. Criação do formulário de 'Edição'

Observar como os campos vêm da base de dados e como eles devem ser colocados no HTML.

### 24. Criar o validador do formulário de 'Edição'

Semelhante ao formulário de criação o formulário de edição pode ter algumas diferenças tais como: Permitir que o campo email já esteja base de dados "desde que seja do mesmo assinante"

### 25. Revisar mensagens de erros para o feedback

Podem ser diferentes, "dica utilize a mesma seção do formulário de inserção assim você nao precisa tratar os erros na blade

### 26. Criação do formulário de 'visualização'

Apenas mostre os campos no sistema, não permita nenhuma alteração neles.

Não há necessidade do formulário ser exatamente igual ao de criação/edição, mostre os campos checkbox, listas, option, rádio, no formato texto simplificando que lê a informação.

Concatene os campos que podem ser exibidos de uma só vez tais como tipo de logradouro + logradouro + numero

Adicionar um botão de voltar

Atentar-se para que o formulário não deve conter uma action "valendo"

Exibir os campos input com o atributo de readonly ou disabled.

### 27. Remoção do assinante
O Laravel trabalha o método destroy um pouco diferente método editar Update ou Store, é necessário dizer ao Láravel que queremos remover o registros. Para isso colocamos a diretiva `@method('DELETE')`
dentro de um formulário específico, isto informa que ao controller que queremos acessar o método destroy. Vale a dica de confirmar a exclusão, pois na maioria dos casos não tem como recuperar o registro.


### 28. Extrair mensagens e menus para partials

Separar os formulários em pequenos pedaços com o objetivo de reaproveitá-los, por exemplo
layout.blade.php -> principal contendo os estilos e javascript
form.blade.php -> somente formulario - assim ele servirá para o edição e criação
exemplo do fomulário create

```html
@extends('layouts.app') <!-- contém o styles+css+javascript -->

@section('content')  <!--  corpo do html -->
    <h1 class="h2 mb-2">Novo Assinante</h1>

    @include('assinante.error') <!-- mensagens de feedback de erros -->

    <form action="{{ route('assinantes.store') }}" method="POST" novalidate>
        @csrf
        @include('assinante.form')  <!-- formulário com os input type, rádios, checkbox, etc -->
        <div class="form-action">
            <input type="submit" value="Cadastrar" class="btn btn-lg btn-primary">
        </div>
    </form>
@endsection
```

### 29. Adicionar máscaras aos campos

Máscaras são importantes pois auxiliam a visualização da informação que está no campo e ajuda o usuário no seu preenchimento.

### 30. PlaceHolder
Coloque PlaceHolder onde o usuário pode ter dúvidas de como preencher o campo, por ex: cep 02435-090, cpf 141.547.141-58

### 31. Helpers
O Helpers são pequenas mensagens que auxiliam o usuário de como preencher específicas, por ex: "preencher no mínimo 3 interesses".

### 32. Validação da navegação completa
Valide cada para do sistema, teste unitário
Valide todo o sistema, teste completo.
Peça a outra pessoa para testar você se surpreenderá com que os usuários conseguem fazer.

### 33. Criação arquivo de Ceps
Seguir o mesmo modelos dos assinantes e revistas, montar o factory

### 34. Jquery preenchimento automático do endereço, através do CEP
Para que se atribua um conteudo a um campo do formulário é necessiario criar um ID para este campo, com isso conseguimos buscar a informação do seu conteudo *value*
ex:
```javascript
 var cep = $('#cep').val();
``` 
Para atribuir o conteúdo ao Html usamos
```javascript
$('#bairro').val(response.data.bairro);
``` 


### 35. Desenvolvimento do Botão de manutenção para enviar emails para o usuário informando que o sistema estará em manutenção
Foi criada uma rota específica
``` 
Route::get('enviar-email/{id}', 'MailerController@emailManutencao')->name('email.manutencao');
``` 
MailerController -> Controller que irá gerenciar o envio do email
emailManutencao -> Nome do método que irá receber a request
email.manutencao -> Nome associado a esta rota (utilizada no blade de assinantes)
enviar-email -> Nome da rota
{id} - informação que será passada para o método emailManutencao

### 36. Criação da classe ManutencaoMail usada para enviar o email
php artisan make:mail ManutencaoMail --markdown=ManutencaoMail

### 37. Criação de comandos para enviar email de manutênção para um determinado assinante
```sh
php artisan make:command EmailManutencaoCommand --command=email:manutencao
``` 
> EmailManutencaoCommand Nome da Classe que ira extender command.

> email.mannutencao - é a assinatura do commando 

##### exemplo do commando final - php artisan
```sh
php artisan email:manutencao teobaldo77@example.net --urgente
```
--urgente Tratado como parâmetro opcional, 
    
### 38 - Testes

* Os tipos de testes são:
  * **testes unitários**
    * São testes feitos de **forma isolada** que garantem o funcionamentos dos métodos e classes.
  * **testes de integração**
    * São testes que garantem o funcionamento correto da **comunicação** entre os componentes do sistema.
  * **testes funcionais**
    * são testes que garantem o funcionamento correto de uma **funcionalidade da aplicação**.
    * Por exemplo:
        * inclusão de um cliente no cadastro
        * testar um entrypoint de uma API
        * testar o envio de um e-mail
* As **classes de testes** devem terminar com o sufixo `Test`, obrigatoriamente.
* Os **métodos de testes** devem ser obrigatoriamente públicos e começarem com o prefixo `test`. Por exemplo, `testApiSuccess()`.
* A ferramenta mais utilizada para a **execução de testes** em PHP é o PHPUnit.
* O PHPUnit vem por padrão na maioria dos frameworks como Laravel e Symfony.
* Para criar um classe para **testes unitários** no Laravel utiliza-se o comando `php artisan make:test MyUnitTest --unit`.
* Para criar um classe para **testes funcionais** no Laravel utiliza-se o comando `php artisan make:test MyFunctionalTest`.
 * Para executar os testes utilizando o PHPUnit utiliza-se o comando `vendor/bin/phpunit`.
* O exemplo de uma execução de testes no Laravel com o PHPUnit:
```sh
PHPUnit 7.5.7 by Sebastian Bergmann and contributors.

...                                                                 3 / 3 (100%)

Time: 239 ms, Memory: 16.00 MB

OK (3 tests, 4 assertions)
```
* No exemplo foram feitos 3 testes com 4 assertions.

### 39 - Model Assinatura
Nesta model vamos abordar alguns conceitos novos:
###### Repository:
Este classe é responsável por fazer consultas ao bancos de dados e retornar os objetos ao controller, desta forma o controller fica "enxuto".
ex RevistasRepository
```php
<?php

namespace App\Repository;
use App\Revista;

class RevistasRepository {

    public function getArrayComTodasRevistas()
    {
        return Revista::select('id','codigo','titulo','valor')->get();
    }

    public function getListagemPaginate()
    {
        return Revista::orderBy('id', 'DESC')->paginate(10);
    }
}
``` 

### 40 - Injeção de depêndencia por construtor
#### Vantagens
**As dependências da classe ficam expostas:** as dependências da classe são mais óbvias se comparadas ao método de injeção por campo ou método, no qual as classes ficam próximas a uma caixa preta. 
**Mock é mais fácil e confiável:** comparado ao injeção por campo, é mais fácil mockar as dependências em um teste unitário

#### Desvantagens
**Aumenta a dificuldade de mockar dependências:** 
você precisa usar frameworks de reflection para conseguir injetar as dependências no teste unitário;
**As dependências da classe ficam ocultas:** 
isto cria uma tendência dos desenvolvedores adicionarem mais dependências para a classe sem se preocupar com as consequências, que são: aumento do tamanho da própria classe e aumento da complexidade da mesma;
**Ferramentas de análise de código vão te ajudar mais:** Ferramentas de análise de código, como o Sonar, podem te avisar sobre uma classe que está ficando muito complexa
**Código independente: com menos anotações de injeção no seu código**, ele fica menos dependente do framework de injeção.
**As dependências podem ser imutáveis**: a imutabilidade é uma qualidade bem-vinda.
Fonte: [CWI Software](https://medium.com/cwi-software/os-benef%C3%ADcios-de-usar-inje%C3%A7%C3%A3o-por-construtor-8cd442884adc)
 
### 41 - Relacionamentos no Laravel
O Laravel utiliza o relacionamento de forma bem "mágica", bastando dizer a model como é feito o relacionamento com as outras models.
HasOne - Relacionamento direto One to One 
```php
class Assinatura extends Model
{
    protected $guarded = [];

    public function revista()
    {
        return $this->hasOne('App\Revista', 'id');
    }
}
```

HasMany - Relacionamento de One para Muito, um assinante pode ter muitas assinaturas.
```php
class Assinante extends Model
{
    protected $guarded = [];

    public function assinaturas()
    {
        return $this->hasMany('App\Assinatura');
    }
}    
```
BelongsTo - Relacionamento "invertido" pertence a algúem.
```php
class Assinatura extends Model
{
    protected $guarded = [];

    public function assinante()
    {
        return $this->belongsTo('App\Assinante', 'assinante_id', 'id');
    }
}
```
### 42 - Autenticação no Laravel

### 43 - Tratamento multilinguas email Autenticação, verificação de senha

### 44 - Laravel-mix
pré-requisitos 
##### Recomenda-se instalar o ultimo pacote do NPM e NodeJs

arquivo basico webpack.mix.js
```php
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');
```
neste exemplo o mix vai `copiar` o arquivo app.js para a pasta public/js, modificando ou não o seu conteúdo.
As modificações podem ser `minificar` o arquivo

`.sass` -> o arquivo `app.scss` será convertido para app.css utilizando o conceito do Sass

Para o site **Editora Virtual** vamos criar um arquivo `js` e `css` específicos.
Instalação do Inputmask via npm
```sh
npm install inputmask --save
```
Instalação do autocomplete via npm
```sh
 npm i devbridge-autocomplete
```

### 46 Trabalhando com módulos no Javasscript
Para evitarmos que o sistema carregue um unico `.js` com todo o código javascript, utilzaremos a técnica de modularizar as chamadas.
**Vantagem**: Cada pagina carrega apenas o seu javascript, assim podemos tratar as diretivas do Jquery #id, por página, `#idvalor` pode estar na página de assinaturas e revistas e terem comportamentos diferentes.
**Desvantagem**: Talves alguns códigos e máscaras devam ser repetidos em mais de um `javascript`

no arquivo app.blade.php 
```html
<body data-module="{{ $module ?? 'no-module' }}">
```

no arquivo `app.js`
```javascript
let $   = require('jquery');
let app = {};

// let popper = require('popper.js');
// let bootstrap = require('bootstrap');

app['assinantes']  = require('./pages/assinantes');
app['assinaturas'] = require('./pages/assinaturas');
app['revistas']    = require('./pages/revistas');

$(document).ready(function() {
    var module = $('body').data('module');

    if (app[module]) {
        var currentModule = new app[module](document.body);
        currentModule.init();
    } else {
        console.log("Module '%s' could not be loaded.", module);
    }
});
```

### 47 Implantando o sistema através o Forge

* Assistir Laravel cast - Forge
* Criar a conta Laravel forge
* Conectar o forge ao repositório git que será utilizado, pode ser qualquer repositório (github)
* Entrar no console da Digital Ocean, escolhi esse pela facilidade de se obter o token
* criar a chave API, copiar o Access Token para ser usado no forge
* escolher a cloud provedora, (digital ocean no meu caso)
* colar o token, para permitir que o forge acesse a nuvem e "busque" as máquinas disponíveis.
* Escolher qual tipo de máquina deverá ser usado para o seu site.
* Esperar até o forge informar `conexion successful` na página do Servers
*Navegar até o IP gerado caso encontre "Welcome to nginx!", tudo certo, o servidor está criado o nginx está instalado e configurado.

##### Próximo passo instalar a aplicação.

Em sites details -> Apps -> Install Repository
Configurar as variáveis de ambiente - Envinroment

```php
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:o+9y9ZURZrtIvwQOuJvf2MVbrJnWwFKCkuTBrKjpgo8=
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=p4lC5uGmUsYOu0uarTzo

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

Editar as informações e email e novas chaves que seu projeto possu

*Deploy automático*
Caso queira fazer o deploy da aplicação toda vez que o github receber um push no master, você precisa configurar o webhook, webhook é uma chamada http que o github faz ao forge informando que houve um atualização em seu repositório.
