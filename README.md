Small appointment laravel calendar.<br>
First you must register becouse you can set and view only your appointments on the calendar. Otherwise, feel free to see the entire `appointments` table in the Admin section of the page. Here you have admin rights and can easily manipulate the database.

The logic of this app is very simple:<br>
    -register and recive an user_id which is stored in `users` as id and in `appointments` table as user_id<br>
    -next, after log in, the callendar will show up and will be populated via php using controller and routes<br>
    -the user can add and delete event from calendar via ajax<br>
    -created a small view for admins to see all events and remove them quicly if needed<br>
    
 Table -> users<br>
       -> appointments<br>
       -> migrations<br>
       -> password_reset<br>
       -> personal_access_tokens<br>
  
Views:<br>
    -admin<br>
    -welcome<br>
    -dashboard<br>
    
 Database: MySql <br>
 Laravel v9.50.2 (PHP v8.2.2)<br>
 jquery-3.6.3<br>
 Bootstrap 4<br>
 Fullcalendar.js<br>
