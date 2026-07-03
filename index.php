<?php
$titulo = 'Home';
$breadcrumb = [['nome' => 'Home', 'url' => '#', 'ativo' => true]];
require_once 'dados.php';
require_once 'layout.php';
?>

<!-- Hero Banner -->
<div class="hero-section rounded-3 mb-4 p-5 text-white text-center">
  <div class="py-3">
    <i class="fas fa-hard-hat fa-4x text-warning mb-3"></i>
    <h1 class="display-5 fw-bold">ConstrutorMax</h1>
    <p class="lead mb-4">Sua loja completa de material de construção.<br>Qualidade, variedade e os melhores preços do mercado!</p>
    <a href="produtos.php" class="btn btn-warning btn-lg me-3 fw-bold">
      <i class="fas fa-boxes me-2"></i>Ver Produtos
    </a>
    <a href="contato.php" class="btn btn-outline-light btn-lg">
      <i class="fas fa-phone me-2"></i>Falar Conosco
    </a>
  </div>
</div>

<!-- Cards de navegação principal -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card h-100 border-0 shadow-sm produto-card">
      <div class="card-body text-center p-4">
        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px">
          <i class="fas fa-boxes fa-2x text-primary"></i>
        </div>
        <h4 class="card-title fw-bold">Nossos Produtos</h4>
        <p class="card-text text-muted">Cimentos, tijolos, areia, ferragens, tintas e muito mais com os melhores preços.</p>
        <a href="produtos.php" class="btn btn-primary mt-2">
          <i class="fas fa-arrow-right me-1"></i>Ver Catálogo
        </a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-0 shadow-sm produto-card">
      <div class="card-body text-center p-4">
        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px">
          <i class="fas fa-building fa-2x text-success"></i>
        </div>
        <h4 class="card-title fw-bold">A Empresa</h4>
        <p class="card-text text-muted">Conheça nossa história, missão e os clientes satisfeitos que confiam em nós há anos.</p>
        <a href="empresa.php" class="btn btn-success mt-2">
          <i class="fas fa-arrow-right me-1"></i>Saiba Mais
        </a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-0 shadow-sm produto-card">
      <div class="card-body text-center p-4">
        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px">
          <i class="fas fa-address-book fa-2x text-warning"></i>
        </div>
        <h4 class="card-title fw-bold">Contato</h4>
        <p class="card-text text-muted">Entre em contato conosco, visite nossas lojas ou nos siga nas redes sociais.</p>
        <a href="contato.php" class="btn btn-warning mt-2">
          <i class="fas fa-arrow-right me-1"></i>Falar Conosco
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Estatísticas -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card bg-primary text-white text-center p-3">
      <i class="fas fa-boxes fa-2x mb-2"></i>
      <h3 class="fw-bold mb-0"><?= count($produtos) ?>+</h3>
      <small>Produtos</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card bg-success text-white text-center p-3">
      <i class="fas fa-users fa-2x mb-2"></i>
      <h3 class="fw-bold mb-0"><?= count($clientes) ?>+</h3>
      <small>Clientes</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card bg-warning text-white text-center p-3">
      <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
      <h3 class="fw-bold mb-0"><?= count($locais) ?></h3>
      <small>Lojas</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card bg-info text-white text-center p-3">
      <i class="fas fa-truck fa-2x mb-2"></i>
      <h3 class="fw-bold mb-0">24h</h3>
      <small>Entrega</small>
    </div>
  </div>
</div>

<!-- Produtos em destaque -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Produtos em Destaque</h5>
    <a href="produtos.php" class="btn btn-sm btn-light">Ver todos</a>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <?php foreach (array_slice($produtos, 0, 4) as $p): ?>
      <div class="col-sm-6 col-lg-3">
        <div class="card h-100 produto-card border">
          <img src="<?= $p['imagem'] ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nome']) ?>" style="height:160px;object-fit:cover">
          <div class="card-body p-3">
            <span class="badge bg-secondary badge-categoria mb-1"><?= htmlspecialchars($p['categoria']) ?></span>
            <h6 class="card-title fw-bold"><?= htmlspecialchars($p['nome']) ?></h6>
            <p class="text-success fw-bold mb-2">R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
            <a href="produto.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
              <i class="fas fa-eye me-1"></i>Ver Detalhes
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Por que escolher a ConstrutorMax -->
<div class="card shadow-sm">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0"><i class="fas fa-thumbs-up me-2"></i>Por que escolher a ConstrutorMax?</h5>
  </div>
  <div class="card-body">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <i class="fas fa-medal fa-2x text-warning mb-2"></i>
        <h6 class="fw-bold">Qualidade</h6>
        <small class="text-muted">Produtos certificados e de alta qualidade</small>
      </div>
      <div class="col-6 col-md-3">
        <i class="fas fa-tag fa-2x text-success mb-2"></i>
        <h6 class="fw-bold">Melhores Preços</h6>
        <small class="text-muted">Preços competitivos no mercado</small>
      </div>
      <div class="col-6 col-md-3">
        <i class="fas fa-truck fa-2x text-primary mb-2"></i>
        <h6 class="fw-bold">Entrega Rápida</h6>
        <small class="text-muted">Entregamos em toda a região</small>
      </div>
      <div class="col-6 col-md-3">
        <i class="fas fa-headset fa-2x text-info mb-2"></i>
        <h6 class="fw-bold">Suporte</h6>
        <small class="text-muted">Atendimento especializado</small>
      </div>
    </div>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
