# Global Tourism Booking System 🌍

A comprehensive and scalable backend API built with **Laravel 10**, designed to power a complete tourism and travel ecosystem. This platform manages everything from company registrations and trip planning to a sophisticated loyalty-based financial system.

## 🚀 Key Features

* **Multi-Role Management:** Robust logic for **Super Admins** (platform owners), **Admins** (Tourism Companies), and **Users** (Travelers).
* **Advanced Wallet System:** * Tiered loyalty levels: **Bronze, Silver, Gold, and Platinum**.
    * Secure transactions: Deposits, Withdrawals, and Transfers.
    * Point accumulation and automated level upgrading based on spending.
* **Dynamic Booking System:** Handles trip lifecycle from pending requests to active and canceled bookings.
* **Smart Rating Engine:** Built-in Traits for real-time average rating calculations for both Tourism Companies and specific offers.
* **Polymorphic Media Handling:** A unified image processing system for flexible file management across the entire platform.
* **Security:** Integrated verification codes for wallet actions and password resets.

## 🛠️ Technical Highlights

* **Framework:** Laravel 10 (PHP)
* **Database:** MySQL (Complex relational schema with 20+ optimized migrations).
* **Architecture:** Heavily utilizes **Traits** (DRY principle) for reusable logic like `WalletTrait`, `ImageTrait`, and `RatingTraits`.
* **API Resources:** Clean and structured JSON responses for seamless frontend integration.

## 📂 Core Structure Included

* **Controllers:** Admin, SuperAdmin, User, and Authentication controllers.
* **Migrations:** Full database schema including `wallets`, `bookings`, `tourist_trips`, `transactions`, and `ratings`.
* **Traits:** Custom business logic for modular and clean code.

## 🔧 Installation & Setup

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/ali-hamdan-002/Global-Tourism-Booking-System.git](https://github.com/ali-hamdan-002/Global-Tourism-Booking-System.git)
    ```
2.  **Install Dependencies:**
    ```bash
    composer install
    ```
3.  **Environment Configuration:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Database Setup:**
    Configure your `.env` database settings, then run:
    ```bash
    php artisan migrate
    ```
5.  **Run Server:**
    ```bash
    php artisan serve
    ```

---
*Developed by Ali Hamdan - A professional backend solution for modern tourism management.*