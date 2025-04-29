# Blog Website

A simple blog platform built with PHP, MySQL, and Bootstrap.

## Features

- User registration and authentication
- Create, read, update, and delete blog posts
- Responsive design
- Categories and tags support
- Featured posts section

## Installation

1. Clone the repository:
```bash
git clone https://github.com/Nikhil4123/Blogwebsit.git
cd Blogwebsit
```

2. Create a `.env` file in the root directory:
```bash
cp sample.env .env
```

3. Configure your database settings in the `.env` file:
```
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_username
DB_PASS=your_password
DEBUG=true
```

4. Import the database schema:
```bash
mysql -u your_username -p your_database_name < database.sql
```

5. Configure your web server (Apache/Nginx) to serve the application from the public directory.

## Environment Variables

The following environment variables should be set in your `.env` file:

- `DB_HOST`: Your MySQL database host (default: localhost)
- `DB_NAME`: Your MySQL database name
- `DB_USER`: Your MySQL database username
- `DB_PASS`: Your MySQL database password
- `DEBUG`: Set to true for development mode, false for production

## Security Notes

- The `.env` file contains sensitive information and is excluded from version control
- Always use secure passwords for your database
- Set DEBUG=false in production to hide error messages

## License

This project is open-source software.

## Credits

- Built with [Bootstrap](https://getbootstrap.com/)
- Font Awesome for icons 