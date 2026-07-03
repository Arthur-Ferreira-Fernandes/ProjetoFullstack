<?php
require_once __DIR__ . '/auth.php';
$pagina_atual = basename($_SERVER['PHP_SELF'], '.php');
$usuario_logado = usuarioLogado();

function asset(string $path): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    $base   = str_replace('\\', '/', __DIR__);
    $depth  = substr_count($script, '/') - substr_count($base, '/') - 1;
    return str_repeat('../', max(0, $depth)) . 'dist/' . $path;
}
function url(string $path): string {
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    $base   = str_replace('\\', '/', __DIR__);
    $depth  = substr_count($script, '/') - substr_count($base, '/') - 1;
    return str_repeat('../', max(0, $depth)) . $path;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($titulo ?? 'ConstrutorMax') ?> | ConstrutorMax</title>
  <link rel="stylesheet" href="<?= asset('plugins/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= asset('plugins/fontawesome/css/all.min.css') ?>">
  <style>
    .produto-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.15);transition:all .25s}
    .produto-card{transition:all .25s}
    .hero-section{background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%)}
    .preco-destaque{font-size:1.8rem;font-weight:700;color:#28a745}
    .social-icon{width:48px;height:48px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-size:1.4rem}
    .local-card .card-header{background:linear-gradient(135deg,#0f3460,#16213e)}
    .user-panel{padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.1)}
  </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

  <!-- Navbar superior -->
  <nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a></li>
      </ul>
      <a href="<?= url('index.php') ?>" class="navbar-brand ms-2">
        <i class="fas fa-hard-hat text-warning me-2"></i>
        <span class="fw-bold text-primary">ConstrutorMax</span>
        <small class="text-muted ms-2 d-none d-md-inline">Material de Construção</small>
      </a>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= url('contato.php') ?>" class="nav-link text-body"><i class="fas fa-phone me-1"></i> (11) 3000-1000</a>
        </li>
        <?php if ($usuario_logado): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:.8rem">
              <?= strtoupper(substr($usuario_logado['nome'], 0, 1)) ?>
            </div>
            <span class="d-none d-md-inline"><?= htmlspecialchars(explode(' ', $usuario_logado['nome'])[0]) ?></span>
            <?php if (isAdmin()): ?><span class="badge bg-danger ms-1">Admin</span><?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><h6 class="dropdown-header"><?= htmlspecialchars($usuario_logado['nome']) ?></h6></li>
            <li><a class="dropdown-item" href="<?= url('perfil.php') ?>"><i class="fas fa-user me-2"></i>Meu Perfil</a></li>
            <?php if (isCliente()): ?>
            <li><a class="dropdown-item" href="<?= url('cliente/compras.php') ?>"><i class="fas fa-shopping-bag me-2"></i>Minhas Compras</a></li>
            <?php endif; ?>
            <?php if (isAdmin()): ?>
            <li><a class="dropdown-item" href="<?= url('admin/index.php') ?>"><i class="fas fa-cog me-2"></i>Gerenciar Produtos</a></li>
            <?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= url('logout.php') ?>"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= url('login.php') ?>"><i class="fas fa-sign-in-alt me-1"></i>Entrar</a></li>
        <li class="nav-item"><a class="btn btn-primary btn-sm ms-2 me-2" href="<?= url('cadastro.php') ?>">Cadastre-se</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Sidebar -->
  <aside class="app-sidebar bg-dark shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
      <a href="<?= url('index.php') ?>" class="brand-link">
        <i class="fas fa-hard-hat text-warning fa-lg me-2"></i>
        <span class="brand-text fw-bold fs-5">ConstrutorMax</span>
      </a>
    </div>

    <?php if ($usuario_logado): ?>
    <div class="user-panel text-white">
      <div class="d-flex align-items-center gap-2">
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:36px;height:36px;min-width:36px">
          <?= strtoupper(substr($usuario_logado['nome'], 0, 1)) ?>
        </div>
        <div style="overflow:hidden">
          <div class="fw-semibold text-truncate" style="font-size:.9rem"><?= htmlspecialchars($usuario_logado['nome']) ?></div>
          <small class="text-muted"><?= isAdmin() ? 'Administrador' : 'Cliente' ?></small>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
          <li class="nav-item"><a href="<?= url('index.php') ?>" class="nav-link <?= $pagina_atual==='index'?'active':'' ?>"><i class="nav-icon fas fa-home"></i><p>Home</p></a></li>
          <li class="nav-item"><a href="<?= url('produtos.php') ?>" class="nav-link <?= in_array($pagina_atual,['produtos','produto'])?'active':'' ?>"><i class="nav-icon fas fa-boxes"></i><p>Produtos</p></a></li>
          <li class="nav-item"><a href="<?= url('empresa.php') ?>" class="nav-link <?= in_array($pagina_atual,['empresa','clientes'])?'active':'' ?>"><i class="nav-icon fas fa-building"></i><p>Empresa</p></a></li>
          <li class="nav-item"><a href="<?= url('contato.php') ?>" class="nav-link <?= in_array($pagina_atual,['contato','locais'])?'active':'' ?>"><i class="nav-icon fas fa-address-book"></i><p>Contato</p></a></li>

          <?php if ($usuario_logado): ?>
          <li class="nav-header mt-2">MINHA CONTA</li>
          <li class="nav-item"><a href="<?= url('perfil.php') ?>" class="nav-link <?= $pagina_atual==='perfil'?'active':'' ?>"><i class="nav-icon fas fa-user"></i><p>Meu Perfil</p></a></li>
          <?php if (isCliente()): ?>
          <li class="nav-item"><a href="<?= url('cliente/compras.php') ?>" class="nav-link <?= $pagina_atual==='compras'?'active':'' ?>"><i class="nav-icon fas fa-shopping-bag"></i><p>Minhas Compras</p></a></li>
          <?php endif; ?>
          <?php if (isAdmin()): ?>
          <li class="nav-header mt-2">ADMINISTRAÇÃO</li>
          <li class="nav-item"><a href="<?= url('admin/index.php') ?>" class="nav-link <?= in_array($pagina_atual,['index','produto_form'])?'active':'' ?>"><i class="nav-icon fas fa-cog"></i><p>Gerenciar Produtos</p></a></li>
          <li class="nav-item"><a href="<?= url('admin/produto_form.php') ?>" class="nav-link"><i class="nav-icon fas fa-plus-circle"></i><p>Novo Produto</p></a></li>
          <?php endif; ?>
          <li class="nav-item"><a href="<?= url('logout.php') ?>" class="nav-link text-danger"><i class="nav-icon fas fa-sign-out-alt"></i><p>Sair</p></a></li>
          <?php else: ?>
          <li class="nav-header mt-2">ACESSO</li>
          <li class="nav-item"><a href="<?= url('login.php') ?>" class="nav-link <?= $pagina_atual==='login'?'active':'' ?>"><i class="nav-icon fas fa-sign-in-alt"></i><p>Entrar</p></a></li>
          <li class="nav-item"><a href="<?= url('cadastro.php') ?>" class="nav-link <?= $pagina_atual==='cadastro'?'active':'' ?>"><i class="nav-icon fas fa-user-plus"></i><p>Cadastre-se</p></a></li>
          <?php endif; ?>

          <li class="nav-header mt-2">CATEGORIAS</li>
          <?php
          $cats=['Cimentos','Tijolos','Agregados','Tintas','Hidráulica','Ferro e Aço','Coberturas','Pisos e Revestimentos'];
          $icos=['fas fa-industry','fas fa-th-large','fas fa-mountain','fas fa-paint-roller','fas fa-faucet','fas fa-tools','fas fa-home','fas fa-border-all'];
          foreach($cats as $i=>$cat):?>
          <li class="nav-item"><a href="<?= url('produtos.php') ?>?categoria=<?= urlencode($cat) ?>" class="nav-link"><i class="nav-icon <?= $icos[$i] ?>"></i><p><?= $cat ?></p></a></li>
          <?php endforeach;?>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Conteúdo principal -->
  <main class="app-main">
    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0"><?= htmlspecialchars($titulo ?? 'Home') ?></h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
              <?php if(isset($breadcrumb)):foreach($breadcrumb as $b):?>
              <li class="breadcrumb-item <?= $b['ativo']?'active':'' ?>"><?= $b['ativo']?htmlspecialchars($b['nome']):'<a href="'.htmlspecialchars($b['url']).'">'.htmlspecialchars($b['nome']).'</a>' ?></li>
              <?php endforeach;endif;?>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="app-content"><div class="container-fluid">
