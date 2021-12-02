## Laravel 5.5 Import CSV Contacts

Simple project showing how to import data from CSV file, also matching CSV columns with database columns.

---

### How to use

- Clone the repository with __git clone__
- Copy __env.example__ file to __.env__
- Run __composer install__
- Run __php artisan key:generate__
- Run __docker-compose -f database.yml up -d__
- Run __php artisan migrate__
- Run __php artisan db:seed__
- Run __php artisan serve__
- That's it - load the homepage
- Login to the application, using the user test@gmail.com, with the password: password
- The Sample CSV file is located in the root of the project, the file name is sample.csv
---
