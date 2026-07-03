<?php
require_once '../auth.php';
require_once '../config.php';
requireAdmin();

// Processar exclusão via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $db = getDB();
    $db->prepare('DELETE FROM produtos WHERE id = :id')->execute([':id' => intval($_POST['excluir_id'])]);
    $_SESSION['flash'] = 'Produto excluído com sucesso.';
    header('Location: index.php');
    exit;
}

$db = getDB();
$produtos = $db->query('SELECT * FROM produtos ORDER BY categoria, nome')->fetchAll();

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

$titulo = 'Gerenciar Produtos';
$breadcrumb = [
    ['nome' => 'Admin', 'url' => '#', 'ativo' => false],
    ['nome' => 'Produtos', 'url' => '#', 'ativo' => true],
];
require_once '../layout.php';
?>

<?php if ($flash): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($flash) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
  <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Lista de Produtos</h5>
    <a href="produto_form.php" class="btn btn-warning btn-sm fw-bold">
      <i class="fas fa-plus me-1"></i>Novo Produto
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Produto</th>
            <th>Categoria</th>
            <th class="text-end">Preço</th>
            <th class="text-center">Estoque</th>
            <th class="text-center">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): ?>
          <tr>
            <td class="align-middle"><span class="badge bg-secondary"><?= $p['id'] ?></span></td>
            <td class="align-middle">
              <div class="d-flex align-items-center gap-2">
                <img src="<?= htmlspecialchars($p['imagem']) ?>" style="width:44px;height:44px;object-fit:cover;border-radius:4px" alt="">
                <span class="fw-semibold"><?= htmlspecialchars($p['nome']) ?></span>
              </div>
            </td>
            <td class="align-middle"><span class="badge bg-secondary"><?= htmlspecialchars($p['categoria']) ?></span></td>
            <td class="align-middle text-end fw-bold text-success">R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td class="align-middle text-center">
              <?php if ($p['estoque'] < 20): ?>
              <span class="badge bg-danger"><?= $p['estoque'] ?></span>
              <?php else: ?>
              <span class="badge bg-success"><?= $p['estoque'] ?></span>
              <?php endif; ?>
            </td>
            <td class="align-middle text-center">
              <div class="d-flex gap-1 justify-content-center">
                <a href="../produto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar" target="_blank">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="produto_form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <form method="POST" class="d-inline" onsubmit="return confirm('Excluir o produto <?= htmlspecialchars(addslashes($p['nome'])) ?>?')">
                  <input type="hidden" name="excluir_id" value="<?= $p['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Excluir">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer text-muted">
    Total: <?= count($produtos) ?> produto(s) cadastrado(s).
  </div>
</div>

<?php require_once '../layout_footer.php'; ?>
