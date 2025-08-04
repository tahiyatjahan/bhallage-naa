# Bhallage Na - Mental Health & Creative Expression Platform

A comprehensive Laravel-based web application designed to support mental health through journaling, anonymous sharing, daily prompts, and creative expression.

## 🌟 Features

### **Core Features**
- **Mood Journal**: Document feelings with hashtags and mood ratings
- **Secret Whispers**: Anonymous sharing in a safe space
- **Daily Prompts**: Auto generated prompts for journaling inspiration
- **Express Yourself**: Share creative content (music, art, poetry, photography)
- **Admin Panel**: Comprehensive management system

### **User Experience**
- **Responsive Design**: Works on all devices
- **Beautiful UI**: Yellow-themed, calming interface
- **Real-time Interactions**: Likes, comments, upvotes
- **File Uploads**: Support for images and media files
- **Hashtag System**: Categorize and filter content

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x (PHP 8.4)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL/PostgreSQL
- **File Storage**: Laravel Storage
- **Authentication**: Laravel Breeze

## 📋 Requirements

- PHP 8.4+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web server (Apache/Nginx)

## 🚀 Installation

### **1. Clone the Repository**
```bash
git clone https://github.com/YOUR_USERNAME/bhallage-naa.git
cd bhallage-naa
```

### **2. Install Dependencies**
```bash
composer install
npm install
```

### **3. Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

### **4. Database Configuration**
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bhallage_naa
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### **5. Run Migrations**
```bash
php artisan migrate
```

### **6. Build Assets**
```bash
npm run build
```

### **7. Create Storage Link**
```bash
php artisan storage:link
```

### **8. Start the Server**
```bash
php artisan serve
```

## 👤 Admin Setup

### **Make a User Admin**
```bash
php artisan user:make-admin user@example.com
```

### **Admin Features**
- Dashboard with statistics
- User management
- Content moderation
- Daily prompt management
- Creative posts management

## 📁 Project Structure

```
bhallage-naa/
├── app/
│   ├── Console/Commands/          # Custom Artisan commands
│   ├── Http/Controllers/          # Controllers
│   ├── Http/Middleware/           # Custom middleware
│   └── Models/                    # Eloquent models
├── database/
│   └── migrations/                # Database migrations
├── resources/
│   └── views/                     # Blade templates
└── routes/
    └── web.php                    # Web routes
```

## 🎨 Key Features Explained

### **Mood Journal System**
- Users can write journal entries with hashtags
- Optional mood ratings (1-10)
- Upvote and comment system
- Filter by hashtags

### **Secret Whispers**
- Anonymous sharing platform
- Report system for moderation
- Safe space for vulnerable thoughts

### **Daily Prompts**
- Auto generated daily prompts
- Admin can create custom prompts
- Categorized prompts (general, gratitude, reflection)

### **Express Yourself**
- Creative content sharing
- Multiple categories (music, art, poetry, photography)
- File upload support
- Like and comment system

## 🔧 Customization

### **Themes**
The application uses a yellow-based theme. To customize:
1. Edit `resources/css/app.css`
2. Modify Tailwind classes in Blade templates
3. Update color variables in CSS

### **Adding New Features**
1. Create migrations for new tables
2. Add models with relationships
3. Create controllers with CRUD operations
4. Add routes in `routes/web.php`
5. Create Blade views

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with Laravel framework
- Styled with Tailwind CSS
- Icons from Heroicons
- Fonts from Google Fonts

## 📞 Support

For support, email support@bhallage-naa.com or create an issue on GitHub.

---

**Made with ❤️ for mental health awareness and creative expression**
