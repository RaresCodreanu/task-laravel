Small appointment laravel calendar.
First you must register becouse you can set and view only your appointments on the calendar. Otherwise, feel free to see the entire `appointments` table in the Admin section of the page. Here you have admin rights and can easily manipulate the database.

The logic of this app is very simple:
    -register and recive an user_id which is stored in `users` as id and in `appointments` table as user_id
    -next, after log in, the callendar will show up and will be populated via php using controller and routes
    -the user can add and delete event from calendar via ajax
    -created a small view for admins to see all events and remove them quicly if needed
    
 Table -> users
       -> appointments
       -> migrations
       -> password_reset
       -> personal_access_tokens
  
Views:
    -admin
    -welcome
    -dashboard
    
 Database: MySql
 Laravel v9.50.2 (PHP v8.2.2)
 jquery-3.6.3
 Bootstrap 4
 Fullcalendar.js
