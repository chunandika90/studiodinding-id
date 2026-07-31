<?php
/**
 * Same page as before (blog-post.dc.html), served through PHP so the
 * <title>/meta description/Open Graph tags are filled in per-article BEFORE
 * any JavaScript runs — see project.php for the full rationale (same pattern).
 */
require __DIR__ . '/../shared/config.php';
header('Content-Type: text/html; charset=utf-8');

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare(
    'SELECT title, excerpt, seo_title AS seoTitle, seo_description AS seoDescription, cover_img AS coverImg
     FROM blog_posts WHERE slug = ? AND published = 1'
);
$stmt->execute([$slug]);
$post = $stmt->fetch();

if ($post) {
    $pageTitle = $post['seoTitle'] !== null && $post['seoTitle'] !== ''
        ? $post['seoTitle']
        : $post['title'] . ' — Studio Dinding Journal';
    $rawDescription = $post['seoDescription'] !== null && $post['seoDescription'] !== ''
        ? $post['seoDescription']
        : ($post['excerpt'] !== '' ? $post['excerpt'] : 'Artikel dari Studio Dinding — architecture & interior design studio.');
    $pageImage = $post['coverImg'] !== '' ? $post['coverImg'] : (SITE_URL . '/assets/img/favicon.png');
} else {
    $pageTitle = 'Article Not Found — Studio Dinding Journal';
    $rawDescription = 'Studio Dinding — architecture & interior design studio based in Jakarta.';
    $pageImage = SITE_URL . '/assets/img/favicon.png';
}
$pageDescription = mb_substr(trim($rawDescription), 0, 160);
$pageUrl = SITE_URL . '/journal/' . urlencode($slug);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<base href="/">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<link rel="canonical" href="<?= htmlspecialchars($pageUrl) ?>">
<meta property="og:type" content="article">
<meta property="og:site_name" content="Studio Dinding">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta property="og:image" content="<?= htmlspecialchars($pageImage) ?>">
<meta property="og:url" content="<?= htmlspecialchars($pageUrl) ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<script src="./support.js"></script>
<script src="./cms-client.js"></script>
<script>window.__PAGE_SLUG__ = <?= json_encode($slug) ?>;</script>
</head>
<body>
<x-dc>
<helmet>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(28px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    [data-reveal-id] { animation: fadeInUp 1s cubic-bezier(.2,.8,.2,1) both; }
    html { scroll-behavior: smooth; }
    body { margin: 0; background: #0a0a09; }
    ::selection { background: #b89159; color: #0a0a09; }
    .post-body p { margin: 0 0 22px; }
  </style>
</helmet>
<div style="background:#0a0a09; color:oklch(0.95 0.01 85); font-family:'Inter',sans-serif; overflow-x:hidden; min-height:100vh;">

  <nav style="position:fixed; top:0; left:0; right:0; z-index:100; display:flex; align-items:center; justify-content:space-between; padding:28px 5vw; background:linear-gradient(to bottom, rgba(0,0,0,0.55), rgba(0,0,0,0));">
    <a href="/" style="display:block;"><img src="assets/img/sd-logo-white.png" alt="Studio Dinding" style="height:56px; width:auto; display:block;"></a>
    <div style="display:flex; gap:40px; align-items:center;">
      <a href="/#projects" style="color:oklch(0.9 0.01 85); text-decoration:none; font-size:13px; letter-spacing:2px; font-weight:400;" style-hover="color:#c9a15c;">PORTFOLIO</a>
      <a href="about" style="color:oklch(0.9 0.01 85); text-decoration:none; font-size:13px; letter-spacing:2px; font-weight:400;" style-hover="color:#c9a15c;">ABOUT</a>
      <a href="journal" style="color:#c9a15c; text-decoration:none; font-size:13px; letter-spacing:2px; font-weight:400;">JOURNAL</a>
      <a href="/#contact" style="color:oklch(0.9 0.01 85); text-decoration:none; font-size:13px; letter-spacing:2px; font-weight:400; border:1px solid oklch(0.6 0.05 75); padding:10px 22px;" style-hover="color:#0a0a09; background:#c9a15c; border-color:#c9a15c;">CONTACT</a>
    </div>
  </nav>

  <sc-if value="{{ loading }}" hint-placeholder-val="{{ false }}">
    <div style="min-height:100vh; display:flex; align-items:center; justify-content:center; font-size:13px; letter-spacing:1.5px; color:oklch(0.6 0.02 85);">LOADING…</div>
  </sc-if>

  <sc-if value="{{ notFound }}" hint-placeholder-val="{{ false }}">
    <div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:5vw;">
      <div style="font-family:'Playfair Display',serif; font-size:clamp(28px,4vw,44px); margin-bottom:20px;">Article not found</div>
      <a href="journal" style="color:#c9a15c; text-decoration:none; font-size:13px; letter-spacing:2px;">← BACK TO JOURNAL</a>
    </div>
  </sc-if>

  <sc-if value="{{ loaded }}" hint-placeholder-val="{{ false }}">

  <section style="position:relative; height:60vh; min-height:420px; display:flex; align-items:flex-end; overflow:hidden;">
    <div style="{{ heroBgStyle }}position:absolute; inset:0; background-size:cover; background-position:center;"></div>
    <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(10,10,9,0.95) 0%, rgba(10,10,9,0.2) 55%, rgba(10,10,9,0.35) 100%);"></div>
    <div data-reveal-id="hero" style="position:relative; padding:140px 5vw 70px; max-width:800px; box-sizing:border-box;">
      <div style="font-size:13px; letter-spacing:4px; color:#c9a15c; margin-bottom:20px;">{{ post.publishedAt }}</div>
      <h1 style="font-family:'Playfair Display',serif; font-weight:400; font-size:clamp(32px,5vw,58px); line-height:1.1; margin:0;">{{ post.title }}</h1>
    </div>
  </section>

  <section style="padding:80px 5vw 60px; max-width:760px; margin:0 auto;">
    <div class="post-body" style="font-size:16px; line-height:1.9; color:oklch(0.85 0.01 85); font-weight:300;">
      <sc-for list="{{ bodyParagraphs }}" as="para" hint-placeholder-count="4">
        <p>{{ para }}</p>
      </sc-for>
    </div>
  </section>

  <section style="position:relative; height:50vh; min-height:340px; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-top:40px;">
    <div style="{{ nextBgStyle }}position:absolute; inset:0; background-size:cover; background-position:center; opacity:0.6;"></div>
    <div style="position:absolute; inset:0; background:rgba(10,10,9,0.5);" style-hover="background:rgba(10,10,9,0.3);"></div>
    <a href="{{ nextHref }}" style="position:relative; text-align:center; text-decoration:none; color:oklch(0.95 0.01 85);">
      <div style="font-size:12px; letter-spacing:2px; color:#c9a15c; margin-bottom:14px;">NEXT ARTICLE</div>
      <div style="font-family:'Playfair Display',serif; font-size:clamp(26px,4vw,44px);">{{ nextTitle }} →</div>
    </a>
  </section>

  </sc-if>

  <footer style="padding:34px 5vw; display:flex; justify-content:space-between; align-items:center; border-top:1px solid rgba(255,255,255,0.1); flex-wrap:wrap; gap:16px;">
    <div style="font-size:12px; color:oklch(0.55 0.02 85);">© 2026 Studio Dinding. All rights reserved.</div>
    <a href="https://www.instagram.com/studio.dinding" target="_blank" style="color:oklch(0.75 0.02 85); text-decoration:none; font-size:12px; letter-spacing:1.5px;" style-hover="color:#c9a15c;">INSTAGRAM ↗</a>
  </footer>

  <div style="position:fixed; right:28px; bottom:28px; z-index:200; display:flex; flex-direction:column; align-items:center; gap:14px;">
    <a href="https://www.instagram.com/studio.dinding" target="_blank" title="Instagram" style="display:flex; align-items:center; justify-content:center; width:46px; height:46px; border-radius:50%; border:1px solid rgba(242,236,225,0.35); background:rgba(10,10,9,0.55); backdrop-filter:blur(6px); transition:border-color 0.3s ease, transform 0.3s ease;" style-hover="border-color:#c9a15c; transform:translateY(-2px);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f2ece1" stroke-width="1.4"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4.2"></circle><circle cx="17.2" cy="6.8" r="0.9" fill="#f2ece1" stroke="none"></circle></svg>
    </a>
    <a href="https://wa.me/6281289795996?text=Hello%20Studio%20Dinding%2C%20saya%20tertarik%20dengan%20layanan%20Anda" target="_blank" title="WhatsApp" style="display:flex; align-items:center; justify-content:center; width:58px; height:58px; border-radius:50%; background:#c9a15c; box-shadow:0 8px 24px rgba(0,0,0,0.4); transition:background 0.3s ease, transform 0.3s ease;" style-hover="background:#f2ece1; transform:translateY(-2px);">
      <svg width="24" height="24" viewBox="0 0 32 32" fill="#0a0a09"><path d="M16.02 4C9.4 4 4 9.4 4 16.02c0 2.12.56 4.14 1.6 5.94L4 28l6.2-1.56a11.94 11.94 0 0 0 5.82 1.5h.01c6.63 0 12.02-5.4 12.02-12.02C28.05 9.4 22.65 4 16.02 4zm0 21.86h-.01a9.9 9.9 0 0 1-5.05-1.38l-.36-.21-3.68.93.98-3.58-.24-.37a9.85 9.85 0 0 1-1.51-5.23c0-5.46 4.45-9.9 9.92-9.9 2.65 0 5.13 1.03 7 2.9a9.83 9.83 0 0 1 2.9 7c0 5.47-4.45 9.84-9.95 9.84zm5.44-7.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.24-.46-2.37-1.47-.87-.78-1.46-1.74-1.63-2.04-.17-.3-.02-.46.13-.6.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48s1.07 2.87 1.22 3.07c.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35z"></path></svg>
    </a>
  </div>

</div>

</x-dc>
<script type="text/x-dc" data-dc-script>
class Component extends DCLogic {
  state = { loading: true, notFound: false };
  post0 = {};
  nextPost0 = null;

  get slug() {
    return window.__PAGE_SLUG__ || new URLSearchParams(location.search).get('slug') || '';
  }

  componentDidMount() {
    if (!this.slug) {
      this.setState({ loading: false, notFound: true });
      return;
    }
    CMS.getBlogPost(this.slug).then((data) => {
      if (data && !data.error) {
        this.post0 = data;
        this.nextPost0 = data.nextPost || null;
        this.setState({ loading: false, notFound: false });
      } else {
        this.setState({ loading: false, notFound: true });
      }
    }).catch(() => {
      this.setState({ loading: false, notFound: true });
    });
  }

  renderVals() {
    const body = this.post0.body || '';
    return {
      loading: this.state.loading,
      notFound: this.state.notFound,
      loaded: !this.state.loading && !this.state.notFound,
      post: this.post0,
      bodyParagraphs: body.split(/\n\s*\n/).map((p) => p.trim()).filter(Boolean),
      heroBgStyle: `background-image:url('${this.post0.coverImg || ''}');`,
      nextBgStyle: `background-image:url('${this.post0.coverImg || ''}');`,
      nextHref: this.nextPost0 ? 'journal/' + encodeURIComponent(this.nextPost0.slug) : 'journal',
      nextTitle: this.nextPost0 ? this.nextPost0.title : 'Back to Journal',
    };
  }
}

</script>
</body>
</html>
