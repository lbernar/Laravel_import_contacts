## Laravel 5.5 Import CSV Contacts

Simple project showing how to import data from CSV file, also matching CSV columns with database columns.

Also showing how to deal with CSV files with/without header rows, using plain PHP functions and [maatwebsite/excel package](https://github.com/Maatwebsite/Laravel-Excel).

![Laravel Import CSV](https://laraveldaily.com/wp-content/uploads/2018/11/import-csv.png)

---

### How to use

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__
- Run __composer install__
- Run __php artisan key:generate__
- Run __docker-compose -f database.yml up -d__
- Run __php artisan migrate__
- Run __php artisan db:seed__
- Run __php artisan serve__
- That's it - load the homepage
- Login to the application, using the user test@gmail.com, with the password: password
---
