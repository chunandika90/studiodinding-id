-- Incremental addition — run this ONE separately via phpMyAdmin (Import or the SQL tab).
-- Do NOT re-run the full schema.sql — it DROPs and recreates every table, which
-- would wipe out all the content you've already migrated into the database.

CREATE TABLE IF NOT EXISTS about_hero (
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
  headline VARCHAR(255) NOT NULL DEFAULT '',
  intro_quote TEXT NOT NULL,
  img VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO about_hero (id, headline, intro_quote, img) VALUES
  (1,
   'The people behind every space we shape.',
   'A small, senior studio — architecture, interiors and construction working as one team, from first sketch to final handover.',
   'https://new.studiodinding.id/assets/img/about.jpg');
