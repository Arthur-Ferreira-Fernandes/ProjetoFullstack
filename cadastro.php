<?php
require_once 'auth.php';
require_once 'config.php';

if (isLoggedIn()) { header('Location: index.php'); exit; }

$erro  = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome   = trim($_POST['nome']   ?? '');
    $email  = trim($_POST['email']  ?? '');
    $tel    = trim($_POST['telefone'] ?? '');
    $senha  = $_POST['senha']        ?? '';
    $senha2 = $_POST['senha2']       ?? '';

    if (!$nome || !$email || !$senha) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter no mínimo 6 caracteres.';
    } elseif ($senha !== $senha2) {
        $erro = 'As senhas não coincidem.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = :email');
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO usuarios (nome, email, telefone, senha_hash, tipo) VALUES (:nome, :email, :tel, :hash, "cliente")');
            $stmt->execute([':nome' => $nome, ':email' => $email, ':tel' => $tel, ':hash' => $hash]);
            $id = $db->lastInsertId();
            iniciarSessao(['id' => $id, 'nome' => $nome, 'email' => $email, 'tipo' => 'cliente']);
            header('Location: index.php');
            exit;
        }
    }
}

$titulo = 'Cadastro';
$breadcrumb = [['nome' => 'Cadastro', 'url' => '#', 'ativo' => true]];
require_once 'layout.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white text-center py-4">
        <i class="fas fa-user-plus fa-3x mb-2"></i>
        <h4 class="fw-bold mb-0">Criar sua conta</h4>
      </div>
      <div class="card-body p-4">
        <?php if ($erro): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nome completo *</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" name="nome" class="form-control" placeholder="Seu nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">E-mail *</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="seu@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Telefone</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input type="tel" name="telefone" class="form-control" placeholder="(11) 99999-9999" value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Senha * <small class="text-muted">(mín. 6 caracteres)</small></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" name="senha" class="form-control" placeholder="Crie uma senha" required minlength="6">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Confirmar senha *</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" name="senha2" class="form-control" placeholder="Repita a senha" required>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100 btn-lg">
            <i class="fas fa-user-plus me-2"></i>Criar conta
          </button>
        </form>
      </div>
      <div class="card-footer text-center text-muted py-3">
        Já tem conta? <a href="login.php" class="fw-semibold text-decoration-none">Entrar</a>
      </div>
    </div>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
