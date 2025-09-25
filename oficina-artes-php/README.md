# Oficina de Artes (PHP + MySQL)

Projeto simples sem CSS. Contém CRUD para "Sobre a Oficina" com upload de imagens.

## Instalação rápida

1. Clone o repositório.
2. Crie a pasta uploads/ (ou já estará criada). Coloque um .gitkeep para versionar.
3. Execute o script SQL em sql/create_db.sql para criar o banco e tabela.
   - Ex.: usando mysql -u root -p < sql/create_db.sql ou via phpMyAdmin.
4. Ajuste as credenciais em config/db.php (host, usuário, senha).
5. Acesse http://localhost/oficina-artes-php/index.php (dependendo do seu servidor).

## Observações

- Sem CSS: aplique seu próprio estilo depois.
- Mensagens de erro são intencionais e simples para ensino.
- Verifique permissões da pasta uploads/ para permitir upload (por exemplo 0755 ou 0775 dependendo do ambiente).