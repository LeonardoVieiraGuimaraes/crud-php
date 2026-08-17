<h1>Atualizar Usuário</h1>

<?php
$id = id_da_requisicao();
$row = null;

if ($id !== null) {
    $stmt = $conn->prepare('SELECT id, nome, email, data_nascimento FROM usuarios WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_object();
    $stmt->close();
}

if (!$row) { ?>
    <p class="alert alert-danger">Usuário não encontrado.</p>
    <a class="btn btn-secondary" href="?page=listar">Voltar</a>
<?php } else { ?>

<form action="?page=salvar" method="post">
    <input type="hidden" name="acao" value="update">
    <input type="hidden" name="id" value="<?= (int) $row->id ?>">

    <div class="mb-3">
        <label for="id_nome">Nome</label>
        <input type="text" name="nome" id="id_nome" class="form-control" required
               value="<?= e($row->nome) ?>">
    </div>
    <div class="mb-3">
        <label for="id_email">E-mail</label>
        <input type="email" name="email" id="id_email" class="form-control" required
               value="<?= e($row->email) ?>">
    </div>
    <div class="mb-3">
        <label for="id_senha">Senha</label>
        <input type="password" name="senha" id="id_senha" class="form-control"
               autocomplete="new-password">
        <div class="form-text">
            Deixe em branco para manter a senha atual. A senha nunca é devolvida
            para a tela — o banco guarda só o hash.
        </div>
    </div>
    <div class="mb-3">
        <label for="id_data_nascimento">Data de nascimento</label>
        <input type="date" name="data_nascimento" id="id_data_nascimento" class="form-control" required
               value="<?= e($row->data_nascimento) ?>">
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a class="btn btn-secondary" href="?page=listar">Cancelar</a>
    </div>
</form>

<?php } ?>
