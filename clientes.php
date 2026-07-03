<?php
require_once 'dados.php';
$titulo = 'Clientes Cadastrados';
$breadcrumb = [
    ['nome' => 'Empresa', 'url' => 'empresa.php', 'ativo' => false],
    ['nome' => 'Clientes', 'url' => '#', 'ativo' => true],
];
require_once 'layout.php';

$total_clientes = count($clientes);
$total_compras = array_sum(array_column($clientes, 'total_compras'));
$total_valor = array_sum(array_column($clientes, 'valor_total'));
?>

<!-- Cards de resumo -->
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="card bg-primary text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-users fa-3x opacity-75"></i>
        <div>
          <h3 class="fw-bold mb-0"><?= $total_clientes ?></h3>
          <span>Clientes Cadastrados</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card bg-success text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-shopping-bag fa-3x opacity-75"></i>
        <div>
          <h3 class="fw-bold mb-0"><?= $total_compras ?></h3>
          <span>Total de Compras</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card bg-warning text-white shadow-sm">
      <div class="card-body d-flex align-items-center gap-3">
        <i class="fas fa-dollar-sign fa-3x opacity-75"></i>
        <div>
          <h3 class="fw-bold mb-0">R$ <?= number_format($total_valor, 2, ',', '.') ?></h3>
          <span>Volume Total</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tabela de clientes -->
<div class="card shadow-sm">
  <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Lista de Clientes</h5>
    <span class="badge bg-light text-dark"><?= $total_clientes ?> registros</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Cadastro</th>
            <th class="text-center">Compras</th>
            <th class="text-end">Total Gasto</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clientes as $c): ?>
          <tr>
            <td><span class="badge bg-secondary"><?= $c['id'] ?></span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;min-width:36px;font-size:.8rem">
                  <?= strtoupper(substr($c['nome'], 0, 1)) ?>
                </div>
                <span class="fw-semibold"><?= htmlspecialchars($c['nome']) ?></span>
              </div>
            </td>
            <td><small><?= htmlspecialchars($c['cpf']) ?></small></td>
            <td><a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="text-decoration-none"><?= htmlspecialchars($c['email']) ?></a></td>
            <td><?= htmlspecialchars($c['telefone']) ?></td>
            <td><small><?= htmlspecialchars($c['endereco']) ?></small></td>
            <td><small><?= date('d/m/Y', strtotime($c['data_cadastro'])) ?></small></td>
            <td class="text-center">
              <span class="badge bg-info"><?= $c['total_compras'] ?></span>
            </td>
            <td class="text-end fw-bold text-success">
              R$ <?= number_format($c['valor_total'], 2, ',', '.') ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot class="table-secondary fw-bold">
          <tr>
            <td colspan="7" class="text-end">Totais:</td>
            <td class="text-center"><span class="badge bg-info"><?= $total_compras ?></span></td>
            <td class="text-end text-success">R$ <?= number_format($total_valor, 2, ',', '.') ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<!-- Cards individuais (mobile-friendly) -->
<div class="row g-3 mt-2 d-md-none">
  <?php foreach ($clientes as $c): ?>
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-2">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;font-size:.9rem">
            <?= strtoupper(substr($c['nome'], 0, 1)) ?>
          </div>
          <div>
            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($c['nome']) ?></h6>
            <small class="text-muted"><?= htmlspecialchars($c['cpf']) ?></small>
          </div>
        </div>
        <ul class="list-unstyled mb-0 small">
          <li><i class="fas fa-envelope text-muted me-2"></i><?= htmlspecialchars($c['email']) ?></li>
          <li><i class="fas fa-phone text-muted me-2"></i><?= htmlspecialchars($c['telefone']) ?></li>
          <li><i class="fas fa-map-marker-alt text-muted me-2"></i><?= htmlspecialchars($c['endereco']) ?></li>
          <li><i class="fas fa-shopping-bag text-muted me-2"></i><?= $c['total_compras'] ?> compras — <strong class="text-success">R$ <?= number_format($c['valor_total'], 2, ',', '.') ?></strong></li>
        </ul>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="mt-3">
  <a href="empresa.php" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Voltar à Empresa
  </a>
</div>

<?php require_once 'layout_footer.php'; ?>
