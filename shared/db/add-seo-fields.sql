-- Incremental addition — run via phpMyAdmin (SQL tab). Do NOT re-run schema.sql.
-- Optional per-item SEO title/description overrides. NULL/empty = fall back
-- to the project name+quote / post title+excerpt automatically.

ALTER TABLE projects
  ADD COLUMN seo_title VARCHAR(255) NULL AFTER quote,
  ADD COLUMN seo_description VARCHAR(300) NULL AFTER seo_title;

ALTER TABLE blog_posts
  ADD COLUMN seo_title VARCHAR(255) NULL AFTER excerpt,
  ADD COLUMN seo_description VARCHAR(300) NULL AFTER seo_title;
