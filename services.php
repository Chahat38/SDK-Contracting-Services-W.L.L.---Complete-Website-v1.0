<?php
require_once 'includes/db.php';
$page_title = 'Services';
$services = db()->query(
    "SELECT * FROM services WHERE status='active' ORDER BY sort_order ASC"
)->fetchAll();
require_once 'includes/header.php';
?>

<div class="page-hero">
    <div class="container">
        <div class="section-label">What We Do</div>
        <h1>Our Services</h1>
        <p>Comprehensive construction and advisory solutions tailored to Qatar's unique market</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if (empty($services)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🔧</div>
                <h3>No Services Listed</h3>
                <p>Services will appear here once added from the admin panel.</p>
            </div>
        <?php else: ?>
        <div class="services-grid">
            <?php foreach ($services as $s): ?>
            <div class="service-card">
                <div class="service-card-img">
                   <?php if (!empty($s['image']) && file_exists(__DIR__ . '/uploads/services/' . $s['image'])): ?>
                        <img src="/uploads/services/<?= htmlspecialchars($s['image']) ?>"
                             alt="<?= htmlspecialchars($s['title']) ?>"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div class="service-card-img-placeholder" style="display:none;"><?= htmlspecialchars($s['icon'] ?? '🔧') ?></div>
                    <?php else: ?>
                        <div class="service-card-img-placeholder"><?= htmlspecialchars($s['icon'] ?? '🔧') ?></div>
                    <?php endif; ?>
                    <div class="service-card-img-overlay"></div>
                </div>
                <div class="service-card-content">
                    <h3><?= htmlspecialchars($s['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($s['description'] ?? '')) ?></p>
                    <a href="/contact?service=<?= urlencode($s['title']) ?>" class="service-card-link">Get a Quote →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->

<!-- ── DIGITAL SERVICES ── -->
<section class="digital-services-section" id="digital">
    <div class="container">
        <div style="text-align:center; max-width:700px; margin:0 auto;">
            <div class="section-label" style="justify-content:center;">Digital & Technology</div>
            <h2 class="section-title">Professional Digital Services<br>for Qatar Businesses</h2>
            <p style="color:var(--muted); font-size:16px; line-height:1.8; margin-bottom:0;">
                SDK offers expert digital services alongside our physical contracting work. Whether you need a professional website, a mobile app, or better Google rankings  we build digital solutions that grow your business.
            </p>
        </div>

        <div class="digital-services-grid">

            <!-- Web Development -->
            <div class="digital-service-card">
                <div class="digital-service-card-icon">🌐</div>
                <h3>Web Development</h3>
                <p>Professional, fast, and mobile-friendly websites built to represent your business online and convert visitors into paying clients.</p>
                <ul class="digital-service-features">
                    <li>Business & corporate websites</li>
                    <li>E-commerce online stores</li>
                    <li>Custom web applications</li>
                    <li>Admin panels & dashboards</li>
                    <li>Landing pages & portfolios</li>
                    <li>Website maintenance & updates</li>
                </ul>
                <a href="/contact?service=Web+Development" class="btn btn-primary">Get a Quote →</a>
            </div>

            <!-- App Development -->
            <div class="digital-service-card">
                <div class="digital-service-card-icon">📱</div>
                <h3>App Development</h3>
                <p>Android and iOS mobile apps designed for performance, usability, and business growth. From concept to launch, we handle everything.</p>
                <ul class="digital-service-features">
                    <li>Android mobile apps</li>
                    <li>iOS mobile apps</li>
                    <li>Cross-platform apps</li>
                    <li>Business & service apps</li>
                    <li>App UI/UX design</li>
                    <li>App maintenance & support</li>
                </ul>
                <a href="/contact?service=App+Development" class="btn btn-primary">Get a Quote →</a>
            </div>

            <!-- SEO -->
            <div class="digital-service-card">
                <div class="digital-service-card-icon">🔍</div>
                <h3>SEO Services</h3>
                <p>Rank higher on Google and get found by clients actively searching for your services in Qatar. Proven SEO strategies that deliver real results.</p>
                <ul class="digital-service-features">
                    <li>Google ranking optimization</li>
                    <li>On-page SEO & content</li>
                    <li>Local SEO for Qatar</li>
                    <li>Technical SEO audit</li>
                    <li>Keyword research & strategy</li>
                    <li>Monthly SEO reports</li>
                </ul>
                <a href="/contact?service=SEO+Services" class="btn btn-primary">Get a Quote →</a>
            </div>

        </div>

        <!-- Process Steps -->
        <div class="digital-process">
            <div style="text-align:center;">
                <div class="section-label" style="justify-content:center;">How It Works</div>
                <h2 class="section-title" style="font-size:clamp(1.6rem,3vw,2.2rem);">Our Simple 4-Step Process</h2>
            </div>
            <div class="digital-process-steps">
                <div class="process-step">
                    <div class="process-step-num">01</div>
                    <h4>Free Consultation</h4>
                    <p>Tell us your goals. We listen, understand your needs, and suggest the best solution.</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">02</div>
                    <h4>Proposal & Quote</h4>
                    <p>We send you a clear proposal with timeline, cost, and deliverables  no surprises.</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">03</div>
                    <h4>Development</h4>
                    <p>Our team builds your project with regular updates and your feedback at every stage.</p>
                </div>
                <div class="process-step">
                    <div class="process-step-num">04</div>
                    <h4>Launch & Support</h4>
                    <p>We launch your project and provide ongoing support to keep everything running smoothly.</p>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="cta-section" style="background:linear-gradient(135deg,#7C3AED 0%,#4F46E5 60%,#4338CA 100%);">
    <div class="cta-bg"></div>
    <div class="container cta-inner">
        <div class="cta-text">
            <div class="section-label" style="justify-content:flex-start;color:rgba(255,255,255,0.8);">
                Custom Solutions
            </div>

            <h2 class="section-title" style="margin-bottom:12px;color:#FFFFFF;">
                Need Something<br>Specific?
            </h2>

            <p style="color:rgba(255,255,255,0.82); font-size:16px; line-height:1.8;">
                Every project is unique. Tell us your requirements and we'll tailor the perfect solution.
            </p>
        </div>

        <div class="cta-actions">
            <a href="/contact" class="btn btn-lg"
               style="background:#FFFFFF;color:#4F46E5;border-color:#FFFFFF;">
               Request a Consultation →
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
