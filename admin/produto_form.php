<?php
require_once '../auth.php';
require_once '../config.php';
requireAdmin();

$db = getDB();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$editando = $id > 0;
$erro = '';
$sucesso = '';

// Carregar produto existente
$produto = ['nome'=>'','categoria'=>'','preco'=>'','estoque'=>'','imagem'=>'','descricao'=>''];
$specs = [];

if ($editando) {
    $stmt = $db->prepare('SELECT * FROM produtos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch();
    if (!$produto) { header('Location: index.php'); exit; }

    $stmt2 = $db->prepare('SELECT chave, valor FROM produto_especificacoes WHERE produto_id = :id ORDER BY id');
    $stmt2->execute([':id' => $id]);
    $specs = $stmt2->fetchAll();
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome']      ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $preco     = floatval(str_replace(',', '.', $_POST['preco'] ?? '0'));
    $estoque   = intval($_POST['estoque'] ?? 0);
    $imagem    = trim($_POST['imagem']    ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    $spec_chaves = $_POST['spec_chave'] ?? [];
    $spec_valores = $_POST['spec_valor'] ?? [];

    if (!$nome || !$categoria || $preco <= 0) {
        $erro = 'Nome, categoria e preço são obrigatórios.';
    } else {
        if ($editando) {
            $db->prepare('UPDATE produtos SET nome=:nome, categoria=:cat, preco=:preco, estoque=:estoque, imagem=:img, descricao=:desc WHERE id=:id')
               ->execute([':nome'=>$nome,':cat'=>$categoria,':preco'=>$preco,':estoque'=>$estoque,':img'=>$imagem,':desc'=>$descricao,':id'=>$id]);
            $db->prepare('DELETE FROM produto_especificacoes WHERE produto_id = :id')->execute([':id' => $id]);
        } else {
            $db->prepare('INSERT INTO produtos (nome, categoria, preco, estoque, imagem, descricao) VALUES (:nome,:cat,:preco,:estoque,:img,:desc)')
               ->execute([':nome'=>$nome,':cat'=>$categoria,':preco'=>$preco,':estoque'=>$estoque,':img'=>$imagem,':desc'=>$descricao]);
            $id = $db->lastInsertId();
        }

        $ins = $db->prepare('INSERT INTO produto_especificacoes (produto_id, chave, valor) VALUES (:pid, :chave, :valor)');
        foreach ($spec_chaves as $k => $chave) {
            $chave = trim($chave);
            $valor = trim($spec_valores[$k] ?? '');
            if ($chave && $valor) {
                $ins->execute([':pid' => $id, ':chave' => $chave, ':valor' => $valor]);
            }
        }

        $_SESSION['flash'] = $editando ? 'Produto atualizado com sucesso.' : 'Produto criado com sucesso.';
        header('Location: index.php');
        exit;
    }

    // Rebuild para re-exibir
    $produto = compact('nome','categoria','preco','estoque','imagem','descricao');
    $specs = [];
    foreach ($spec_chaves as $k => $chave) {
        if (trim($chave)) {
            $specs[] = ['chave' => $chave, 'valor' => $spec_valores[$k] ?? ''];
        }
    }
}

$titulo = $editando ? 'Editar Produto' : 'Novo Produto';
$breadcrumb = [
    ['nome' => 'Admin', 'url' => 'index.php', 'ativo' => false],
    ['nome' => $titulo, 'url' => '#', 'ativo' => true],
];
require_once '../layout.php';
?>

<?php if ($erro): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST">
<div class="row g-4">

  <!-- Coluna principal -->
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-box-open me-2"></i>Informações do Produto</h5>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Nome do produto *</label>
            <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($produto['nome']) ?>" placeholder="Ex: Cimento CP-II 50kg">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-semibold">Categoria *</label>
            <input type="text" name="categoria" class="form-control" required value="<?= htmlspecialchars($produto['categoria']) ?>" list="categorias-list" placeholder="Ex: Cimentos">
            <datalist id="categorias-list">
              <?php
              $cats = $db->query('SELECT DISTINCT categoria FROM produtos ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);
              foreach ($cats as $c): ?>
              <option value="<?= htmlspecialchars($c) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>
          <div class="col-sm-3">
            <label class="form-label fw-semibold">Preço (R$) *</label>
            <input type="number" name="preco" class="form-control" required min="0.01" step="0.01" value="<?= htmlspecialchars($produto['preco']) ?>" placeholder="0,00">
          </div>
          <div class="col-sm-3">
            <label class="form-label fw-semibold">Estoque</label>
            <input type="number" name="estoque" class="form-control" min="0" value="<?= intval($produto['estoque']) ?>">
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">URL da imagem</label>
            <input type="url" name="imagem" class="form-control" value="<?= htmlspecialchars($produto['imagem']) ?>" placeholder="https://...">
            <div class="form-text">Cole o link direto para a imagem do produto.</div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva o produto..."><?= htmlspecialchars($produto['descricao']) ?></textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Especificações -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Especificações Técnicas</h5>
        <button type="button" class="btn btn-sm btn-warning" id="addSpec"><i class="fas fa-plus me-1"></i>Adicionar</button>
      </div>
      <div class="card-body">
        <div id="specs-container">
          <?php if (empty($specs)): ?>
          <div class="spec-row row g-2 mb-2">
            <div class="col-5"><input type="text" name="spec_chave[]" class="form-control" placeholder="Atributo (ex: Peso)"></div>
            <div class="col-6"><input type="text" name="spec_valor[]" class="form-control" placeholder="Valor (ex: 50 kg)"></div>
            <div class="col-1"><button type="button" class="btn btn-outline-danger btn-remove-spec w-100"><i class="fas fa-times"></i></button></div>
          </div>
          <?php else: ?>
          <?php foreach ($specs as $s): ?>
          <div class="spec-row row g-2 mb-2">
            <div class="col-5"><input type="text" name="spec_chave[]" class="form-control" value="<?= htmlspecialchars($s['chave']) ?>" placeholder="Atributo"></div>
            <div class="col-6"><input type="text" name="spec_valor[]" class="form-control" value="<?= htmlspecialchars($s['valor']) ?>" placeholder="Valor"></div>
            <div class="col-1"><button type="button" class="btn btn-outline-danger btn-remove-spec w-100"><i class="fas fa-times"></i></button></div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="form-text">Especificações que aparecem na página do produto.</div>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="col-lg-4">
    <!-- Preview imagem -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-secondary text-white">
        <h6 class="mb-0"><i class="fas fa-image me-2"></i>Prévia da Imagem</h6>
      </div>
      <div class="card-body text-center">
        <img id="img-preview"
             src="<?= htmlspecialchars($produto['imagem'] ?: 'https://placehold.co/400x300/e8e8e8/555?text=Produto') ?>"
             class="img-fluid rounded" style="max-height:200px;object-fit:cover" alt="Prévia">
      </div>
    </div>

    <!-- Ações -->
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="fas fa-save me-2"></i>Salvar</h6>
      </div>
      <div class="card-body d-grid gap-2">
        <button type="submit" class="btn btn-success btn-lg">
          <i class="fas fa-save me-2"></i><?= $editando ? 'Atualizar Produto' : 'Criar Produto' ?>
        </button>
        <a href="index.php" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i>Cancelar
        </a>
        <?php if ($editando): ?>
        <a href="../produto.php?id=<?= $id ?>" target="_blank" class="btn btn-outline-info btn-sm">
          <i class="fas fa-eye me-2"></i>Ver no site
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
</form>

<script>
// Prévia de imagem
document.querySelector('input[name="imagem"]').addEventListener('input', function () {
  const img = document.getElementById('img-preview');
  img.src = this.value || 'https://placehold.co/400x300/e8e8e8/555?text=Produto';
});

// Adicionar linha de especificação
document.getElementById('addSpec').addEventListener('click', function () {
  const row = document.createElement('div');
  row.className = 'spec-row row g-2 mb-2';
  row.innerHTML = `
    <div class="col-5"><input type="text" name="spec_chave[]" class="form-control" placeholder="Atributo"></div>
    <div class="col-6"><input type="text" name="spec_valor[]" class="form-control" placeholder="Valor"></div>
    <div class="col-1"><button type="button" class="btn btn-outline-danger btn-remove-spec w-100"><i class="fas fa-times"></i></button></div>`;
  document.getElementById('specs-container').appendChild(row);
});

// Remover linha
document.getElementById('specs-container').addEventListener('click', function (e) {
  if (e.target.closest('.btn-remove-spec')) {
    const rows = this.querySelectorAll('.spec-row');
    if (rows.length > 1) {
      e.target.closest('.spec-row').remove();
    }
  }
});
</script>

<?php require_once '../layout_footer.php'; ?>
