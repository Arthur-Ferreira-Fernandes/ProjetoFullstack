<?php
require_once '../auth.php';
require_once '../config.php';
requireCliente();

$db = getDB();
$stmt = $db->prepare('
    SELECT p.nome AS produto_nome, p.imagem, p.categoria,
           pc.quantidade, pc.valor_unitario,
           (pc.quantidade * pc.valor_unitario) AS subtotal,
           pc.data_pedido, pc.status
    FROM pedidos pc
    JOIN produtos p ON p.id = pc.produto_id
    WHERE pc.usuario_id = :id
    ORDER BY pc.data_pedido DESC
');
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$pedidos = $stmt->fetchAll();

$total_gasto = array_sum(array_column($pedidos, 'subtotal'));
$total_itens = array_sum(array_column($pedidos, 'quantidade'));

$titulo = 'Minhas Compras';
$breadcrumb = [
    ['nome' => 'Perfil', 'url' => '../perfil.php', 'ativo' => false],
    ['nome' => 'Minhas Compras', 'url' => '#', 'ativo' => true],
];
require_once '../layout.php';
?>

<!-- Resumo -->
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="card bg-primary text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-shopping-bag fa-3x opacity-75"></i>
        <div><h3 class="fw-bold mb-0"><?= count($pedidos) ?></h3><span>Pedidos</span></div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card bg-success text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-boxes fa-3x opacity-75"></i>
        <div><h3 class="fw-bold mb-0"><?= $total_itens ?></h3><span>Itens comprados</span></div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card bg-warning text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-dollar-sign fa-3x opacity-75"></i>
        <div><h3 class="fw-bold mb-0">R$ <?= number_format($total_gasto, 2, ',', '.') ?></h3><span>Total investido</span></div>
      </div>
    </div>
  </div>
</div>

<?php if (empty($pedidos)): ?>
<div class="card shadow-sm">
  <div class="card-body text-center py-5">
    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
    <h4 class="text-muted">Você ainda não realizou compras</h4>
    <p class="text-muted">Explore nosso catálogo e encontre o que precisa para sua obra!</p>
    <a href="../produtos.php" class="btn btn-primary mt-2"><i class="fas fa-boxes me-2"></i>Ver Produtos</a>
  </div>
</div>
<?php else: ?>
<div class="card shadow-sm">
  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Histórico de Compras</h5>
    <span class="badge bg-light text-dark"><?= count($pedidos) ?> pedido(s)</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th>Produto</th>
            <th class="text-center">Qtd.</th>
            <th class="text-end">Vlr. Unit.</th>
            <th class="text-end">Subtotal</th>
            <th class="text-center">Data</th>
            <th class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pedidos as $p): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="<?= htmlspecialchars($p['imagem']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:4px" alt="">
                <div>
                  <div class="fw-semibold"><?= htmlspecialchars($p['produto_nome']) ?></div>
                  <small class="text-muted"><?= htmlspecialchars($p['categoria']) ?></small>
                </div>
              </div>
            </td>
            <td class="text-center align-middle"><?= $p['quantidade'] ?></td>
            <td class="text-end align-middle">R$ <?= number_format($p['valor_unitario'], 2, ',', '.') ?></td>
            <td class="text-end align-middle fw-bold text-success">R$ <?= number_format($p['subtotal'], 2, ',', '.') ?></td>
            <td class="text-center align-middle"><small><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></small></td>
            <td class="text-center align-middle">
              <?php
              $badges = ['entregue' => 'success', 'em_transito' => 'info', 'processando' => 'warning', 'cancelado' => 'danger'];
              $labels = ['entregue' => 'Entregue', 'em_transito' => 'Em trânsito', 'processando' => 'Processando', 'cancelado' => 'Cancelado'];
              $cor = $badges[$p['status']] ?? 'secondary';
              $label = $labels[$p['status']] ?? ucfirst($p['status']);
              ?>
              <span class="badge bg-<?= $cor ?>"><?= $label ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot class="table-secondary fw-bold">
          <tr>
            <td colspan="3" class="text-end">Total gasto:</td>
            <td class="text-end text-success">R$ <?= number_format($total_gasto, 2, ',', '.') ?></td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="mt-3">
  <a href="../perfil.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar ao Perfil</a>
</div>

<?php require_once '../layout_footer.php'; ?>
