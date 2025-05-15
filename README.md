# 🐝 BeeMo: IoT-Enabled Stingless Beehive Monitoring System

BeeMo is an IoT-based, web-enabled monitoring and management system designed specifically for **Tetragonula biroi**, a stingless bee species native to the Philippines. It aims to modernize beekeeping by enhancing honey production, automating environmental monitoring, and providing real-time data access through a user-friendly web interface.

---

## 📌 Table of Contents

- [Features](#features)
- [System Overview](#system-overview)
- [Hardware Components](#hardware-components)
- [Software Components](#software-components)
- [Parameter Ranges](#parameter-ranges)
- [System Users](#system-users)
- [Web Interface Features](#web-interface-features)
- [Offline Functionality](#offline-functionality)
- [Installation & Setup](#installation--setup)
- [License](#license)

---

## ✅ Features

- Real-time monitoring of **temperature**, **humidity**, and **hive weight**
- Automated regulation of environmental parameters
- Web and **SMS notifications** for anomalies or harvest alerts
- Historical data analysis with **descriptive analytics**
- Multi-hive and multi-user management with role-based access
- Harvest cycle tracking and notifications
- Offline temperature/humidity control during internet downtime

---

## 🌐 System Overview

BeeMo integrates IoT hardware with a web system to:

- **Monitor**: Continuously track hive conditions using sensors.
- **Analyze**: Visualize and interpret historical trends.
- **Notify**: Alert users via SMS and web notifications when needed.
- **Control**: Automatically adjust hive temperature and humidity.

---

## 🔧 Hardware Components

| Component         | Description                                     |
|-------------------|-------------------------------------------------|
| Arduino Uno       | Microcontroller for sensor control              |
| NodeMCU ESP8266   | Wi-Fi module for internet communication         |
| DHT22             | Temperature and humidity sensor                 |
| Load Cell + HX711 | Weight sensor module                            |
| PTC Heater + Fan  | For heating when temperature is too low         |
| TEC-12706 + Fan   | For cooling during excessive heat               |

> DC fans operate at 3500 RPM and 40 dB to avoid disturbing the bees.

---

## 🖥️ Software Components

- **Frontend**: HTML, CSS, Bootstrap, JavaScript, Chart.js
- **Backend**: PHP
- **Authentication**: PHPmailer
- **Notifications**: Infobip API for SMS
- **Database**: MySQL (for sensor readings and user data)
- **Testing**: PHPPest

---

## 📊 Parameter Ranges

| Parameter   | Recommended Range                 |
|-------------|-----------------------------------|
| Temperature | 32°C – 35°C                        |
| Humidity    | 50% – 60%                          |
| Hive Weight | 1–5 kg (recommended for harvesting)|

---

## 👥 System Users

- **Administrator**: Full access, including reports, harvest cycles, and worker management.
- **Worker/User**: Can access all features except Reports and Worker sections.

---

## 🌐 Web Interface Features

1. **Home** – Overview of the BeeMo project
2. **Choose Hive** – Select and manage specific hives
3. **Parameters Monitoring** – View real-time values with color indicators
4. **Reports** – Graphs and analytics with cycle/date filters
5. **Harvest Cycle** – Record and manage 6-month harvest cycles
6. **Bee Guide** – Tutorial and device overview
7. **Worker Management** – Admin-only feature to manage users

### Color Indicator System

- 🟢 Green: Optimal range  
- 🔴 Red: Above range  
- 🔵 Blue: Below range

---

## 📴 Offline Functionality

- Continues regulating temperature and humidity
- Data is not displayed or logged until reconnection
- Use routers, hotspots, or Wi-Fi extenders with the same SSID/password for seamless switching

---

## ⚙️ Installation & Setup

1. Clone the repository
2. Set up MySQL and import the provided database schema
3. Configure database credentials in `config/db.php`
4. Upload the project to a web server (e.g., XAMPP, live host)
5. Connect the IoT hardware (Arduino + ESP8266)
6. Register users via the admin dashboard
7. Start monitoring and managing your beehives!

---

## 📄 License

This project was developed for academic and research purposes. For commercial use, please contact the author for licensing information.

---

> For questions or setup assistance, please reach out to the project team.
