<?php
require_once 'dados.php';

$categoria_filtro = $_GET['categoria'] ?? '';
$busca = $_GET['busca'] ?? '';

$lista = getProdutos($categoria_filtro, $busca);
$categorias_unicas = getCategorias();

$titulo = 'Produtos';
$breadcrumb = [['nome' => 'Produtos', 'url' => '#', 'ativo' => true]];

require_once 'layout.php';
?>

<!-- Filtros -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
      <div class="col-md-5">
        <label class="form-label fw-semibold"><i class="fas fa-search me-1"></i>Buscar produto</label>
        <input type="text" name="busca" class="form-control" placeholder="Nome ou descrição..." value="<?= htmlspecialchars($busca) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold"><i class="fas fa-filter me-1"></i>Categoria</label>
        <select name="categoria" class="form-select">
          <option value="">Todas as categorias</option>
          <?php foreach ($categorias_unicas as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $categoria_filtro === $cat ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-search me-1"></i>Filtrar</button>
        <a href="produtos.php" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
      </div>
    </form>
  </div>
</div>

<?php if ($categoria_filtro || $busca): ?>
<div class="alert alert-info mb-4">
  <i class="fas fa-info-circle me-2"></i>
  <?= count($lista) ?> produto(s) encontrado(s)
  <?= $categoria_filtro ? " na categoria <strong>" . htmlspecialchars($categoria_filtro) . "</strong>" : '' ?>
  <?= $busca ? " para <strong>\"" . htmlspecialchars($busca) . "\"</strong>" : '' ?>
</div>
<?php endif; ?>

<!-- Badges de categoria -->
<div class="mb-4">
  <a href="produtos.php" class="badge bg-<?= !$categoria_filtro ? 'primary' : 'secondary' ?> me-1 mb-1 text-decoration-none fs-6 p-2">Todos</a>
  <?php foreach ($categorias_unicas as $cat): ?>
  <a href="produtos.php?categoria=<?= urlencode($cat) ?>" class="badge bg-<?= $categoria_filtro === $cat ? 'primary' : 'secondary' ?> me-1 mb-1 text-decoration-none fs-6 p-2">
    <?= htmlspecialchars($cat) ?>
  </a>
  <?php endforeach; ?>
</div>

<!-- Grid de produtos -->
<?php if (empty($lista)): ?>
<div class="card shadow-sm">
  <div class="card-body text-center py-5">
    <i class="fas fa-search fa-4x text-muted mb-3"></i>
    <h4 class="text-muted">Nenhum produto encontrado</h4>
    <p class="text-muted">Tente ajustar os filtros ou <a href="produtos.php">ver todos os produtos</a>.</p>
  </div>
</div>
<?php else: ?>
<div class="row g-4">
  <?php foreach ($lista as $p): ?>
  <div class="col-sm-6 col-lg-4 col-xl-3">
    <div class="card h-100 shadow-sm produto-card">
      <img src="<?= htmlspecialchars($p['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nome']) ?>" style="height:200px;object-fit:cover">
      <div class="card-body d-flex flex-column">
        <div class="mb-2">
          <span class="badge bg-secondary" style="font-size:.7rem"><?= htmlspecialchars($p['categoria']) ?></span>
          <?php if ($p['estoque'] < 20): ?>
          <span class="badge bg-danger">Últimas unidades</span>
          <?php endif; ?>
        </div>
        <h5 class="card-title fw-bold"><?= htmlspecialchars($p['nome']) ?></h5>
        <p class="card-text text-muted small flex-fill"><?= htmlspecialchars(substr($p['descricao'], 0, 80)) ?>...</p>
        <div class="d-flex align-items-center justify-content-between mt-2">
          <span class="text-success fw-bold fs-5">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
          <small class="text-muted"><i class="fas fa-box me-1"></i><?= $p['estoque'] ?> un.</small>
        </div>
        <a href="produto.php?id=<?= $p['id'] ?>" class="btn btn-primary mt-3">
          <i class="fas fa-eye me-1"></i>Ver Detalhes
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once 'layout_footer.php'; ?>
