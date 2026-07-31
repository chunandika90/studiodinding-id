-- Incremental addition — run via phpMyAdmin (SQL tab). Do NOT re-run schema.sql.

CREATE TABLE IF NOT EXISTS contact_info (
  id TINYINT UNSIGNED NOT NULL PRIMARY KEY DEFAULT 1,
  whatsapp VARCHAR(50) NOT NULL DEFAULT '',
  email VARCHAR(255) NOT NULL DEFAULT '',
  address VARCHAR(500) NOT NULL DEFAULT '',
  instagram_url VARCHAR(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO contact_info (id, whatsapp, email, address, instagram_url) VALUES
  (1,
   '+62 812-8979-5996',
   'studiodinding@gmail.com',
   'Jl. Tanjung Duren Barat IV No.22A, Jakarta Barat 11470',
   'https://www.instagram.com/studio.dinding');
