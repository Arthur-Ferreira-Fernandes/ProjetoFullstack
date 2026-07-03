<?php
require_once 'auth.php';
require_once 'config.php';

if (isLoggedIn()) { header('Location: index.php'); exit; }

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
        $db   = getDB();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            iniciarSessao($usuario);
            $redirect = $_GET['redirect'] ?? 'index.php';
            header('Location: ' . $redirect);
            exit;
        }
        $erro = 'E-mail ou senha incorretos.';
    } else {
        $erro = 'Preencha todos os campos.';
    }
}

$titulo = 'Entrar';
$breadcrumb = [['nome' => 'Login', 'url' => '#', 'ativo' => true]];
require_once 'layout.php';
?>

<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white text-center py-4">
        <i class="fas fa-hard-hat fa-3x text-warning mb-2"></i>
        <h4 class="fw-bold mb-0">Entrar na sua conta</h4>
      </div>
      <div class="card-body p-4">
        <?php if ($erro): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-semibold">E-mail</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="seu@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Senha</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" name="senha" class="form-control" placeholder="Sua senha" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100 btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>Entrar
          </button>
        </form>
      </div>
      <div class="card-footer text-center text-muted py-3">
        Não tem conta? <a href="cadastro.php" class="fw-semibold text-decoration-none">Cadastre-se</a>
      </div>
    </div>

    <div class="card mt-3 border-info">
      <div class="card-body p-3">
        <p class="mb-1 fw-semibold text-info"><i class="fas fa-info-circle me-1"></i>Acesso de demonstração:</p>
        <small class="text-muted d-block">Admin: <strong>admin@construtormax.com</strong> / <strong>admin123</strong></small>
        <small class="text-muted d-block">Cliente: <strong>joao.silva@email.com</strong> / <strong>cliente123</strong></small>
      </div>
    </div>
  </div>
</div>

<?php require_once 'layout_footer.php'; ?>
