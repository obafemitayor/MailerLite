# MailerLite Completed Coding Challenge

> This is the completed source code for the coding challenge

## Setting up The API

``` bash
# Setting up Database Connection
Change the connection string to the appropriate connection string in .env file

# Create Database
Create a Database on MYSQL server using the CREATE Database SQL Syntax i.e. CREATE DATABASE databasename;

# Run Migrations
Run the Migrations To Create The Tables and their respective relationships using php artisan migrate

# Run The API
Run the API and make sure it is listening on port 8000 using this command php artisan serve --port=8000
```

## Setting up The Frontend App

``` bash
# Install Node Packages
Open your CLI, navigate to the frontend directory and run npm install

# Start the application
Start the application by running the command npm run dev 
```

## Running The Unit Tests

``` bash
# Activate The MockClass
uncomment the $this->app->bind(SubscriberProvider::class, MockSubscriberProvider::class) and comment the $this->app->bind(SubscriberProvider::class, AppSubscriberProvider::class)
in the AppServiceProvider class to activate the MockClass for Unit Testing.

# Run Tests
Run the Tests using php artisan test
```
