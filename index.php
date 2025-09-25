<?php
require __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';


// buscar último registro
$stmt = $pdo->query('SELECT * FROM sobre ORDER BY created_at DESC, id DESC LIMIT 1');
$ultimo = $stmt->fetch();
?>


<h2>Home</h2>


<?php if ($ultimo): ?>
<article>
<h3><?php echo htmlspecialchars($ultimo['titulo']); ?></h3>
<p><?php echo nl2br(htmlspecialchars($ultimo['descricao'])); ?></p>
<?php if (!empty($ultimo['foto_path']) && file_exists(__DIR__ . '/' . $ultimo['foto_path'])): ?>
<p><img src="<?php echo htmlspecialchars($ultimo['foto_path']); ?>" alt="<?php echo htmlspecialchars($ultimo['titulo']); ?>" style="max-width:300px;"></p>
<?php endif; ?>
<p><small>Publicado em: <?php echo $ultimo['created_at']; ?></small></p>
</article>
<?php else: ?>
<p>Nenhum conteúdo cadastrado ainda.</p>
<?php endif; ?>


<?php include __DIR__ . '/includes/footer.php'; ?>