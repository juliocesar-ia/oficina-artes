
<?php include 'includes/header.php'; ?>

<h2>Contato</h2>

<!-- Informações fixas -->
<p><strong>📍 Endereço:</strong> Rua Comunidade Esperança, 166, Cabo de S. Agostinho</p>
<p><strong>📞 Telefone:</strong> (81) 98775-3827</p>
<p><strong>✉️ Email:</strong> juliocesarbatistadelima53@gmail.com</p>
<p><strong>⏰ Atendimento:</strong> Seg a Sex - 09h às 18h</p>

<hr>

<!-- Formulário de contato -->
<h3>Envie sua mensagem</h3>
<form method="post" action="enviar_contato.php">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Mensagem:</label><br>
    <textarea name="mensagem" rows="5" required></textarea><br><br>

    <button type="submit">Enviar</button>
</form>

<hr>

<!-- Google Maps -->
<h3>Como chegar</h3>
<iframe

 src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d348.98182984923915!2d-35.011982613328954!3d-8.275035847626963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7aae53e0dfea3ff%3A0x7783f61bc09ba6da!2sIgreja%20evang%C3%A9lica%20batista!5e0!3m2!1spt-BR!2sbr!4v1758806647938!5m2!1spt-BR!2sbr" width="200" height="200
 " style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">

</iframe>

<?php include 'includes/footer.php'; ?>

