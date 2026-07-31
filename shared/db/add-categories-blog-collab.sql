-- Incremental additions — run via phpMyAdmin SQL tab. Do NOT re-run schema.sql.

-- 1. Master project categories (replaces hardcoded residential/commercial)
CREATE TABLE IF NOT EXISTS project_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO project_categories (id, name, slug, sort_order) VALUES
  (1, 'Residential', 'residential', 1),
  (2, 'Commercial', 'commercial', 2);

-- If this errors with "Duplicate column name", the column already exists — skip this ALTER and continue.
ALTER TABLE projects
  ADD COLUMN category_id INT UNSIGNED NULL AFTER type;

UPDATE projects SET category_id = 1 WHERE type = 'residential' AND category_id IS NULL;
UPDATE projects SET category_id = 2 WHERE type = 'commercial' AND category_id IS NULL;

-- 2. Blog / articles
CREATE TABLE IF NOT EXISTS blog_posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(150) NOT NULL,
  title VARCHAR(255) NOT NULL,
  excerpt VARCHAR(500) NOT NULL DEFAULT '',
  body MEDIUMTEXT NOT NULL,
  cover_img VARCHAR(255) NOT NULL DEFAULT '',
  published TINYINT(1) NOT NULL DEFAULT 1,
  published_at DATE NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Collaborators (partner/brand logos on the homepage)
CREATE TABLE IF NOT EXISTS collaborators (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  logo_img VARCHAR(255) NOT NULL,
  url VARCHAR(255) NOT NULL DEFAULT '',
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Ticket attachment. If this errors with "Duplicate column name", it already exists — skip and continue.
ALTER TABLE contact_submissions
  ADD COLUMN attachment VARCHAR(255) NULL AFTER message;
