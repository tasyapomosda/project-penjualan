CREATE TABLE debts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pembeli VARCHAR(255) NOT NULL,
    barang VARCHAR(255) NOT NULL,
    qty INT NOT NULL,
    nominal INT NOT NULL,
    is_paid TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

SELECT * FROM debts LIMIT 1;
SHOW COLUMNS FROM debts;

