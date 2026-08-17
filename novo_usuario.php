<h1>Novo Usuário</h1>

<form action="?page=salvar" method="post">
    <input type="hidden" name="acao" value="create">
    <div class="mb-3">
        <label for="id_nome">Nome</label>
        <input type="text" name="nome" id="id_nome" class="form-control" required maxlength="120">
    </div>
    <div class="mb-3">
        <label for="id_email">E-mail</label>
        <input type="email" name="email" id="id_email" class="form-control" required maxlength="180">
    </div>
    <div class="mb-3">
        <label for="id_senha">Senha</label>
        <input type="password" name="senha" id="id_senha" class="form-control" required
               minlength="8" autocomplete="new-password">
        <div class="form-text">Mínimo de 8 caracteres. Guardada como hash bcrypt.</div>
    </div>
    <div class="mb-3">
        <label for="id_data_nascimento">Data de nascimento</label>
        <input type="date" name="data_nascimento" id="id_data_nascimento" class="form-control" required>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a class="btn btn-secondary" href="?page=listar">Cancelar</a>
    </div>
</form>
