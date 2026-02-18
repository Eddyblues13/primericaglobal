-- Tesla Investment Plans — schema + seed (SQL)
-- MySQL / MariaDB. If you use Laravel migration, skip the ALTER block and run only DELETE + INSERT.

-- ========== SCHEMA: add new columns (skip if migration already ran) ==========
ALTER TABLE investment_plans
    ADD COLUMN max_investment DECIMAL(16,2) NULL AFTER min_investment,
    ADD COLUMN profit_margin DECIMAL(6,2) NOT NULL DEFAULT 0 AFTER max_investment,
    ADD COLUMN duration_days INT UNSIGNED NOT NULL DEFAULT 0 AFTER profit_margin,
    ADD COLUMN duration_label VARCHAR(64) NULL AFTER duration_days;

-- ========== SEED: replace plans with 4 Tesla plans ==========
-- WARNING: This removes ALL existing investment plans and user investments!
-- Disable foreign key checks for clean truncate
SET FOREIGN_KEY_CHECKS = 0;

-- Clear all data (truncate resets auto-increment automatically)
TRUNCATE TABLE user_investments;
TRUNCATE TABLE investment_plans;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Insert the four Tesla plans
INSERT INTO investment_plans (
    name, slug, category, strategy, risk_level, nav, one_year_return,
    min_investment, max_investment, profit_margin, duration_days, duration_label,
    is_featured, display_order, created_at, updated_at
) VALUES
('STARTER PLAN', 'starter', 'Tesla', 'Tesla Investment', 'medium', 0, 20,
 2000, 9999, 20, 2, '2 days', 1, 1, NOW(), NOW()),

('MEGA PLAN', 'mega', 'Tesla', 'Tesla Investment', 'medium', 0, 20,
 10000, 39999, 20, 4, '4 days', 1, 2, NOW(), NOW()),

('GRAND PLAN', 'grand', 'Tesla', 'Tesla Investment', 'high', 0, 30,
 40000, 99999, 30, 7, '7 days', 1, 3, NOW(), NOW()),

('VIP PLAN', 'vip', 'Tesla', 'Tesla Investment', 'high', 0, 50,
 999000, NULL, 50, 30, '1 month', 1, 4, NOW(), NOW());
