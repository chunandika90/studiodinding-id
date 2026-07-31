// Fetch wrapper for cms.studiodinding.id talking to its own same-origin
// api/admin/*.php (session-cookie auth). Mirrors the shape the dashboard
// script already expects: CMS.admin.me(), CMS.admin.slides.list(), etc.
window.CMS = {
  admin: {
    me() {
      return fetch('api/admin/me.php').then((r) => r.json()).catch(() => ({ loggedIn: false }));
    },
    login(username, password) {
      return fetch('api/admin/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body })));
    },
    logout() {
      return fetch('api/admin/logout.php', { method: 'POST' }).then((r) => r.json());
    },
    upload(file) {
      const fd = new FormData();
      fd.append('file', file);
      return fetch('api/admin/upload.php', { method: 'POST', body: fd })
        .then((r) => r.json().then((body) => ({ ok: r.ok, ...body })));
    },
    _crud(endpoint) {
      return {
        list: (query) => fetch('api/admin/' + endpoint + (query ? '?' + query : ''))
          .then((r) => r.json()),
        create: (data) => fetch('api/admin/' + endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
        update: (id, data) => fetch('api/admin/' + endpoint + '?id=' + id, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
        remove: (id) => fetch('api/admin/' + endpoint + '?id=' + id, { method: 'DELETE' })
          .then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
      };
    },
    get slides() { return this._crud('slides.php'); },
    get projects() { return this._crud('projects.php'); },
    get team() { return this._crud('team.php'); },
    get submissions() { return this._crud('submissions.php'); },
    get admins() { return this._crud('admins.php'); },
    get categories() { return this._crud('categories.php'); },
    get collaborators() { return this._crud('collaborators.php'); },
    get blog() { return this._crud('blog.php'); },
    about: {
      get: () => fetch('api/admin/about.php').then((r) => r.json()),
      update: (data) => fetch('api/admin/about.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
    },
    contactInfo: {
      get: () => fetch('api/admin/contact-info.php').then((r) => r.json()),
      update: (data) => fetch('api/admin/contact-info.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
    },
    migrate(module, confirm) {
      return fetch('api/admin/migrate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ module, confirm: !!confirm }),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body })));
    },
    gallery: {
      list: (projectId) => fetch('api/admin/project-gallery.php?projectId=' + projectId).then((r) => r.json()),
      create: (data) => fetch('api/admin/project-gallery.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
      update: (id, data) => fetch('api/admin/project-gallery.php?id=' + id, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      }).then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
      remove: (id) => fetch('api/admin/project-gallery.php?id=' + id, { method: 'DELETE' })
        .then((r) => r.json().then((body) => ({ ok: r.ok, ...body }))),
    },
  },
};
