<?php
require_once 'includes/db.php';
$page_title = 'Construction, Cleaning & Services in Qatar';
$featured = db()->query("SELECT * FROM projects WHERE status='active' ORDER BY created_at DESC LIMIT 3")->fetchAll();
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid-lines"></div>
    <div class="hero-content">
        <div class="hero-badge"> &nbsp;<?= htmlspecialchars(setting('hero_badge', "Qatar's Trusted Contracting & Services Partner")) ?></div>
        <h1 class="hero-title">
            <?= htmlspecialchars(setting('hero_title_line1', "Qatar's Leading")) ?>
            <span><?= htmlspecialchars(setting('hero_title_line2', 'Contracting Company')) ?></span>
        </h1>
        <p class="hero-subtitle"><?= htmlspecialchars(setting('hero_subtitle', 'From villa construction to road asphalt, cleaning services, legal advisory, and building materials supply  SDK delivers quality across Qatar.')) ?></p>
        <div class="hero-actions">
            <a href="/projects" class="btn btn-primary">View Our Projects →</a>
            <a href="/contact" class="btn btn-outline">Get a Free Quote</a>
        </div>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-num" data-counter="<?= setting('stat_1_num','1') ?>"><?= setting('stat_1_num','1') ?>+</div>
                <div class="stat-label"><?= htmlspecialchars(setting('stat_1_label','Years Active')) ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-num" data-counter="<?= setting('stat_2_num','50') ?>"><?= setting('stat_2_num','50') ?>+</div>
                <div class="stat-label"><?= htmlspecialchars(setting('stat_2_label','Projects Completed')) ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-num" data-counter="<?= setting('stat_3_num','8') ?>"><?= setting('stat_3_num','8') ?>+</div>
                <div class="stat-label"><?= htmlspecialchars(setting('stat_3_label','Services Offered')) ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-num" data-counter="<?= setting('stat_4_num','30') ?>"><?= setting('stat_4_num','30') ?>+</div>
                <div class="stat-label"><?= htmlspecialchars(setting('stat_4_label','Happy Clients')) ?></div>
            </div>
        </div>
    </div>
    <div class="hero-bg">
    <img src="/images/Hero.jpg"
         alt="SDK Qatar"
         loading="eager"
         onerror="this.style.display='none'">
</div>
</section>

<section class="section" style="background:var(--dark-2);">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label">Portfolio</div>
                <h2 class="section-title">Featured Projects</h2>
                <p class="section-subtitle">Recently completed work delivered across Qatar  on time and within budget.</p>
            </div>
            <a href="/projects" class="btn btn-outline section-header-btn">View All Projects →</a>
        </div>
        <?php if (empty($featured)): ?>
            <p style="color:var(--muted);">No projects yet. Check back soon.</p>
        <?php else: ?>
        <div class="projects-grid">
            <?php
           foreach ($featured as $i => $p): ?>
<div class="project-card">
    <div class="project-card-img">
        <?php if ($p['image'] && file_exists(__DIR__ . '/uploads/projects/' . $p['image'])): ?>
            <img src="/uploads/projects/<?= htmlspecialchars($p['image']) ?>"
                 alt="<?= htmlspecialchars($p['title']) ?>"
                 loading="lazy"
                 onerror="this.parentElement.innerHTML='<div style=\'height:240px;display:flex;align-items:center;justify-content:center;font-size:52px;background:#181818;\'>🏗️</div>';">
        <?php else: ?>
            <div style="height:240px;display:flex;align-items:center;justify-content:center;font-size:52px;background:#181818;">🏗️</div>
        <?php endif; ?>
                    <div class="project-card-overlay">
                        <a href="/projects" class="project-card-view-btn">View Project →</a>
                    </div>
                </div>
                <div class="project-card-body">
                    <?php if (!empty($p['category'])): ?>
                    <div class="project-card-tag"><?= htmlspecialchars($p['category']) ?></div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($p['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($p['description'] ?? '', 0, 110)) ?>...</p>
                    <div class="project-card-footer">
                        <span class="project-card-date">📅 <?= date('M Y', strtotime($p['created_at'])) ?></span>
                        <a href="/projects" class="project-card-link">View More →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-label">What We Do</div>
                <h2 class="section-title">Complete Services<br>Across Qatar</h2>
                <p class="section-subtitle">Construction, cleaning, road works, legal advisory, translation  all under one roof.</p>
            </div>
            <a href="/services" class="btn btn-outline section-header-btn">All Services →</a>
        </div>
        <?php $preview_services = db()->query("SELECT * FROM services WHERE status='active' ORDER BY sort_order ASC LIMIT 6")->fetchAll(); ?>
        <div class="services-grid">
            <?php foreach ($preview_services as $s): ?>
            <div class="service-card">
                <div class="service-card-img">
                    <?php if (!empty($s['image']) && file_exists(__DIR__ . '/uploads/services/' . $s['image'])): ?>
                        <img src="/uploads/services/<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['title']) ?>"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div class="service-card-img-placeholder" style="display:none;"><?= htmlspecialchars($s['icon'] ?? '🔧') ?></div>
                    <?php else: ?>
                        <div class="service-card-img-placeholder">🏗️</div>
                    <?php endif; ?>
                    <div class="service-card-img-overlay"></div>
                </div>
                <div class="service-card-content">
                    <h3><?= htmlspecialchars($s['title']) ?></h3>
                    <p><?= htmlspecialchars(substr($s['description'] ?? '', 0, 100)) ?>...</p>
                    <a href="/services" class="service-card-link">Learn More →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg,#7C3AED 0%,#4F46E5 60%,#4338CA 100%);">
    <div class="container">
        <div class="section-label" style="color:rgba(255,255,255,0.8);">Why Choose SDK</div>
        <h2 class="section-title" style="color:#FFFFFF;"><?= htmlspecialchars(setting('why_title','Why Clients Trust SDK in Qatar')) ?></h2>
        <div class="why-grid" style="margin-top:48px;">
            <?php for ($i=1; $i<=4; $i++): ?>
            <div class="why-card" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);">
                <div class="why-card-icon" style="background:rgba(255,255,255,0.15);border-color:rgba(255,255,255,0.25);"><?= htmlspecialchars(setting("why_card_{$i}_icon",'⭐')) ?></div>
                <div class="why-card-body">
                    <h3 style="color:#FFFFFF;"><?= htmlspecialchars(setting("why_card_{$i}_title",'')) ?></h3>
                    <p style="color:rgba(255,255,255,0.78);"><?= htmlspecialchars(setting("why_card_{$i}_desc",'')) ?></p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>


<!-- ── DIGITAL SERVICES SECTION ── -->
<section class="digital-section">
    <div class="container">
        <div class="digital-grid">

            <div class="digital-info">
                <div class="section-label">Digital & Tech</div>
                <h2 class="section-title">We Also Build<br><span style="color:var(--gold)">Digital Products</span></h2>
                <p>Beyond physical construction, SDK offers professional digital services  web development, mobile app development, and SEO. Whether you need a business website, a custom app, or higher Google rankings, we deliver results.</p>
                <div class="digital-badges">
                    <span class="digital-badge">🌐 Web Development</span>
                    <span class="digital-badge">📱 App Development</span>
                    <span class="digital-badge">🔍 SEO Services</span>
                </div>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="/services#digital" class="btn btn-primary">Explore Digital Services →</a>
                    <a href="/contact" class="btn btn-outline">Get a Quote</a>
                </div>
            </div>

            <div class="digital-cards">
                <div class="digital-card featured">
                    <div class="digital-card-icon">🌐</div>
                    <div>
                        <h3>Web Development</h3>
                        <p>Professional business websites, e-commerce stores, and custom web applications. Fast, mobile-friendly, and built to convert visitors into clients.</p>
                        <div class="digital-card-tags">
                            <span class="digital-card-tag">Business Sites</span>
                            <span class="digital-card-tag">E-Commerce</span>
                            <span class="digital-card-tag">Custom Web Apps</span>
                            <span class="digital-card-tag">PHP / MySQL</span>
                        </div>
                    </div>
                </div>
                <div class="digital-card">
                    <div class="digital-card-icon">📱</div>
                    <h3>App Development</h3>
                    <p>Android and iOS mobile apps for businesses, startups, and service companies. Clean UI, fast performance, and scalable architecture.</p>
                    <div class="digital-card-tags">
                        <span class="digital-card-tag">Android</span>
                        <span class="digital-card-tag">iOS</span>
                        <span class="digital-card-tag">Cross-Platform</span>
                    </div>
                </div>
                <div class="digital-card">
                    <div class="digital-card-icon">🔍</div>
                    <h3>SEO Services</h3>
                    <p>Rank higher on Google and get more clients organically. On-page SEO, local SEO, and content strategy tailored for Qatar businesses.</p>
                    <div class="digital-card-tags">
                        <span class="digital-card-tag">On-Page SEO</span>
                        <span class="digital-card-tag">Local SEO</span>
                        <span class="digital-card-tag">Google Ranking</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="cta-section" style="background:linear-gradient(135deg,#7C3AED 0%,#4F46E5 60%,#4338CA 100%);">
    <div class="cta-bg"></div>
    <div class="container cta-inner">
        <div class="cta-text">
            <div class="section-label" style="justify-content:flex-start;color:rgba(255,255,255,0.8);">Get Started Today</div>
            <h2 class="section-title" style="margin-bottom:12px;color:#FFFFFF;">Ready to Start Your<br>Project in Qatar?</h2>
            <p style="color:rgba(255,255,255,0.82);font-size:16px;line-height:1.8;max-width:480px;">
                Contact SDK today for a free consultation. Construction, cleaning, legal advisory, and more  all under one roof.
            </p>
        </div>
        <div class="cta-actions">
            <a href="/contact" class="btn btn-lg" style="background:#FFFFFF;color:#4F46E5;border-color:#FFFFFF;">Get Free Consultation →</a>
            <a href="https://wa.me/97466927592" class="btn btn-lg" style="background:transparent;color:#FFFFFF;border-color:rgba(255,255,255,0.65);" target="_blank">💬 WhatsApp Us</a>
            <div class="cta-trust">
                <span style="color:rgba(255,255,255,0.72);">✅ Free Quote</span>
                <span style="color:rgba(255,255,255,0.72);">✅ 24hr Response</span>
                <span style="color:rgba(255,255,255,0.72);">✅ Qatar Based</span>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
