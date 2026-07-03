#ConstrutorMax — E-commerce de Material de Construção

Sistema completo de e-commerce para venda de materiais de construção, desenvolvido em PHP puro com AdminLTE 4, Bootstrap 5 e MySQL.

---

##Funcionalidades

### Área Pública
- **Home** — Banner hero, cards de navegação, estatísticas e vitrine de produtos em destaque
- **Catálogo de Produtos** — Listagem com filtro por categoria e busca por nome
- **Página do Produto** — Imagem, descrição, especificações técnicas e produtos relacionados
- **Empresa** — Apresentação institucional com missão, visão, valores e linha do tempo
- **Clientes** — Lista de clientes cadastrados com resumo de compras
- **Contato** — Formulário de contato com validação
- **Locais de Retirada** — Endereços, horários e guia de como retirar pedidos

### Autenticação
- Cadastro de novos usuários (perfil cliente)
- Login com validação de senha via `password_verify()`
- Logout com destruição de sessão
- Redirecionamento automático conforme perfil (admin/cliente)

### Área do Cliente
- Perfil com edição de nome, e-mail e telefone
- Alteração de senha com verificação da senha atual
- Histórico de compras com status colorido (Processando, Em trânsito, Entregue, Cancelado)

### Painel Administrativo
- Listagem de produtos com badge de estoque baixo (< 20 unidades)
- Criar novo produto com nome, categoria, preço, estoque, imagem e descrição
- Editar produto existente (dados + especificações técnicas)
- Excluir produto com confirmação via JavaScript
- Gerenciamento dinâmico de especificações técnicas (add/remove sem recarregar a página)
- Prévia da imagem em tempo real ao digitar a URL

---

##Tecnologias

| Camada | Tecnologia |
|--------|------------|
| Back-end | PHP 8+ (sem framework) |
| Banco de dados | MySQL / MariaDB com PDO |
| Front-end | AdminLTE 4.0.2 + Bootstrap 5.3.3 |
| Ícones | Font Awesome 6.5 |
| Sessões | PHP Sessions nativas |
| Senhas | `password_hash()` / `password_verify()` |

> Todos os assets (AdminLTE, Bootstrap, Font Awesome) estão incluídos localmente em `site/dist/` — o site funciona **100% offline**.

---

## Estrutura de Arquivos

```
ProjetoFullstack/
├── construtormax.sql          # Schema + seed data do banco
└── site/
    ├── index.php              # Home
    ├── produtos.php           # Catálogo
    ├── produto.php            # Página individual do produto
    ├── empresa.php            # Sobre a empresa
    ├── clientes.php           # Lista de clientes
    ├── contato.php            # Formulário de contato
    ├── locais.php             # Locais de retirada
    ├── login.php              # Autenticação
    ├── cadastro.php           # Registro de usuário
    ├── logout.php             # Encerrar sessão
    ├── perfil.php             # Perfil do usuário
    ├── auth.php               # Funções de sessão/autenticação
    ├── config.php             # Conexão PDO com o banco
    ├── dados.php              # Funções de consulta ao banco
    ├── layout.php             # Header + sidebar (reutilizável)
    ├── layout_footer.php      # Footer (reutilizável)
    ├── admin/
    │   ├── index.php          # Painel admin — lista de produtos
    │   └── produto_form.php   # Criar / editar produto
    ├── cliente/
    │   └── compras.php        # Histórico de compras
    └── dist/                  # AdminLTE 4 + Bootstrap 5 + Font Awesome (offline)
        ├── css/
        ├── js/
        └── plugins/
            ├── bootstrap/
            └── fontawesome/
```

---

##Instalação

### Pré-requisitos
- PHP 8.0 ou superior com extensão PDO e GD
- MySQL 5.7+ ou MariaDB 10.3+
- Servidor web Apache ou Nginx (ou XAMPP / WAMP / Laragon)

### Passo a passo

**1. Clone o repositório ou extraia o ZIP**
```bash
git clone https://github.com/arthur-ferreira-fernandes/projetofullstack.git
```

**2. Importe o banco de dados**
```bash
mysql -u root -p < construtormax.sql
```
Ou importe pelo phpMyAdmin usando o arquivo `construtormax.sql`.

**3. Configure a conexão com o banco**

Edite `site/config.php` com suas credenciais:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // seu usuário MySQL
define('DB_PASS', '');          // sua senha MySQL
define('DB_NAME', 'construtormax');
define('DB_PORT', 3306);
```

**4. Coloque a pasta `site/` no servidor**

Exemplo com XAMPP: copie a pasta para `C:/xampp/htdocs/construtormax/`

**5. Acesse no navegador**
```
http://localhost/construtormax/site/
```

---

##Usuários de demonstração

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Administrador | admin@construtormax.com | admin123 |
| Cliente | joao.silva@email.com | cliente123 |
| Cliente | maria.oliveira@email.com | cliente123 |

---

##Banco de Dados

### Tabelas

| Tabela | Descrição |
|--------|-----------|
| `produtos` | Catálogo de produtos (nome, categoria, preço, estoque, imagem) |
| `produto_especificacoes` | Especificações técnicas de cada produto |
| `usuarios` | Contas de acesso (admin e cliente) |
| `pedidos` | Histórico de compras vinculado a usuário e produto |
| `clientes` | Perfis de clientes cadastrados |
| `locais` | Unidades/pontos de retirada |

### Dados iniciais incluídos
- 9 produtos com especificações técnicas
- 3 usuários (1 admin + 2 clientes)
- 7 pedidos de demonstração com status variados
- 6 clientes cadastrados
- 3 locais de retirada

---

## Segurança

- Senhas armazenadas com `password_hash()` (bcrypt)
- Todas as queries usam **prepared statements** com PDO
- Saídas HTML protegidas com `htmlspecialchars()`
- Controle de acesso por perfil em cada página (`requireAdmin()`, `requireCliente()`)
- Proteção contra acesso direto a rotas protegidas via redirecionamento de sessão

---

## Licença

Projeto desenvolvido para fins educacionais.
