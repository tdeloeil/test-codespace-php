USE database;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, created_at) VALUES
    ('Alice Dupont', 'alice@example.com', NOW()),
    ('Bob Martin', 'bob@example.com', NOW()),
    ('Charlie Durand', 'charlie@example.com', DATE_SUB(NOW(), INTERVAL 2 DAY))
ON DUPLICATE KEY UPDATE name = VALUES(name);