# 🏥 Health Monitoring System

A comprehensive health monitoring and nutrition tracking system built with Laravel.

## 📋 Features

### 🍎 Nutrition & Health Monitoring
- **BMI Calculator** - Calculate Body Mass Index
- **Calorie Calculator** - Calculate daily calorie needs with history tracking
- **Meal Planner** - Plan your daily meals
- **Food Guide** - Browse and search food database
- **Nutrition Monitoring** - Track children's nutrition and growth

### 📊 Growth Monitoring
- **Growth Tracking** - Monitor children's growth (weight & height)
- **Stunting Detection** - Detect stunting risk using WHO Z-Score
- **Pre-Stunting Detection** - Early detection of stunting risk
- **Growth Reports** - Generate PDF reports

### 👨‍👩‍👧‍👦 Children Management
- **Children Profile** - Manage multiple children profiles
- **Growth Logs** - Track growth history with edit/delete features
- **Food Logs** - Track daily food intake
- **Alerts** - Get notifications for growth concerns

### 🔐 User Management
- **Multi-language Support** (English & Indonesian)
- **User Authentication** - Secure login/register
- **Profile Management** - Update profile and password
- **Admin Panel** - Manage users, food database, and settings

## 🚀 Latest Updates (v2.0)

### ✨ New Features:
- ✅ **Calorie Calculator History** - Save and view calculation history
- ✅ **Growth Log Edit/Delete** - Edit and delete growth records
- ✅ **Empty State UI** - Better UX for new users
- ✅ **AJAX Operations** - Smooth delete operations without page reload
- ✅ **Confirmation Dialogs** - SweetAlert confirmations for destructive actions

### 📦 Database Changes:
- New table: `calorie_history_models`
- Enhanced validation for growth logs
- Foreign key constraints for data integrity

## 🛠️ Tech Stack

- **Framework:** Laravel 10.x
- **PHP:** >= 8.0
- **Database:** MySQL/MariaDB
- **Frontend:** Bootstrap 5, jQuery, SweetAlert2
- **Icons:** Icofont
- **PDF:** DomPDF

## 📥 Installation

1. **Clone Repository**
   ```bash
   git clone https://github.com/Kristinaadine/health_monitoring_main.git
   cd health_monitoring_main
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=health_monitoring
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

8. **Access Application**
   ```
   http://localhost:8000
   ```

## 📚 Documentation

Comprehensive documentation available in the `/docs` folder:

- **MIGRATION_GUIDE.md** - Step-by-step migration guide
- **CHANGELOG_CALORIE_CALCULATOR.md** - Calorie calculator changes
- **COMPARISON_OLD_VS_NEW.md** - Feature comparison
- **DOKUMENTASI_NUTRITION_MONITORING.md** - Nutrition monitoring docs
- **DOKUMENTASI_ZSCORE_DAN_FITUR.md** - Z-Score implementation

## 🔧 Configuration

### Multi-language Setup
The system supports English and Indonesian. Language files are located in:
- `resources/lang/en/`
- `resources/lang/id/`

### Admin Access
Default admin credentials (after seeding):
- Email: admin@example.com
- Password: password

## 🧪 Testing

```bash
php artisan test
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Contributors

- **Kristina Adine** - Initial work

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- WHO Growth Standards for Z-Score calculations
- Bootstrap for responsive UI
- SweetAlert2 for beautiful alerts

## 📞 Support

For support, email kristinaadine@example.com or open an issue on GitHub.

---

**Version:** 2.0  
**Last Updated:** November 13, 2025
