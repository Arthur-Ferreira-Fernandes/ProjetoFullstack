<?php
require_once __DIR__ . '/config.php';

function getProdutos(string $categoria = '', string $busca = ''): array {
    $db = getDB();
    $sql = '
        SELECT p.*, GROUP_CONCAT(pe.chave, "||", pe.valor ORDER BY pe.id SEPARATOR ";;") AS specs_raw
        FROM produtos p
        LEFT JOIN produto_especificacoes pe ON pe.produto_id = p.id
        WHERE 1=1
    ';
    $params = [];
    if ($categoria !== '') {
        $sql .= ' AND p.categoria = :categoria';
        $params[':categoria'] = $categoria;
    }
    if ($busca !== '') {
        $sql .= ' AND (p.nome LIKE :busca OR p.descricao LIKE :busca)';
        $params[':busca'] = '%' . $busca . '%';
    }
    $sql .= ' GROUP BY p.id ORDER BY p.id';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    foreach ($rows as &$row) {
        $row['especificacoes'] = [];
        if (!empty($row['specs_raw'])) {
            foreach (explode(';;', $row['specs_raw']) as $spec) {
                [$chave, $valor] = explode('||', $spec, 2);
                $row['especificacoes'][$chave] = $valor;
            }
        }
        unset($row['specs_raw']);
    }
    return $rows;
}

function getProduto(int $id): ?array {
    $db = getDB();
    $stmt = $db->prepare('
        SELECT p.*, GROUP_CONCAT(pe.chave, "||", pe.valor ORDER BY pe.id SEPARATOR ";;") AS specs_raw
        FROM produtos p
        LEFT JOIN produto_especificacoes pe ON pe.produto_id = p.id
        WHERE p.id = :id
        GROUP BY p.id
    ');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if (!$row) return null;

    $row['especificacoes'] = [];
    if (!empty($row['specs_raw'])) {
        foreach (explode(';;', $row['specs_raw']) as $spec) {
            [$chave, $valor] = explode('||', $spec, 2);
            $row['especificacoes'][$chave] = $valor;
        }
    }
    unset($row['specs_raw']);
    return $row;
}

function getProdutosRelacionados(string $categoria, int $excluirId, int $limite = 3): array {
    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM produtos WHERE categoria = :cat AND id != :id LIMIT :lim');
    $stmt->bindValue(':cat', $categoria);
    $stmt->bindValue(':id', $excluirId, PDO::PARAM_INT);
    $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCategorias(): array {
    $db = getDB();
    $stmt = $db->query('SELECT DISTINCT categoria FROM produtos ORDER BY categoria');
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getClientes(): array {
    $db = getDB();
    $stmt = $db->query('SELECT * FROM clientes ORDER BY id');
    return $stmt->fetchAll();
}

function getLocais(): array {
    $db = getDB();
    $stmt = $db->query('SELECT * FROM locais ORDER BY id');
    return $stmt->fetchAll();
}

// Variáveis globais para manter compatibilidade com o layout
$produtos = getProdutos();
$clientes = getClientes();
$locais   = getLocais();
