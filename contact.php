<?php
require_once 'includes/db.php';
$page_title = 'Contact SDK Contracting & Services — Qatar';
$success = $error = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || strlen($name) < 2)
        $errors['name'] = 'Please enter your full name (min 2 characters).';
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';
    if ($phone && !preg_match('/^\+?[\d\s\-\(\)]{7,20}$/', $phone))
        $errors['phone'] = 'Please enter a valid phone number.';
    if (!$message || strlen($message) < 10)
        $errors['message'] = 'Please write a message (min 10 characters).';

    if (empty($errors)) {
        db()->prepare("INSERT INTO messages (name, phone, email, subject, message) VALUES (?,?,?,?,?)")
             ->execute([$name, $phone, $email, $subject, $message]);
        $success = 'Thank you, ' . htmlspecialchars($name) . '! We will contact you within 24 hours.';
        $_POST = [];
    } else {
        $error = 'Please fix the errors below.';
    }
}
require_once 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <div class="section-label">Get In Touch</div>
        <h1>Contact Us</h1>
        <p>We respond to all enquiries within one business day  call, WhatsApp, or fill the form below</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-grid">

            <div class="contact-info">
                <div class="section-label">Office</div>
                <h2>Let's Discuss<br>Your Project</h2>
                <p>Whether you have a detailed brief or just an early idea our team is ready to help scope, plan, and deliver your Qatar project.</p>

                <div class="contact-detail">
                    <div class="contact-detail-icon">📍</div>
                    <div>
                        <h4>Address</h4>
                        <p>Al Gassarat Road, Street No. 11<br>Industrial Area, Doha, Qatar</p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">📞</div>
                    <div>
                        <h4>Phone & WhatsApp</h4>
                        <p>
                            <a href="tel:+97466927592">+974 6692 7592</a> (also WhatsApp)<br>
                            <a href="tel:+97477024499">+974 7702 4499</a> (also WhatsApp)
                        </p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">✉️</div>
                    <div>
                        <h4>Email</h4>
                        <p>
                            <a href="mailto:ajmal@sdkcontractingservices.com">ajmal@sdkcontractingservices.com</a><br>
                            <a href="mailto:sherdil@sdkcontractingservices.com">sherdil@sdkcontractingservices.com</a>
                        </p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">🕐</div>
                    <div>
                        <h4>Working Hours</h4>
                        <p>Sunday – Thursday: 7:00 AM – 5:00 PM<br>Friday & Saturday: Closed</p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">📋</div>
                    <div>
                        <h4>Company Registration</h4>
                        <p>Reg. No: 207895<br>Registered: 10 December 2024, Qatar</p>
                    </div>
                </div>

                <div style="display:flex;gap:12px;margin-top:28px;flex-wrap:wrap;">
                    <a href="https://wa.me/97466927592" class="btn btn-primary" target="_blank" rel="noopener">💬 WhatsApp Line 1</a>
                    <a href="https://wa.me/97477024499" class="btn btn-outline" target="_blank" rel="noopener">💬 WhatsApp Line 2</a>
                </div>
            </div>

            <div class="contact-form-card">
                <h3 style="font-family:var(--ff-head);font-size:1.4rem;color:var(--white);margin-bottom:28px;">Send Us a Message</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success">✅ <?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="/contact" id="contactForm" novalidate>
                    <div class="form-row">
                        <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
                            <label for="name">Full Name <span style="color:var(--gold)">*</span></label>
                            <input type="text" id="name" name="name"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                   placeholder="Your full name" autocomplete="name" required>
                            <?php if (isset($errors['name'])): ?>
                                <span class="field-error">⚠ <?= htmlspecialchars($errors['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                   placeholder="+974 XXXX XXXX"
                                   autocomplete="tel" inputmode="tel">
                            <?php if (isset($errors['phone'])): ?>
                                <span class="field-error">⚠ <?= htmlspecialchars($errors['phone']) ?></span>
                            <?php endif; ?>
                            <span class="field-hint">Digits, spaces, + or - only</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                            <label for="email">Email Address <span style="color:var(--gold)">*</span></label>
                            <input type="email" id="email" name="email"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   placeholder="you@example.com" autocomplete="email" required>
                            <?php if (isset($errors['email'])): ?>
                                <span class="field-error">⚠ <?= htmlspecialchars($errors['email']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="subject">Service Required</label>
                            <select id="subject" name="subject">
                                <option value="" <?= empty($_POST['subject']) ? 'selected':'' ?>>Select a service...</option>
                                <option value="Villa Construction" <?= ($_POST['subject']??'')==='Villa Construction' ? 'selected':'' ?>>Villa & Building Construction</option>
                                <option value="Road Asphalt" <?= ($_POST['subject']??'')==='Road Asphalt' ? 'selected':'' ?>>Road Asphalt & Subbase</option>
                                <option value="Demolition" <?= ($_POST['subject']??'')==='Demolition' ? 'selected':'' ?>>Demolition & Excavation</option>
                                <option value="Earth Leveling" <?= ($_POST['subject']??'')==='Earth Leveling' ? 'selected':'' ?>>Earth Leveling</option>
                                <option value="Building Materials" <?= ($_POST['subject']??'')==='Building Materials' ? 'selected':'' ?>>Building Materials Supply</option>
                                <option value="Cleaning" <?= ($_POST['subject']??'')==='Cleaning' ? 'selected':'' ?>>Cleaning Services</option>
                                <option value="Legal Advisory" <?= ($_POST['subject']??'')==='Legal Advisory' ? 'selected':'' ?>>Legal Advisory</option>
                                <option value="Typing Translation" <?= ($_POST['subject']??'')==='Typing Translation' ? 'selected':'' ?>>Typing & Translation</option>
                                <option value="Other" <?= ($_POST['subject']??'')==='Other' ? 'selected':'' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group <?= isset($errors['message']) ? 'has-error' : '' ?>">
                        <label for="message">Your Message <span style="color:var(--gold)">*</span></label>
                        <textarea id="message" name="message" rows="6"
                                  placeholder="Tell us about your project — type, location, size, and timeline..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        <?php if (isset($errors['message'])): ?>
                            <span class="field-error">⚠ <?= htmlspecialchars($errors['message']) ?></span>
                        <?php endif; ?>
                        <span class="field-hint char-count-hint">Minimum 10 characters</span>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;padding:16px;font-size:15px;">
                        Send Message →
                    </button>
                    <p style="font-size:12px;color:var(--muted-2);text-align:center;margin-top:12px;">
                        🔒 Your information is safe with us. We never share your data.
                    </p>
                </form>
            </div>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
