<?php
require_once 'dados.php';
$titulo = 'Locais de Retirada';
$breadcrumb = [
    ['nome' => 'Contato', 'url' => 'contato.php', 'ativo' => false],
    ['nome' => 'Locais de Retirada', 'url' => '#', 'ativo' => true],
];
require_once 'layout.php';
?>

<!-- Introdução -->
<div class="card shadow-sm mb-4 border-primary">
  <div class="card-body p-4 text-center">
    <i class="fas fa-map-marked-alt fa-3x text-primary mb-3"></i>
    <h4 class="fw-bold">Locais de Retirada de Material</h4>
    <p class="text-muted mb-0">Compre online ou por telefone e retire seu material em uma de nossas <?= count($locais) ?> lojas. Rápido, fácil e econômico!</p>
  </div>
</div>

<!-- Cards das lojas -->
<div class="row g-4 mb-4">
  <?php foreach ($locais as $i => $l): ?>
  <div class="col-md-4">
    <div class="card shadow-sm h-100 local-card">
      <div class="card-header text-white py-3">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px">
            <i class="fas fa-store text-white"></i>
          </div>
          <div>
            <h5 class="mb-0 fw-bold"><?= htmlspecialchars($l['nome']) ?></h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <ul class="list-unstyled mb-0">
          <li class="mb-2">
            <i class="fas fa-map-marker-alt text-danger me-2"></i>
            <strong><?= htmlspecialchars($l['endereco']) ?></strong><br>
            <span class="ms-4 text-muted"><?= htmlspecialchars($l['bairro']) ?> — <?= htmlspecialchars($l['cidade']) ?>/<?= htmlspecialchars($l['estado']) ?></span>
          </li>
          <li class="mb-2">
            <i class="fas fa-envelope text-muted me-2"></i>
            CEP: <?= htmlspecialchars($l['cep']) ?>
          </li>
          <li class="mb-2">
            <i class="fas fa-phone text-primary me-2"></i>
            <?= htmlspecialchars($l['telefone']) ?>
          </li>
          <li class="mb-3">
            <i class="fas fa-clock text-warning me-2"></i>
            <?= nl2br(str_replace('|', "\n", htmlspecialchars($l['horario']))) ?>
          </li>
        </ul>
        <a href="<?= $l['mapa'] ?>" target="_blank" class="btn btn-outline-primary w-100">
          <i class="fas fa-map me-2"></i>Ver no Mapa
        </a>
      </div>
      <?php if ($i === 0): ?>
      <div class="card-footer bg-warning bg-opacity-10 text-center">
        <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Loja Principal</span>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Como retirar - passo a passo -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-dark text-white">
    <h5 class="mb-0"><i class="fas fa-list-ol me-2"></i>Como Funciona a Retirada</h5>
  </div>
  <div class="card-body">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <div class="border rounded p-3 h-100">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2" style="width:40px;height:40px">1</div>
          <h6 class="fw-bold">Faça seu Pedido</h6>
          <small class="text-muted">Por telefone, WhatsApp ou pelo nosso site</small>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="border rounded p-3 h-100">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2" style="width:40px;height:40px">2</div>
          <h6 class="fw-bold">Confirme o Pagamento</h6>
          <small class="text-muted">Aceitamos PIX, cartão e dinheiro</small>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="border rounded p-3 h-100">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2" style="width:40px;height:40px">3</div>
          <h6 class="fw-bold">Aguarde o Aviso</h6>
          <small class="text-muted">Te avisamos quando o material estiver pronto</small>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="border rounded p-3 h-100">
          <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-2" style="width:40px;height:40px">4</div>
          <h6 class="fw-bold">Retire na Loja</h6>
          <small class="text-muted">Leve um documento e a nota do pedido</small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Redes sociais -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Siga-nos nas Redes Sociais</h5>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-6 col-md-3">
        <a href="#" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3 d-block">
          <i class="fab fa-facebook fa-3x text-primary mb-2"></i>
          <div class="fw-bold">Facebook</div>
          <small class="text-muted">/ConstrutorMax</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3 d-block">
          <i class="fab fa-instagram fa-3x text-danger mb-2"></i>
          <div class="fw-bold">Instagram</div>
          <small class="text-muted">@construtormax</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3 d-block">
          <i class="fab fa-whatsapp fa-3x text-success mb-2"></i>
          <div class="fw-bold">WhatsApp</div>
          <small class="text-muted">(11) 99000-1000</small>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="#" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3 d-block">
          <i class="fab fa-youtube fa-3x text-danger mb-2"></i>
          <div class="fw-bold">YouTube</div>
          <small class="text-muted">ConstrutorMax TV</small>
        </a>
      </div>
    </div>
    <div class="alert alert-info mt-3 mb-0">
      <i class="fas fa-bell me-2"></i>
      Nos siga para receber promoções exclusivas, dicas de obra e lançamentos em primeira mão!
    </div>
  </div>
</div>

<div class="mt-2">
  <a href="contato.php" class="btn btn-outline-secondary">
    <i class="fas fa-arrow-left me-2"></i>Voltar ao Contato
  </a>
</div>

<?php require_once 'layout_footer.php'; ?>
