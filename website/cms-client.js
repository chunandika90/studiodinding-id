// Fetch wrapper for new.studiodinding.id talking to its own same-origin
// api/public/*.php. Every call resolves to `null` on failure instead of
// throwing, so callers can just do:
//   CMS.getHomepage().then((data) => { if (data) this.setState(...); });
// and keep whatever hardcoded default was already in state if the API
// is unreachable.
window.CMS = {
  getHomepage() {
    return fetch('api/public/homepage.php')
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getProject(slug) {
    return fetch('api/public/project.php?slug=' + encodeURIComponent(slug))
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getTeam() {
    return fetch('api/public/team.php')
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getAboutHero() {
    return fetch('api/public/about.php')
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getContactInfo() {
    return fetch('api/public/contact-info.php')
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getBlogList() {
    return fetch('api/public/blog.php')
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  getBlogPost(slug) {
    return fetch('api/public/blog-post.php?slug=' + encodeURIComponent(slug))
      .then((r) => (r.ok ? r.json() : null))
      .catch(() => null);
  },
  submitContact(data) {
    // multipart, not JSON — data.attachment (if present) is a File object.
    const fd = new FormData();
    fd.append('name', data.name || '');
    fd.append('email', data.email || '');
    fd.append('message', data.message || '');
    if (data.attachment) fd.append('attachment', data.attachment);
    return fetch('api/public/contact.php', { method: 'POST', body: fd })
      .then((r) => r.json().then((body) => ({ ok: r.ok, ...body })))
      .catch(() => ({ ok: false, error: 'Network error.' }));
  },
};
