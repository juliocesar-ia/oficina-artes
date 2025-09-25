<?php
require __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

$rows = $pdo->query('SELECT id, titulo, foto_path, created_at FROM sobre WHERE foto_path IS NOT NULL ORDER BY created_at DESC')->fetchAll();
?>

<h2>Galeria</h2>

<?php if (!$rows): ?>
  <p>Nenhuma imagem cadastrada.</p>
<?php else: ?>
  <div>
    <?php foreach ($rows as $r): ?>
      <?php if (!empty($r['foto_path']) && file_exists(__DIR__ . '/' . $r['foto_path'])): ?>
        <figure style="display:inline-block; margin:8px; text-align:center;">
          <a href="<?php echo htmlspecialchars($r['foto_path']); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($r['foto_path']); ?>" alt="<?php echo htmlspecialchars($r['titulo']); ?>" style="max-width:150px; display:block;">
          </a>
          <figcaption><?php echo htmlspecialchars($r['titulo']); ?></figcaption>
        </figure>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>