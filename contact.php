<?php
// Definir título da página
$page_title = "Contact";

// Incluir header
require 'header.php';

// Obter email do administrador de contato
$contact_email = '';
try {
    $db = getDBConnection();
    $query = $db->prepare("SELECT email FROM admin WHERE contact = 1 LIMIT 1");
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);
    $contact_email = $admin ? $admin['email'] : '';
} catch(PDOException $e) {
    // Silenciosamente ignorar erro para não quebrar a página
}

// Processar envio do formulário
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    
    // Validação
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Tous les champs sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide";
    } else {
        // Enviar email (implementação básica)
        $to = $contact_email;
        $subject = "Nouveau message de contact de $name";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $email_body = "Nom: $name\n";
        $email_body .= "Email: $email\n\n";
        $email_body .= "Message:\n$message";
        
        if (mail($to, $subject, $email_body, $headers)) {
            $success = true;
        } else {
            $error = "Erreur lors de l'envoi du message. Veuillez réessayer plus tard.";
        }
    }
}
?>

<section style="max-width: 600px; margin: 2rem auto; padding: 1rem;">
    <h1 style="text-align:center; margin-bottom:1rem;">Contactez-nous</h1>
    
    <?php if ($success): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            Votre message a été envoyé avec succès!
        </div>
    <?php elseif (!empty($error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div style="margin-bottom: 15px;">
            <label for="name" style="display: block; margin-bottom: 5px;">Nom:</label>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 8px; box-sizing: border-box;" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="email" style="display: block; margin-bottom: 5px;">Email:</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; box-sizing: border-box;" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="message" style="display: block; margin-bottom: 5px;">Message:</label>
            <textarea id="message" name="message" required rows="5" style="width: 100%; padding: 8px; box-sizing: border-box;"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
        </div>
        
        <button type="submit" name="send_message" style="background: #008080; color: white; padding: 10px 15px; border: none; cursor: pointer;">Envoyer</button>
    </form>
</section>

<?php 
// Incluir footer
require 'footer.php'; 
?>