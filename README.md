## Project Installation 

- Clone the project repository  
- Enter to project directry on terminal or from IDE
-  Do composer install by
  
  composer install
  
  Or
  
  composer update
  
- Take a copy of .env.example and create .env file, provide proper database connection details.
- Genarate key only if it doesn't happens by installation.
 
  php artisan key:generate
  
- Create new database and give the same in .env with db username and password
- Do migrate tables using the following command
  
  php artisan migrate
  
- Run the Seeder to generate User using the following command
  
  php artisan db:seed --class=UserTeacherSeeder
  
- Run the application using
  
  php artisan serve
  
  Login Using the following credentials
  
- **User**: adarshthavarool@gmail.com
- **pass**: Password
