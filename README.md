## Author

-   [Milan Joshi](https://github.com/mj756)

## About

This is micro architecture laravel app which includes dependency injection,singleton object pattern.Email configuration using Jobs and Queue. Scheduled task for sending email, custom error handling and much more.
The main idea is to optimize the perfomance and maintain standard architecture. In controller we only write code related validation no any business logic should be write in controller. The DataManager class will handle all database related operation, perform business logic and return data to controller.

## Dependency Injection

    In Repository folder there are serveral classes and interface defined. I have bound specific interface to its related class in in singleton manner.
    So in any controller we pass OperationManagerInterface as dependency via controller's constructor. I have bound interfaces to its relative classes in CustomBindingProvider which is registered in app.php.

    The logic of singleton class is written in CustomBindingProvider

## DataManager class

    The DataManager class is one and only class in entire app which interact with database, perform business logic. Thus data pass from controller to DataManager via layer
    like

    Controller->OperationManagerInterface->OperationManager->DataManagerInterface->DataManager

    and data return by DataManager is in following way
    DataManager->OperationManager->Controller

## Database models

    I have declared all Database related models in App\Repository\Models\

## useful commands

    1. php artisan serve
    2. php artisan migrate:fresh
    3. php artisan optimize
    4. composer install
    5. php artisan route:cache
    6. php artisan make:model <modelname>
    7. php artisan cache:clear
    8. php artisan config:clear
    9. php artisan make:provider <providername>
    10.php artisan make:controller <controller name>
    11.php artisan schedule:work
    12.php artisan queue:listen
    13.php artisan passport:client --personal

## Scheduling

    I have created one command called ScheduledEmail which send email to user whose license is expiring soon. Currently it send email on every saturday, you can customize functionality as per need.

    To run this functinoality run following command

    php artisan schedule:work
    which will start sending email to all database users on every Saturday.

## Note

    1. start listening queue by php artisan queue:listen  then only jobs will start executing.
    2. whenever you add new routes in web.php or api.php dont forget to run following command
        php artisan route:cache
    3. To start scheduling run following command , otherwise scheduled task will not be executed.
        php artisan schedule:work

    4. I have defined macro in AppServiceProvider which will provider valid json data based on status code and payload.
    5. Whenever there is need to add new controller/model then specify the full path like  App\Http\Controllers\WEB\<name of controller> or App\Http\Controllers\API\<name of         controller> . For model specify App\Repository\Models\<model name>

## Run project
   
    1.Rename .env.example file to .env
    2.composer install
    3.php artisan migrate:fresh
    4.php artisan passport:client --personal
    5.php artisan route:cache
    6.php artisan config:cache
    7.php artisan optimize
    8.php artisan serve
