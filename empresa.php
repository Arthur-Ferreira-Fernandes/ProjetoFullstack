<?php
require_once 'dados.php';
$titulo = 'A Empresa';
$breadcrumb = [['nome' => 'Empresa', 'url' => '#', 'ativo' => true]];
require_once 'layout.php';
?>

<!-- Banner da empresa -->
<div class="card shadow-sm mb-4">
  <div class="card-body p-0">
    <div class="hero-section rounded-3 p-5 text-white text-center">
      <i class="fas fa-building fa-4x text-warning mb-3"></i>
      <h2 class="fw-bold">ConstrutorMax Materiais de Construção</h2>
      <p class="lead mb-0">Construindo sonhos desde 1995 — mais de 29 anos de tradição e qualidade.</p>
    </div>
  </div>
</div>

<!-- Missão, Visão, Valores -->
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card h-100 border-primary shadow-sm">
      <div class="card-body text-center p-4">
        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px">
          <i class="fas fa-bullseye fa-2x text-primary"></i>
        </div>
        <h5 class="card-title fw-bold text-primary">Nossa Missão</h5>
        <p class="card-text text-muted">Fornecer materiais de construção de alta qualidade com preços justos, contribuindo para a realização dos sonhos de nossos clientes.</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-success shadow-sm">
      <div class="card-body text-center p-4">
        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px">
          <i class="fas fa-eye fa-2x text-success"></i>
        </div>
        <h5 class="card-title fw-bold text-success">Nossa Visão</h5>
        <p class="card-text text-muted">Ser a principal referência em materiais de construção na região, reconhecida pela excelência no atendimento e qualidade dos produtos.</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100 border-warning shadow-sm">
      <div class="card-body text-center p-4">
        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px">
          <i class="fas fa-heart fa-2x text-warning"></i>
        </div>
        <h5 class="card-title fw-bold text-warning">Nossos Valores</h5>
        <p class="card-text text-muted">Honestidade, qualidade, respeito ao cliente, responsabilidade ambiental e compromisso com a excelência em cada produto entregue.</p>
      </div>
    </div>
  </div>
</div>

<!-- História -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Nossa História</h5>
  </div>
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-md-8">
        <p>A <strong>ConstrutorMax</strong> nasceu em 1995 como uma pequena loja de materiais de construção no centro de São Paulo, fundada pela família Silva com o objetivo de oferecer produtos de qualidade a preços acessíveis.</p>
        <p>Ao longo dos anos, crescemos e nos tornamos referência no setor, expandindo para <strong>3 unidades</strong> na região metropolitana e atendendo a milhares de clientes, desde reformas residenciais até grandes obras comerciais.</p>
        <p>Hoje contamos com um portfólio de mais de <strong>500 produtos</strong>, parceria com os principais fabricantes do Brasil e uma equipe de profissionais especializados prontos para orientar nossos clientes na melhor escolha.</p>
      </div>
      <div class="col-md-4">
        <div class="row g-3 text-center">
          <div class="col-6">
            <div class="card bg-primary text-white p-3">
              <h3 class="fw-bold mb-0">29+</h3>
              <small>Anos de mercado</small>
            </div>
          </div>
          <div class="col-6">
            <div class="card bg-success text-white p-3">
              <h3 class="fw-bold mb-0">500+</h3>
              <small>Produtos</small>
            </div>
          </div>
          <div class="col-6">
            <div class="card bg-warning text-white p-3">
              <h3 class="fw-bold mb-0">3</h3>
              <small>Lojas</small>
            </div>
          </div>
          <div class="col-6">
            <div class="card bg-info text-white p-3">
              <h3 class="fw-bold mb-0">5k+</h3>
              <small>Clientes</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Linha do tempo -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-secondary text-white">
    <h5 class="mb-0"><i class="fas fa-road me-2"></i>Nossa Trajetória</h5>
  </div>
  <div class="card-body">
    <div class="timeline">
      <?php
      $timeline = [
          ['ano' => '1995', 'evento' => 'Fundação da ConstrutorMax no Centro de SP', 'icon' => 'fas fa-flag', 'cor' => 'primary'],
          ['ano' => '2002', 'evento' => 'Abertura da segunda unidade na Zona Norte', 'icon' => 'fas fa-store', 'cor' => 'success'],
          ['ano' => '2010', 'evento' => 'Expansão do portfólio para mais de 300 produtos', 'icon' => 'fas fa-boxes', 'cor' => 'info'],
          ['ano' => '2015', 'evento' => 'Inauguração da terceira loja na Zona Sul', 'icon' => 'fas fa-building', 'cor' => 'warning'],
          ['ano' => '2020', 'evento' => 'Lançamento de vendas online e atendimento digital', 'icon' => 'fas fa-laptop', 'cor' => 'danger'],
          ['ano' => '2024', 'evento' => 'Mais de 5.000 clientes cadastrados na plataforma', 'icon' => 'fas fa-users', 'cor' => 'success'],
      ];
      foreach ($timeline as $t): ?>
      <div class="d-flex mb-3 align-items-start">
        <div class="me-3">
          <span class="badge bg-<?= $t['cor'] ?> p-2 fs-6"><?= $t['ano'] ?></span>
        </div>
        <div class="border-start border-2 ps-3 flex-fill">
          <i class="<?= $t['icon'] ?> text-<?= $t['cor'] ?> me-2"></i>
          <?= htmlspecialchars($t['evento']) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- CTA clientes -->
<div class="card shadow-sm bg-primary text-white">
  <div class="card-body text-center p-4">
    <i class="fas fa-users fa-3x mb-3"></i>
    <h4 class="fw-bold">Conheça Nossos Clientes</h4>
    <p class="mb-3">Veja a lista de clientes cadastrados que já confiam na ConstrutorMax para suas obras e reformas.</p>
    <a href="clientes.php" class="btn btn-warning btn-lg fw-bold">
      <i class="fas fa-id-card me-2"></i>Ver Clientes Cadastrados
    </a>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
