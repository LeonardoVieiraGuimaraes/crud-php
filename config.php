<?php
/**
 * Conexão com o banco.
 *
 * As credenciais vêm de variáveis de ambiente. Os valores após o `?:` são apenas
 * o padrão de uma instalação local (XAMPP) — em qualquer outro ambiente, defina
 * as variáveis e nada precisa ser alterado aqui.
 */

$host = getenv('DB_HOST') ?: 'localhost';
$port = (int) (getenv('DB_PORT') ?: 3307);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$base = getenv('DB_NAME') ?: 'cadastro';

// Faz o mysqli lançar exceção em vez de devolver false silenciosamente.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $user, $pass, $base, $port);
$conn->set_charset('utf8mb4');

/**
 * Devolve o id da query string apenas se for um inteiro positivo.
 * Qualquer outra coisa vira null, e a página trata como registro inexistente.
 */
function id_da_requisicao(): ?int
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    return $id === false ? null : $id;
}

/** Escapa texto antes de imprimir no HTML. */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
