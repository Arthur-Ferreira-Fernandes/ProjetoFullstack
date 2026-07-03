<?php
require_once 'dados.php';

$id = intval($_GET['id'] ?? 0);
$produto = getProduto($id);

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

$relacionados = getProdutosRelacionados($produto['categoria'], $produto['id']);

$titulo = $produto['nome'];
$breadcrumb = [
    ['nome' => 'Produtos', 'url' => 'produtos.php', 'ativo' => false],
    ['nome' => $produto['nome'], 'url' => '#', 'ativo' => true],
];
require_once 'layout.php';
?>

<div class="row g-4">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <img src="<?= htmlspecialchars($produto['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto['nome']) ?>" style="height:320px;object-fit:cover">
      <div class="card-body text-center">
        <span class="badge bg-secondary fs-6 px-3"><?= htmlspecialchars($produto['categoria']) ?></span>
        <?php if ($produto['estoque'] < 20): ?>
        <span class="badge bg-danger fs-6 ms-2">Últimas unidades!</span>
        <?php elseif ($produto['estoque'] > 100): ?>
        <span class="badge bg-success fs-6 ms-2">Em estoque</span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h2 class="fw-bold mb-1"><?= htmlspecialchars($produto['nome']) ?></h2>
        <p class="text-muted mb-3"><?= htmlspecialchars($produto['descricao']) ?></p>

        <div class="border rounded p-3 mb-3 bg-light">
          <div class="preco-destaque">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
          <small class="text-muted">por unidade / medida</small>
        </div>

        <div class="d-flex gap-2 mb-4">
          <div class="flex-fill text-center border rounded p-2">
            <i class="fas fa-box text-primary fa-lg mb-1"></i>
            <div class="fw-bold"><?= $produto['estoque'] ?></div>
            <small class="text-muted">Em estoque</small>
          </div>
          <div class="flex-fill text-center border rounded p-2">
            <i class="fas fa-truck text-success fa-lg mb-1"></i>
            <div class="fw-bold">24h</div>
            <small class="text-muted">Entrega</small>
          </div>
          <div class="flex-fill text-center border rounded p-2">
            <i class="fas fa-shield-alt text-warning fa-lg mb-1"></i>
            <div class="fw-bold">Garantia</div>
            <small class="text-muted">Qualidade</small>
          </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <a href="contato.php" class="btn btn-primary btn-lg flex-fill">
            <i class="fas fa-shopping-cart me-2"></i>Pedir Orçamento
          </a>
          <a href="contato.php" class="btn btn-outline-secondary btn-lg">
            <i class="fas fa-phone"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Especificações técnicas -->
<div class="card shadow-sm mt-4">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Especificações Técnicas</h5>
  </div>
  <div class="card-body">
    <?php if (!empty($produto['especificacoes'])): ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered mb-0">
        <thead class="table-dark">
          <tr><th style="width:35%">Especificação</th><th>Valor</th></tr>
        </thead>
        <tbody>
          <?php foreach ($produto['especificacoes'] as $chave => $valor): ?>
          <tr>
            <td class="fw-semibold"><i class="fas fa-check-circle text-success me-2"></i><?= htmlspecialchars($chave) ?></td>
            <td><?= htmlspecialchars($valor) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <p class="text-muted mb-0">Nenhuma especificação cadastrada.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Informações adicionais -->
<div class="row g-4 mt-1">
  <div class="col-md-4">
    <div class="card border-primary h-100">
      <div class="card-body text-center">
        <i class="fas fa-truck fa-2x text-primary mb-2"></i>
        <h6 class="fw-bold">Entrega Disponível</h6>
        <p class="text-muted small mb-0">Entregamos em toda a região metropolitana. Consulte prazo e valor.</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-success h-100">
      <div class="card-body text-center">
        <i class="fas fa-store fa-2x text-success mb-2"></i>
        <h6 class="fw-bold">Retire na Loja</h6>
        <p class="text-muted small mb-0">Retire em uma de nossas <?= count($locais) ?> lojas.</p>
        <a href="locais.php" class="btn btn-sm btn-outline-success mt-2">Ver locais</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-warning h-100">
      <div class="card-body text-center">
        <i class="fas fa-headset fa-2x text-warning mb-2"></i>
        <h6 class="fw-bold">Dúvidas?</h6>
        <p class="text-muted small mb-0">Nossa equipe especializada está pronta para te ajudar.</p>
        <a href="contato.php" class="btn btn-sm btn-outline-warning mt-2">Falar conosco</a>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($relacionados)): ?>
<div class="card shadow-sm mt-4">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Produtos Relacionados</h5>
    <a href="produtos.php?categoria=<?= urlencode($produto['categoria']) ?>" class="btn btn-sm btn-light">Ver todos</a>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <?php foreach ($relacionados as $rel): ?>
      <div class="col-sm-4">
        <div class="card h-100 produto-card border">
          <img src="<?= htmlspecialchars($rel['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($rel['nome']) ?>" style="height:140px;object-fit:cover">
          <div class="card-body p-3">
            <h6 class="card-title fw-bold"><?= htmlspecialchars($rel['nome']) ?></h6>
            <p class="text-success fw-bold mb-2">R$ <?= number_format($rel['preco'], 2, ',', '.') ?></p>
            <a href="produto.php?id=<?= $rel['id'] ?>" class="btn btn-sm btn-outline-primary w-100">Ver Detalhes</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="mt-3">
  <a href="produtos.php" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Voltar aos Produtos
  </a>
</div>

<?php require_once 'layout_footer.php'; ?>
