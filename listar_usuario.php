<h1>Lista de Usuários</h1>

<?php
/**
 * Listagem. Todo texto vindo do banco passa por e() antes de ir para o HTML,
 * senão um nome com <script> seria executado pelo navegador de quem abre a página.
 */
$res = $conn->query('SELECT id, nome, email, data_nascimento FROM usuarios ORDER BY nome');

if ($res && $res->num_rows > 0) { ?>
    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>E-MAIL</th>
            <th>DATA DE NASCIMENTO</th>
            <th class="text-center">EDITAR</th>
            <th class="text-center">EXCLUIR</th>
        </tr>
        <?php while ($row = $res->fetch_object()) { ?>
            <tr>
                <td><?= (int) $row->id ?></td>
                <td><?= e($row->nome) ?></td>
                <td><?= e($row->email) ?></td>
                <td><?= $row->data_nascimento ? date('d/m/Y', strtotime($row->data_nascimento)) : '' ?></td>
                <td class="text-center">
                    <a href="?page=update&id=<?= (int) $row->id ?>" aria-label="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </td>
                <td class="text-center">
                    <a href="?page=delete&id=<?= (int) $row->id ?>" aria-label="Excluir">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p class="alert alert-danger">Não encontrou resultado!</p>
<?php } ?>
