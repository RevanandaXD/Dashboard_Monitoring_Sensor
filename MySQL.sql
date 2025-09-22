CREATE DATABASE monitoring_sensor;

-- Tabel untuk sensor_suhu
CREATE TABLE sensor_suhu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ts DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  temperature DECIMAL(5,2) NOT NULL,
  humidity DECIMAL(5,2) NOT NULL
);

-- Tabel untuk sensor_lumen
CREATE TABLE sensor_lumen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ts DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ldr_raw INT NOT NULL,
  kondisi VARCHAR(16) NOT NULL
);
