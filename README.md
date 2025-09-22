
# 📈 Monitoring Suhu & Kecerahan dengan ESP32

This project is an implementation of a simple IoT Monitoring System using an ESP32 as a client to read data from a DHT11 sensor (temperature & humidity) and an LDR sensor (light intensity). The sensor data is sent to a MySQL database through a PHP API running on XAMPP, and then displayed in a web dashboard for real-time monitoring


## 📊 Features

- Temperature and humidity monitoring using DHT11
- Light intensity monitoring (Dark, Dim, Bright, Very Bright) using LDR
- ESP32 as a WiFi client sending sensor data to the server
- Data storage in MySQL Database
- PHP API for communication between ESP32 and MySQL
- Web dashboard with auto-refresh to display data in tables and charts


## ⚙️ Technologies Used

- ESP32 (Arduino IDE)
- DHT11 & LDR sensors
- PHP & MySQL (XAMPP)
- HTML, CSS, JavaScript (jQuery & Chart.js)


## 🔧 Installation

ESP32 Setup

```bash
  install Arduino IDE
```
- Add ESP32 board support in Arduino IDE
- Install the required libraries:

```bash
  DHT sensor library
  WiFi.h
  WebServer.h
```
- Flash the ESP32 with the provided Arduino code

Server Setup (using XAMPP)
- Install XAMPP
- Start Apache and MySQL
- Create a database

```bash
  CREATE DATABASE monitoring_sensor;
```
- Import the provided SQL file to create the required tables

PHP API Setup
- Place the PHP files in the htdocs directory of XAMPP
- Clone this projects

```bash
  git clone https://github.com/RevanandaXD/Dashboard_Monitoring_Sensor.git
```
Access Dashboard
- Open http://localhost/your_project_folder/dashboard.php in your browser

    
## Screenshots

![App Screenshot](https://i.postimg.cc/L6BbVvM5/image.png)


## Support

For support, email revanandaislamipasha@gmail.com or Follow my Instagram account at Revananda2006

