<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin('login.php');

$db = getDB();
$u  = $db->prepare('SELECT * FROM usuarios WHERE id = :id');
$u->execute([':id' => $_SESSION['usuario_id']]);
$usuario = $u->fetch();

$sucesso = '';
$erro    = '';

// Atualizar dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {

    if ($_POST['acao'] === 'dados') {
        $nome  = trim($_POST['nome']     ?? '');
        $email = trim($_POST['email']    ?? '');
        $tel   = trim($_POST['telefone'] ?? '');

        if (!$nome || !$email) {
            $erro = 'Nome e e-mail são obrigatórios.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = 'E-mail inválido.';
        } else {
            $chk = $db->prepare('SELECT id FROM usuarios WHERE email = :email AND id != :id');
            $chk->execute([':email' => $email, ':id' => $usuario['id']]);
            if ($chk->fetch()) {
                $erro = 'Este e-mail já está em uso por outra conta.';
            } else {
                $db->prepare('UPDATE usuarios SET nome = :nome, email = :email, telefone = :tel WHERE id = :id')
                   ->execute([':nome' => $nome, ':email' => $email, ':tel' => $tel, ':id' => $usuario['id']]);
                $_SESSION['usuario_nome']  = $nome;
                $_SESSION['usuario_email'] = $email;
                $usuario['nome']  = $nome;
                $usuario['email'] = $email;
                $usuario['telefone'] = $tel;
                $sucesso = 'Dados atualizados com sucesso!';
            }
        }
    }

    if ($_POST['acao'] === 'senha') {
        $atual  = $_POST['senha_atual']  ?? '';
        $nova   = $_POST['senha_nova']   ?? '';
        $nova2  = $_POST['senha_nova2']  ?? '';

        if (!$atual || !$nova || !$nova2) {
            $erro = 'Preencha todos os campos de senha.';
        } elseif (!password_verify($atual, $usuario['senha_hash'])) {
            $erro = 'Senha atual incorreta.';
        } elseif (strlen($nova) < 6) {
            $erro = 'A nova senha deve ter no mínimo 6 caracteres.';
        } elseif ($nova !== $nova2) {
            $erro = 'As novas senhas não coincidem.';
        } else {
            $hash = password_hash($nova, PASSWORD_DEFAULT);
            $db->prepare('UPDATE usuarios SET senha_hash = :hash WHERE id = :id')
               ->execute([':hash' => $hash, ':id' => $usuario['id']]);
            $sucesso = 'Senha alterada com sucesso!';
        }
    }
}

$titulo = 'Meu Perfil';
$breadcrumb = [['nome' => 'Perfil', 'url' => '#', 'ativo' => true]];
require_once 'layout.php';
?>

<?php if ($sucesso): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($sucesso) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($erro): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">
  <!-- Card de identidade -->
  <div class="col-md-4">
    <div class="card shadow-sm text-center">
      <div class="card-body py-4">
        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold mb-3" style="width:80px;height:80px;font-size:2rem">
          <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
        </div>
        <h5 class="fw-bold mb-1"><?= htmlspecialchars($usuario['nome']) ?></h5>
        <p class="text-muted mb-2"><?= htmlspecialchars($usuario['email']) ?></p>
        <span class="badge bg-<?= $usuario['tipo'] === 'admin' ? 'danger' : 'primary' ?> fs-6 px-3">
          <?= $usuario['tipo'] === 'admin' ? 'Administrador' : 'Cliente' ?>
        </span>
        <hr>
        <ul class="list-unstyled text-start small text-muted mb-0">
          <li class="mb-1"><i class="fas fa-phone me-2"></i><?= htmlspecialchars($usuario['telefone'] ?: 'Não informado') ?></li>
          <li><i class="fas fa-calendar me-2"></i>Membro desde <?= date('d/m/Y', strtotime($usuario['criado_em'])) ?></li>
        </ul>
      </div>
    </div>

    <?php if ($usuario['tipo'] === 'cliente'): ?>
    <div class="card shadow-sm mt-3">
      <div class="card-body text-center">
        <i class="fas fa-shopping-bag fa-2x text-primary mb-2"></i>
        <h6 class="fw-bold">Minhas Compras</h6>
        <a href="cliente/compras.php" class="btn btn-outline-primary btn-sm mt-1">Ver histórico</a>
      </div>
    </div>
    <?php endif; ?>
    <?php if ($usuario['tipo'] === 'admin'): ?>
    <div class="card shadow-sm mt-3">
      <div class="card-body text-center">
        <i class="fas fa-cog fa-2x text-danger mb-2"></i>
        <h6 class="fw-bold">Painel Admin</h6>
        <a href="admin/index.php" class="btn btn-outline-danger btn-sm mt-1">Gerenciar produtos</a>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Formulários -->
  <div class="col-md-8">
    <!-- Dados pessoais -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Editar Dados Pessoais</h5>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="acao" value="dados">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Nome completo *</label>
              <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($usuario['nome']) ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">E-mail *</label>
              <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Telefone</label>
              <input type="tel" name="telefone" class="form-control" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($usuario['telefone'] ?? '') ?>">
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Salvar alterações</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Alterar senha -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Alterar Senha</h5>
      </div>
      <div class="card-body">
        <form method="POST">
          <input type="hidden" name="acao" value="senha">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Senha atual *</label>
              <input type="password" name="senha_atual" class="form-control" placeholder="Digite sua senha atual" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Nova senha *</label>
              <input type="password" name="senha_nova" class="form-control" placeholder="Mín. 6 caracteres" required minlength="6">
            </div>
            <div class="col-sm-6">
              <label class="form-label fw-semibold">Confirmar nova senha *</label>
              <input type="password" name="senha_nova2" class="form-control" placeholder="Repita a nova senha" required>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-dark"><i class="fas fa-key me-2"></i>Alterar senha</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
