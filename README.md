# E-Ride Services - Eco-friendly E-Bike Rental Platform

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**E-Ride Services** is a web-based platform designed to provide a smart, affordable, and eco-friendly transportation solution using e-bikes and scooters. This application targets students and office workers in urban areas like **Bashundhara R/A, Dhaka**. 

The system allows users to rent electric vehicles from designated stations, track their ride time, and pay a transparent fare (**2 Taka per minute with a minimum charge of 3 Taka**). The platform is managed through a centralized web interface with three distinct roles: **Rider (Customer), Station Manager, and System Admin**.

---

## 📖 Background Study
In fast-growing cities like Dhaka, students, office workers, and ordinary people struggle daily to find good transportation. Current options are often too expensive, unreliable, and harmful to the environment. While global e-ride services like Lime, Bird, and Tier are popular, Bangladesh still lacks a proper technology-based system for this. 

E-Ride Services solves this by offering a self-driven, short-distance (last-mile connectivity) rental service. Unlike traditional car rentals, it does not require lengthy paperwork, security deposits, or a heavy vehicle license. It is quick, simple, and designed for the modern commuter.

---

## 👥 Team Members
| SL | Student ID | Name | Role |
| :---: | :---: | :--- | :--- |
| 1 | 23-55311-3 | ANJAN SARKER | Group Leader |
| 2 | 23-55198-3 | TAMAL SUTRADHAR | Member |
| 3 | 23-55285-3 | MD. SHIFUL ISLAM | Member |

---

## ✨ Key Features

### 🔐 Common Features (All Users)
*   **Authentication:** Secure Login, Logout, and Registration.
*   **Account Management:** Change/Reset Password, Manage Profile (View, Edit).
*   **Dashboard:** Personalized dashboard access after login.

### 👤 Customer Features
*   **Book an E-Bike:** Browse available bikes at nearby stations.
*   **Start and Complete a Ride:** Seamless ride initiation and end process.
*   **Make Ride Payment:** Secure payment checkout (Card/Bkash) based on time and distance.

### 🏢 Station Manager Features
*   **Update Station Information:** Manage station details and capacity.
*   **Manage Vehicles:** Add, remove, or relocate bikes within the station.
*   **Monitor Availability:** Real-time tracking of available bikes.

### 🛡️ Admin Features
*   **Manage User Accounts and Roles:** Approve manager requests, assign roles.
*   **Manage the Entire E-Ride System:** Overview of all stations, users, and vehicles.
*   **Monitor Payments and Transactions:** Full financial overview of the platform.

---

## 🗄️ System Design & Architecture

### Database ER Diagram
*(Upload your ER Diagram image from the Word file to the `screenshots/` folder and name it `er-diagram.png`)*
> ![ER Diagram](screenshots/er-diagram.png)
*(If you haven't uploaded the image yet, you can remove this image tag or upload it later)*

### UI/UX Design (Use Case Diagram)
*(Upload your Use Case image from the Word file to the `screenshots/` folder and name it `use-case.png`)*
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

### 🏢 Station Manager Panel
<details>
<summary><b>Click to expand Station Manager Panel Screenshots</b></summary>
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
*   **Frontend:** HTML5, CSS3, JavaScript
*   **Backend:** PHP
*   **Database:** MySQL
*   **Local Server:** XAMPP
*   **Version Control:** Git & GitHub

---

## 🚀 How to Run Locally

1. **Clone the repository:**
   ```bash
   git clone https://github.com/ShifulIslam03/ERide-web.git
