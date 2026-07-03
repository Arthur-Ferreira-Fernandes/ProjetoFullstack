<?php
require_once 'dados.php';
$titulo = 'Contato';
$breadcrumb = [['nome' => 'Contato', 'url' => '#', 'ativo' => true]];

$sucesso = false;
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');
    if ($nome && $email && $assunto && $mensagem) {
        // Em produção: enviar e-mail com mail() ou biblioteca
        $sucesso = true;
    } else {
        $erro = 'Preencha todos os campos obrigatórios.';
    }
}

require_once 'layout.php';
?>

<!-- Destaque de contato -->
<div class="row g-3 mb-4">
  <div class="col-sm-4">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body p-4">
        <i class="fas fa-phone fa-2x text-primary mb-2"></i>
        <h6 class="fw-bold">Telefone</h6>
        <p class="mb-0">(11) 3000-1000</p>
        <p class="text-muted small mb-0">(11) 3000-2000 (Norte)</p>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body p-4">
        <i class="fas fa-envelope fa-2x text-success mb-2"></i>
        <h6 class="fw-bold">E-mail</h6>
        <p class="mb-0"><a href="mailto:contato@construtormax.com.br" class="text-decoration-none">contato@construtormax.com.br</a></p>
        <p class="text-muted small mb-0">Respondemos em até 24h</p>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card text-center shadow-sm h-100">
      <div class="card-body p-4">
        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
        <h6 class="fw-bold">Horário</h6>
        <p class="mb-0">Seg–Sex: 07h às 18h</p>
        <p class="text-muted small mb-0">Sáb: 07h às 13h</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- Formulário de contato -->
  <div class="col-md-7">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Enviar Mensagem</h5>
      </div>
      <div class="card-body">
        <?php if ($sucesso): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle me-2"></i>
          Mensagem enviada com sucesso! Em breve entraremos em contato.
        </div>
        <?php endif; ?>
        <?php if ($erro): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?>
        </div>
        <?php endif; ?>

        <form method="POST">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Nome *</label>
              <input type="text" name="nome" class="form-control" placeholder="Seu nome completo" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">E-mail *</label>
              <input type="email" name="email" class="form-control" placeholder="seu@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Telefone</label>
              <input type="tel" name="telefone" class="form-control" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Assunto *</label>
              <select name="assunto" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="orcamento" <?= ($_POST['assunto'] ?? '') === 'orcamento' ? 'selected' : '' ?>>Orçamento</option>
                <option value="duvida" <?= ($_POST['assunto'] ?? '') === 'duvida' ? 'selected' : '' ?>>Dúvida sobre produto</option>
                <option value="entrega" <?= ($_POST['assunto'] ?? '') === 'entrega' ? 'selected' : '' ?>>Informações de entrega</option>
                <option value="reclamacao" <?= ($_POST['assunto'] ?? '') === 'reclamacao' ? 'selected' : '' ?>>Reclamação</option>
                <option value="outro" <?= ($_POST['assunto'] ?? '') === 'outro' ? 'selected' : '' ?>>Outro</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Mensagem *</label>
              <textarea name="mensagem" class="form-control" rows="5" placeholder="Descreva sua dúvida ou pedido de orçamento..." required><?= htmlspecialchars($_POST['mensagem'] ?? '') ?></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-paper-plane me-2"></i>Enviar Mensagem
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Redes sociais e locais -->
  <div class="col-md-5">
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Redes Sociais</h5>
      </div>
      <div class="card-body text-center py-4">
        <a href="#" class="social-icon bg-primary text-white me-2 mb-2 text-decoration-none">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="social-icon bg-danger text-white me-2 mb-2 text-decoration-none">
          <i class="fab fa-instagram"></i>
        </a>
        <a href="#" class="social-icon bg-success text-white me-2 mb-2 text-decoration-none">
          <i class="fab fa-whatsapp"></i>
        </a>
        <a href="#" class="social-icon bg-info text-white me-2 mb-2 text-decoration-none">
          <i class="fab fa-youtube"></i>
        </a>
        <div class="mt-3">
          <p class="text-muted small">Nos siga para novidades, promoções e dicas de construção!</p>
        </div>
        <div class="list-group mt-2">
          <div class="list-group-item d-flex align-items-center gap-2">
            <i class="fab fa-facebook text-primary"></i>
            <a href="#" class="text-decoration-none">/ConstrutorMax</a>
          </div>
          <div class="list-group-item d-flex align-items-center gap-2">
            <i class="fab fa-instagram text-danger"></i>
            <a href="#" class="text-decoration-none">@construtormax</a>
          </div>
          <div class="list-group-item d-flex align-items-center gap-2">
            <i class="fab fa-whatsapp text-success"></i>
            <a href="#" class="text-decoration-none">(11) 99000-1000</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Mini lista de lojas -->
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Nossas Lojas</h5>
        <a href="locais.php" class="btn btn-sm btn-light">Ver todas</a>
      </div>
      <div class="list-group list-group-flush">
        <?php foreach ($locais as $l): ?>
        <div class="list-group-item">
          <h6 class="fw-bold mb-1"><?= htmlspecialchars($l['nome']) ?></h6>
          <small class="text-muted">
            <i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($l['endereco']) ?>, <?= htmlspecialchars($l['bairro']) ?><br>
            <i class="fas fa-clock me-1"></i><?= htmlspecialchars(explode('|', $l['horario'])[0]) ?>
          </small>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="card-footer text-center">
        <a href="locais.php" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-store me-1"></i>Ver locais de retirada
        </a>
      </div>
    </div>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
