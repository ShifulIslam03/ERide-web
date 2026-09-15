# E-Ride Services - Eco-friendly E-Bike Rental Platform

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML](https://img.shields.io/badge/HTML-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

**E-Ride Services** is a web-based platform designed to provide a smart, affordable, and eco-friendly transportation solution using e-bikes and scooters. This application targets students and office workers in urban areas like **Bashundhara R/A, Dhaka**. 

The system allows users to rent electric vehicles from designated stations, track their ride time, and pay a transparent fare (**2 Taka per minute with a minimum charge of 3 Taka**). The platform is managed through a centralized web interface with three distinct roles: **Rider (Customer), Manager, and System Admin**.

---

## 📖 Background Study
In fast-growing cities like Dhaka, students, office workers, and ordinary people struggle daily to find good transportation. Current options are often too expensive, unreliable, and harmful to the environment. While global e-ride services like Lime, Bird, and Tier are popular, Bangladesh still lacks a proper technology-based system for this. 

E-Ride Services solves this by offering a self-driven, short-distance (last-mile connectivity) rental service. Unlike traditional car rentals, it does not require lengthy paperwork, security deposits, or a heavy vehicle license. It is quick, simple, and designed for the modern commuter.

---

## 👥 Team Members
| SL | Student ID | Name | Role |
| :---: | :---: | :--- | :--- |
| 1 | 23-55285-3 | MD. SHIFUL ISLAM | Member |
| 2 | 23-55311-3 | ANJAN SARKER | Member |
| 3 | 23-55198-3 | TAMAL SUTRADHAR | Member |


---

## ✨ Key Features

### 🔐 Common Features (All Users)
*   **Authentication & Security:** Secure Registration, Login, Logout, Session Management, and Role-based Access Control (RBAC).
*   **Profile Management:** View, Edit, Update Profile, Change Password, and Delete Account (Customer).
*   **Dashboard:** Personalized dashboards for Customers, Managers, and Admins.

### 👤 Customer Features
*   **Bike Discovery & Booking:** View available E-Bikes, bike types, details, select pickup station, and book/cancel bookings.
*   **Ride Management:** Start a ride (with or without prior booking), select end station, complete ride, and track ride history.
*   **Automated Calculations:** Automatic Ride Fare and Duration calculation based on time.
*   **Payments:** Secure checkout with Card or bKash, Transaction ID generation, and payment status tracking.

### 👨‍💼 Manager Features
*   **Station Operations:** View and update assigned station information, monitor station capacity, and real-time availability.
*   **Vehicle/Fleet Management:** Add, edit, delete, and change vehicle status (Available, Booked, In-Ride, Maintenance).
*   **Fleet Logistics:** Transfer/move vehicles from one station to another seamlessly.

### 🛡️ Admin Features
*   **User & Role Management:** View all users, delete accounts, and change roles (Customer, Manager, Admin).
*   **Request Management:** Review, approve, or reject Manager role requests.
*   **System & Station Management:** Add, edit, or deactivate stations, and assign managers to specific stations.
*   **Financial Monitoring:** View payments, monitor transactions, and update transaction statuses.

---

## 🗄️ System Design & Architecture

### Database ER Diagram
> ![ER Diagram](screenshots/er-diagram.png)

### UI/UX Design (Use Case Diagram)
> ![Use Case Diagram](screenshots/use-case.png)

---

## 📸 Screenshots

### 🔐 Authentication
| Landing Page | Login | Registration |
| :---: | :---: | :---: |
| ![Landing Page](screenshots/01-landing-page.png) | ![Login](screenshots/02-login.png) | ![Register](screenshots/03-register.png) |

### 👤 Customer Panel
<details>
<summary><b>Click to expand Customer Panel Screenshots</b></summary>
<br>

| Customer Dashboard | Profile & Settings | Rent a Bike |
| :---: | :---: | :---: |
| ![Dashboard](screenshots/04-customer-dashboard.png) | ![Profile](screenshots/05-customer-profile.png) | ![Rent Bike](screenshots/06-rent-bike.png) |

| Manage Ride | Payment Checkout |
| :---: | :---: |
| ![Manage Ride](screenshots/07-manage-ride.png) | ![Payment](screenshots/08-payment.png) |

</details>

### 👨‍💼 Manager Panel
<details>
<summary><b>Click to expand Manager Panel Screenshots</b></summary>
<br>

| Manager Dashboard | Manage Bikes |
| :---: | :---: |
| ![Manager Dashboard](screenshots/09-manager-dashboard.png) | ![Manage Bikes](screenshots/10-manager-bikes.png) |

</details>

### 🛡️ Admin Panel
<details>
<summary><b>Click to expand Admin Panel Screenshots</b></summary>
<br>

| Admin Dashboard | Manage Users | Manager Requests |
| :---: | :---: | :---: |
| ![Admin Dashboard](screenshots/11-admin-dashboard.png) | ![Manage Users](screenshots/12-admin-users.png) | ![Manager Requests](screenshots/13-admin-requests.png) |

| Manage Stations | Payments Overview |
| :---: | :---: |
| ![Manage Stations](screenshots/14-admin-stations.png) | ![Payments](screenshots/15-admin-payments.png) |

</details>

---

## 🛠️ Technologies Used
*   **Frontend:** HTML, CSS, JavaScript
*   **Backend:** PHP
*   **Database:** MySQL
*   **Local Server:** XAMPP
*   **Version Control:** Git & GitHub

---

## 🚀 How to Run Locally

1. **Clone the repository:**
   ```bash
   git clone https://github.com/ShifulIslam03/ERide-web.git
