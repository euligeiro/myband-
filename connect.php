<?php
// Definir título da página
$page_title = "Contact";

// Incluir header
require 'header.php';

// Obter email do administrador de contato
$contact_email = '';
$contact_phone = '';
$contact_address = '';
try {
    $db = getDBConnection();
    $query = $db->prepare("SELECT email, phone, address FROM admin WHERE contact = 1 LIMIT 1");
    $query->execute();
    $admin = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $contact_email = $admin['email'] ?? 'contact@nodokaru.fr';
        $contact_phone = $admin['phone'] ?? '+01 234 555 89';
        $contact_address = $admin['address'] ?? 'My Garage, CA 94126, USA';
    } else {
        // Valores padrão caso não encontre na base de dados
        $contact_email = 'contact@nodokaru.fr';
        $contact_phone = '+01 234 555 89';
        $contact_address = 'My Garage, CA 94126, USA';
    }
} catch(PDOException $e) {
    // Valores padrão em caso de erro
    $contact_email = 'contact@nodokaru.fr';
    $contact_phone = '+01 234 555 89';
    $contact_address = 'My Garage, CA 94126, USA';
}

// Processar envio do formulário
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validação
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Tous les champs sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide";
    } else {
        // Enviar email (implementação básica)
        $to = $contact_email;
        $email_subject = "Nouveau message de contact: $subject";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $email_body = "Nom: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Sujet: $subject\n\n";
        $email_body .= "Message:\n$message";
        
        if (mail($to, $email_subject, $email_body, $headers)) {
            $success = true;
        } else {
            $error = "Erreur lors de l'envoi du message. Veuillez réessayer plus tard.";
        }
    }
}
?>

<section class="contact-section">
    <h1 class="contact-title">Contact us</h1>
    <p class="contact-subtitle">Do you have any questions? You need a quote?</p>
    
    <div class="contact-container">
        <div class="contact-form-container">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    Votre message a été envoyé avec succès!
                </div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contact-form">
                <div class="form-group">
                    <label for="name">Your name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Your email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required 
                           value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="message">Your message</label>
                    <textarea id="message" name="message" required rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>
                
                <button type="submit" name="send_message" class="submit-btn">Send</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h3>Contact Information</h3>
            <div class="contact-details">
                <div class="contact-item">
                    <strong>Address:</strong>
                    <p><?php echo htmlspecialchars($contact_address); ?></p>
                </div>
                
                <div class="contact-item">
                    <strong>Phone:</strong>
                    <p><?php echo htmlspecialchars($contact_phone); ?></p>
                </div>
                
                <div class="contact-item">
                    <strong>Email:</strong>
                    <p><?php echo htmlspecialchars($contact_email); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
// Incluir footer
require 'footer.php'; 
?>