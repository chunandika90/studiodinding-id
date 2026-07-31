-- Incremental addition — run this via phpMyAdmin (SQL tab). Do NOT re-run schema.sql.

ALTER TABLE contact_submissions
  ADD COLUMN status ENUM('new','in_progress','closed') NOT NULL DEFAULT 'new' AFTER message;
