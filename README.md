<h1>Food ordering system</h1>
Tools:
Back-End - REST API using LARAVEL,MySQL
<p>
Developed a page to display nearby cooks .
Manage order/checkout system for selected foods.
Building notification system to notify or approve/cancel order  using Pusher
Building admin panel to manage users/orders
</p>
<h3>How to run?</h3>
<p>
##Mac Os, Ubuntu and windows users continue here:
<ul>
    <li>Create a database locally named homemadefood utf8_general_ci</li>
    <li>Download composer https://getcomposer.org/download/</li>
    <li>Pull Laravel/php project from git provider.</li>
<li>Rename .env.example file to .envinside your project root and fill the database information. (windows wont let you do it, so you have to open your console cd your project root directory and run mv .env.example .env )</li>
<li>enter database credential in env files</li>
<li>Open the console and cd your project root directory</li>
<li>Run composer install or php composer.phar install</li>
<li>Run php artisan key:generate</li>
<li>Run php artisan migrate</li>
<li>Run php artisan db:seed to run seeders, if any.</li>
<li>Run php artisan serve</li>
<li>#####You can now access your project at localhost:8000 :) And you can run those api in Postman (it is an application to run API)
    </ul>
</p>
<ul>
    <h3>I have shared some url to test:</h3>
    <li>POST http://127.0.0.1:8000/api/auth/login
    Body:
        {
            "email":"abc@gmail.com",
            "password":""
        }
    </li>
    <li>
        POST http://127.0.0.1:8000/api/register<br>
    Body-
        {
    "name":"saheli",
    "email":"solu.nly@gmail.com",
    "password":"abck",
    "c_password":"abck"
}
    </li>
    <li>GET http://127.0.0.1:8000/api/dishes</li>
    <li>POST http://127.0.0.1:8000/api/dishes <br>
        Body-
     {
        "name": "Deserunt",
        "price": "985/plate",
        "unit": "plate",
        "user_id": 430,
        "details": "Ut enim eaque odio voluptatem omnis consequatur repellat ut. Animi maxime animi sequi illo numquam.",
        "delivery_type": "Pickup",
        "delivery_time": "2020-05-19 17:08:55",
        "delivery_end_time": "2020-05-24 00:07:06",
        "dish_type": "non-veg",
        "cuisine_type": "Indian"
    }   
    </li>
    <li>
     GET http://127.0.0.1:8000/api/showDish/110
     </li>
    <li>to search food with string 's'
     GET http://127.0.0.1:8000/api/searchFood/s
    </li>
   
</ul>
<p>You can check many other URL from routes/api.php</p>
