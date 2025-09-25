<?php
require __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

$errors = [];
$success = '';

$uploadDir = 'uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}
function valid_ext($filename) {
     $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
     $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
     return in_array($ext, $allowed);
}
if ($SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ??'');
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
}
// Ações: create / update / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;

    if ($titulo === '') $errors[] = 'Título é obrigatório.';
    if ($descricao === '') $errors[] = 'Descrição é obrigatória.';

    // tratar upload, se houver
    $foto_path = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erro no upload da imagem.';
        } else {
            if (!valid_ext($_FILES['foto']['name'])) {
                $errors[] = 'Extensão de imagem não permitida.';
            } else {
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $newName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = $uploadDir . '/' . $newName;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
                    $errors[] = 'Falha ao mover arquivo enviado.';
                } else {
                    $foto_path = $dest;
                }
            }
        }
    }

    if (empty($errors)) {
        if ($id) {
            // update
            // buscar registro antigo para remover foto se trocada
            $stmtOld = $pdo->prepare('SELECT foto_path FROM sobre WHERE id = ?');
            $stmtOld->execute([$id]);
            $old = $stmtOld->fetch();

            if ($foto_path) {
                // remover antiga se existir
                if ($old && !empty($old['foto_path']) && file_exists(__DIR__ . '/' . $old['foto_path'])) {
                    @unlink(__DIR__ . '/' . $old['foto_path']);
                }
                $sql = 'UPDATE sobre SET titulo = ?, descricao = ?, foto_path = ? WHERE id = ?';
                $pdo->prepare($sql)->execute([$titulo, $descricao, $foto_path, $id]);
            } else {
                $sql = 'UPDATE sobre SET titulo = ?, descricao = ? WHERE id = ?';
                $pdo->prepare($sql)->execute([$titulo, $descricao, $id]);
            }
            $success = 'Registro atualizado.';
        } else {
            // insert
            $sql = 'INSERT INTO sobre (titulo, descricao, foto_path) VALUES (?, ?, ?)';
            $pdo->prepare($sql)->execute([$titulo, $descricao, $foto_path]);
            $success = 'Registro criado.';
        }
    }
}

// deletar
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delId = (int)$_GET['id'];
    // buscar para apagar arquivo
    $stmt = $pdo->prepare('SELECT foto_path FROM sobre WHERE id = ?');
    $stmt->execute([$delId]);
    $row = $stmt->fetch();
    if ($row) {
        if (!empty($row['foto_path']) && file_exists(__DIR__ . '/' . $row['foto_path'])) {
            @unlink(__DIR__ . '/' . $row['foto_path']);
        }
        $pdo->prepare('DELETE FROM sobre WHERE id = ?')->execute([$delId]);
        $success = 'Registro excluído.';
    } else {
        $errors[] = 'Registro não encontrado.';
    }
}

// para edição: buscar registro
$edit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $eid = (int)$_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM sobre WHERE id = ?');
    $stmt->execute([$eid]);
    $edit = $stmt->fetch();
}

// listar todos
$rows = $pdo->query('SELECT * FROM sobre ORDER BY created_at DESC')->fetchAll();
?>

<h2>Sobre a Oficina (CRUD)</h2>

<?php if ($success): ?>
  <p style="color:green"><?php echo htmlspecialchars($success); ?></p>
<?php endif; ?>

<?php if ($errors): ?>
  <ul style="color:red">
    <?php foreach ($errors as $e): ?>
      <li><?php echo htmlspecialchars($e); ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<!-- Formulário de criação/edição -->
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $edit ? (int)$edit['id'] : ''; ?>">
  <label>Título:<br>
    <input type="text" name="titulo" value="<?php echo $edit ? htmlspecialchars($edit['titulo']) : ''; ?>" maxlength="150">
  </label>
  <br>
  <label>Descrição:<br>
    <textarea name="descricao" rows="6" cols="60"><?php echo $edit ? htmlspecialchars($edit['descricao']) : ''; ?></textarea>
  </label>
  <br>
  <label>Foto: (jpg, jpeg, png, gif, webp)<br>
    <input type="file" name="foto" accept="image/*">
  </label>
  <?php if ($edit && !empty($edit['foto_path']) && file_exists(__DIR__ . '/' . $edit['foto_path'])): ?>
    <div>
      <p>Foto atual:</p>
      <img src="<?php echo htmlspecialchars($edit['foto_path']); ?>" alt="foto" style="max-width:150px;">
    </div>
  <?php endif; ?>
  <br>
  <button type="submit"><?php echo $edit ? 'Atualizar' : 'Criar'; ?></button>
  <?php if ($edit): ?>
    <a href="/sobre.php">Cancelar</a>
  <?php endif; ?>
</form>

<hr>

<h3>Lista de registros</h3>
<?php if (count($rows) === 0): ?>
  <p>Nenhum registro encontrado.</p>
<?php else: ?>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Título</th>
      <th>Foto</th>
      <th>Criado</th>
      <th>Ações</th>
    </tr>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><?php echo (int)$r['id']; ?></td>
        <td><?php echo htmlspecialchars($r['titulo']); ?></td>
        <td>
          <?php if (!empty($r['foto_path']) && file_exists(__DIR__ . '/' . $r['foto_path'])): ?>
            <img src="<?php echo htmlspecialchars($r['foto_path']); ?>" alt="thumb" style="max-width:100px;">
          <?php else: ?>
            —
          <?php endif; ?>
        </td>
        <td><?php echo $r['created_at']; ?></td>
        <td>
          <a href="/sobre.php?action=edit&id=<?php echo (int)$r['id']; ?>">Editar</a> |
          <a href="/sobre.php?action=delete&id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Deseja excluir?');">Excluir</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>