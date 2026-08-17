# CRUD em PHP puro

Cadastro de usuários em PHP, sem framework, com MySQL e Bootstrap 5.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)

Escrito para ensinar as quatro operações — criar, listar, atualizar e excluir — sem
nenhuma camada de abstração no meio. Quem está aprendendo vê o SQL, a conexão e o HTML
lado a lado, o que um framework esconde de propósito.

---

## O que tem aqui

| Arquivo | Responsabilidade |
| :--- | :--- |
| `index.php` | Layout e roteamento por `?page=` |
| `config.php` | Conexão com o banco e duas funções de apoio |
| `novo_usuario.php` | Formulário de cadastro |
| `listar_usuario.php` | Tabela com os registros |
| `update_usuario.php` | Formulário de edição |
| `delete_usuario.php` | Confirmação de exclusão |
| `salvar_usuario.php` | Grava, atualiza e exclui |
| `schema.sql` | Estrutura do banco |

## Como rodar

Precisa de PHP 8+ e MySQL. Com XAMPP, os padrões já servem.

```bash
mysql -u root -p < schema.sql
php -S localhost:8000
```

Abra `http://localhost:8000`.

Em outro ambiente, defina as variáveis e nada no código muda:

```bash
DB_HOST=... DB_PORT=... DB_USER=... DB_PASS=... DB_NAME=... php -S localhost:8000
```

---

## Sobre a segurança deste código

A primeira versão deste repositório fazia o que quase todo tutorial de CRUD em PHP faz —
e que quase todo tutorial faz errado. Refatorei, e as diferenças estão anotadas aqui
porque **é justamente nelas que está o aprendizado**.

### 1. SQL injection

Antes:

```php
$sql = "SELECT * FROM usuarios WHERE id =". $_REQUEST["id"];
```

O `id` vinha da URL e era colado dentro do comando. Com `?id=5 OR 1=1`, o banco recebia
`WHERE id = 5 OR 1=1` e devolvia a tabela inteira. Não precisa de ferramenta: basta
digitar na barra de endereço.

Depois:

```php
$stmt = $conn->prepare('SELECT id, nome, email, data_nascimento FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $id);
```

Com prepared statement, o SQL vai ao banco **antes** dos dados e já compilado. O que chega
depois é tratado como valor, nunca como comando — não existe entrada capaz de virar
instrução.

### 2. Senha com MD5

Antes: `md5($_POST["senha"])`.

MD5 é rápido de propósito, o que é exatamente o que não se quer em senha: uma GPU testa
bilhões de combinações por segundo, e senhas comuns já estão em tabelas prontas na
internet. Além disso, a mesma senha gera sempre o mesmo hash — dá para descobrir quem
repete senha só olhando a coluna.

Depois: `password_hash($senha, PASSWORD_DEFAULT)`.

Aplica bcrypt, é lento de propósito e gera **salt diferente a cada chamada** — duas contas
com a mesma senha ficam com hashes distintos.

### 3. XSS na listagem

Antes, o nome vindo do banco era impresso direto no HTML. Um cadastro chamado
`<script>...</script>` executava no navegador de quem abrisse a lista.

Depois, tudo passa por `htmlspecialchars()` antes de virar HTML.

### 4. Exclusão por GET

Antes, excluir era um link: `?page=salvar&acao=delete&id=7`.

Requisição GET não deve alterar estado. Um link assim é disparado por qualquer coisa que
carregue a URL — um crawler, um pré-carregamento do navegador, ou uma `<img>` escondida
numa página de terceiro. Agora a exclusão exige POST vindo do formulário.

### 5. Credencial no código

Antes, host, usuário e senha eram constantes no arquivo. Agora vêm de variáveis de
ambiente, com os padrões locais do XAMPP como fallback.

---

## O que este projeto não é

Não tem login, sessão, controle de acesso nem CSRF token. É um CRUD didático, e ampliar o
escopo tiraria dele o que o torna útil para ensinar.

Para autenticação de verdade, o exemplo é outro repositório meu:
[**gerenciamento-endereco**](https://github.com/LeonardoVieiraGuimaraes/gerenciamento-endereco)
— .NET 8 com Keycloak, SSO, verificação em duas etapas, controle por papel e testes
automatizados.

---

## Autor

**Leonardo Vieira Guimarães** — desenvolvedor full stack e Product Owner.
Mestre em Modelagem Computacional e Sistemas (UNIMONTES), doutorando em Modelagem
Matemática e Computacional (CEFET-MG).

[![Portfólio](https://img.shields.io/badge/Portf%C3%B3lio-leoproti.com.br-0A0A0A?style=flat)](https://leoproti.com.br)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-perfil-0A66C2?style=flat&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/leonardo-vieira-guimaraes/)
