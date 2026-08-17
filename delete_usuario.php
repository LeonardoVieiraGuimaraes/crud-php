<h1>Excluir Usuário</h1>

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

<p class="alert alert-warning">Confira os dados antes de excluir. A ação não pode ser desfeita.</p>

<div class="mb-3">
    <label>Nome</label>
    <input type="text" class="form-control" disabled value="<?= e($row->nome) ?>">
</div>
<div class="mb-3">
    <label>E-mail</label>
    <input type="text" class="form-control" disabled value="<?= e($row->email) ?>">
</div>
<div class="mb-3">
    <label>Data de nascimento</label>
    <input type="date" class="form-control" disabled value="<?= e($row->data_nascimento) ?>">
</div>

<!--
    A exclusão vai por POST, não por link. Um GET que apaga registro é
    disparado por qualquer coisa que carregue a URL — inclusive um crawler
    ou uma imagem numa página de terceiro.
-->
<form action="?page=salvar" method="post"
      onsubmit="return confirm('Tem certeza que deseja excluir?');">
    <input type="hidden" name="acao" value="delete">
    <input type="hidden" name="id" value="<?= (int) $row->id ?>">
    <button type="submit" class="btn btn-danger">Excluir</button>
    <a class="btn btn-secondary" href="?page=listar">Cancelar</a>
</form>

<?php } ?>
