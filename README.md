## Author

-   [Milan Joshi](https://github.com/mj756)

## About

This is micro architecture laravel app which includes dependency injection,singleton object pattern.Email configuration using Jobs and Queue. Scheduled task for sending email, custom error handling and much more.

## Dependency Injection

    In Repository folder there are serveral classes and interface defined. I have bound specific interface to its related class in in singleton manner.
    So in any controller we pass OperationManagerInterface as dependency via controller's constructor. I have bound interfaces to its relative classes in CustomBindingProvider which is registered in app.php.

    The logic of singleton class is written in CustomBindingProvider

## DataManager class

    The DataManager class is one and only class in entire app which interact with database. Thus data pass from controller to DataManager via layer
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

## Run project

    1.Rename .env.example file to .env
    2.php artisan migrate:fresh
    3.php artisan passport:client --personal
    4.php artisan route:cache
    5.php artisan config:cache
    6.php artisan optimize
    7.php artisan serve
