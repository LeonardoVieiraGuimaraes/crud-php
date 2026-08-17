<?php
/**
 * Grava, atualiza e exclui usuários.
 *
 * Toda consulta usa prepared statement: o SQL vai para o banco separado dos
 * dados, então nada que o usuário digitar é interpretado como comando.
 */

/** Mostra um aviso e devolve o usuário para a listagem. */
function redireciona(string $mensagem): void
{
    $texto = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');
    echo "<script>alert('{$texto}'); location.href = '?page=listar';</script>";
}

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

switch ($acao) {
    case 'create':
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';

        if ($nome === '' || $email === '' || $senha === '') {
            redireciona('Preencha nome, e-mail e senha.');
            break;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redireciona('E-mail inválido.');
            break;
        }

        // password_hash aplica bcrypt com salt próprio a cada senha.
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            'INSERT INTO usuarios (nome, email, senha, data_nascimento) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('ssss', $nome, $email, $hash, $data_nascimento);
        $ok = $stmt->execute();
        $stmt->close();

        redireciona($ok ? 'Cadastro realizado com sucesso' : 'Cadastro não realizado');
        break;

    case 'update':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $data_nascimento = $_POST['data_nascimento'] ?? '';

        if (!$id || $nome === '' || $email === '') {
            redireciona('Dados incompletos.');
            break;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            redireciona('E-mail inválido.');
            break;
        }

        // Senha em branco significa "manter a atual" — por isso são duas consultas.
        if ($senha !== '') {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                'UPDATE usuarios SET nome = ?, email = ?, senha = ?, data_nascimento = ? WHERE id = ?'
            );
            $stmt->bind_param('ssssi', $nome, $email, $hash, $data_nascimento, $id);
        } else {
            $stmt = $conn->prepare(
                'UPDATE usuarios SET nome = ?, email = ?, data_nascimento = ? WHERE id = ?'
            );
            $stmt->bind_param('sssi', $nome, $email, $data_nascimento, $id);
        }

        $ok = $stmt->execute();
        $stmt->close();

        redireciona($ok ? 'Cadastro atualizado com sucesso' : 'Cadastro não atualizado');
        break;

    case 'delete':
        // Exclusão só por POST: link e GET não podem apagar registro.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redireciona('Exclusão precisa ser confirmada no formulário.');
            break;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        if (!$id) {
            redireciona('Registro inválido.');
            break;
        }

        $stmt = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        redireciona($ok ? 'Cadastro excluído com sucesso' : 'Cadastro não excluído');
        break;

    default:
        redireciona('Ação desconhecida.');
}
