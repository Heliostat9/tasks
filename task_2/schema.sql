-- Справочник кампаний
CREATE TABLE campaigns (
   id          INTEGER PRIMARY KEY,
   name        VARCHAR(255) NOT NULL,
   created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Статистика по дням
CREATE TABLE stats (
   id           INTEGER PRIMARY KEY AUTOINCREMENT,
   campaign_id  INTEGER NOT NULL REFERENCES campaigns(id),
   stat_date    DATE NOT NULL,
   clicks       INTEGER DEFAULT 0,
   conversions  INTEGER DEFAULT 0,
   payout       DECIMAL(10,2) DEFAULT 0,
   UNIQUE(campaign_id, stat_date)
);

CREATE INDEX idx_stats_campaign_date ON stats(campaign_id, stat_date);

-- Тестовые данные
INSERT INTO campaigns(id, name) VALUES
(1, 'Campaign A'),
(2, 'Campaign B');

INSERT INTO stats(campaign_id, stat_date, clicks, conversions, payout) VALUES
(1, '2024-06-01', 1000, 50, 123.45),
(1, '2024-06-02', 1500, 70, 150.00),
(2, '2024-06-01', 500, 20, 50.00);