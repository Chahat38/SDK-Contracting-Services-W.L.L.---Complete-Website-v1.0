<?php
require_once 'includes/db.php';
$page_title = 'Projects';
$projects = db()->query(
    "SELECT * FROM projects WHERE status='active' ORDER BY created_at DESC"
)->fetchAll();
require_once 'includes/header.php';

$fallback_imgs = [
    'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80',
    'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&q=80',
    'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&q=80',
    'https://images.unsplash.com/photo-1590496793929-36417d3117de?w=800&q=80',
    'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800&q=80',
    'https://images.unsplash.com/photo-1518780664697-55e3ad937233?w=800&q=80',
];
?>

<div class="page-hero">
    <div class="container">
        <div class="section-label">Portfolio</div>
        <h1>Our Projects</h1>
        <p>A showcase of work delivered across Qatar  from foundation to handover</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (empty($projects)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🏗️</div>
                <h3>No Projects Yet</h3>
                <p>Projects will appear here once added. Check back soon.</p>
                <a href="/contact" class="btn btn-primary">Contact Us →</a>
            </div>
        <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $i => $p): ?>
            <div class="project-card">
                <div class="project-card-img">
                    <?php if ($p['image'] && file_exists(__DIR__ . '/uploads/projects/' . $p['image'])): ?>
                        <img src="/uploads/projects/<?= htmlspecialchars($p['image']) ?>"
                             alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy"
                             onerror="this.src='<?= $fallback_imgs[$i % count($fallback_imgs)] ?>';">
                    <?php else: ?>
                        <img src="<?= $fallback_imgs[$i % count($fallback_imgs)] ?>"
                             alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                    <?php endif; ?>
                    <div class="project-card-overlay">
                        <span class="project-card-view-btn">View Details →</span>
                    </div>
                </div>
                <div class="project-card-body">
                    <?php if (!empty($p['category'])): ?>
                    <div class="project-card-tag"><?= htmlspecialchars($p['category']) ?></div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($p['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($p['description'] ?? '', 0, 130)) ?>...</p>
                    <div class="project-card-footer">
                        <span class="project-card-date">📅 <?= date('M Y', strtotime($p['created_at'])) ?></span>
                        <a href="/contact?project=<?= urlencode($p['title']) ?>" class="project-card-link">Get Quote →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" style="background:linear-gradient(135deg,#7C3AED 0%,#4F46E5 60%,#4338CA 100%);">
    <div class="cta-bg"></div>

    <div class="container cta-inner">
        <div class="cta-text">

            <div class="section-label" style="justify-content:flex-start;color:rgba(255,255,255,0.8);">
                Work With Us
            </div>

            <h2 class="section-title" style="margin-bottom:12px;color:#FFFFFF;">
                Have a Project<br>in Mind?
            </h2>

            <p style="color:rgba(255,255,255,0.82); font-size:16px; line-height:1.8;">
                We'd love to bring your vision to life. Let's discuss your project today.
            </p>

        </div>

        <div class="cta-actions">

            <a href="/contact"
               class="btn btn-lg"
               style="background:#FFFFFF;color:#4F46E5;border-color:#FFFFFF;">
                Start a Project →
            </a>

            <a href="https://wa.me/<?= setting('whatsapp_number','97455556666') ?>"
               class="btn btn-lg"
               style="background:transparent;color:#FFFFFF;border-color:rgba(255,255,255,0.65);"
               target="_blank">
                💬 WhatsApp Us
            </a>

            <div class="cta-trust">
                <span style="color:rgba(255,255,255,0.72);">✅ Free Quote</span>
                <span style="color:rgba(255,255,255,0.72);">✅ 24hr Response</span>
                <span style="color:rgba(255,255,255,0.72);">✅ Qatar Based</span>
            </div>

        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
