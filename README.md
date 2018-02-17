### Introduction

This is an API in PHP that calls a "backend API" to get information about crash test ratings for vehicles ([NHTSA NCAP 5 Star Safety Ratings API](https://one.nhtsa.gov/webapi/Default.aspx?SafetyRatings/API/5)).

Application is developed in **Laravel Framework 5.6.3**. Please make sure your server environment meets to run Laravel Framework 5.6.3

Application can serve via ```php artisan serve``` or you may use any webserver (apache2, nginx, etc) you like 

### Server Requirement
- PHP 7.1.3 or higher
- uses composer 
- uses phpunit to run test case
- make sure PHP Curl library available

### Quick Start
- `git clone https://github.com/gitmastro/banckendAPI-NHTSA-WebAPIs.git`
- `cd banckendAPI-NHTSA-WebAPIs`
- `composer install` to install dependent packages
- `php artisan serve` to run Laravel internal webserver or you may use laravel compatible webserver
- `phpunit` to run written test cases under `tests/Feature`
- You may use [Postman API Development Environment](https://www.getpostman.com/) to send http GET, POST request
  

### USE Cases 

**Get available vehicle variants for a selected Model Year, Make and Model**  

*GET Request* 
```
GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
```

*POST Request* 
```
POST http://localhost:8080/vehicles

JSON body as follows:

{
    "modelYear": 2015,
    "manufacturer": "Audi",
    "model": "A3"
}
```

*Response* 
```
{
    Count: <NUMBER OF RESULTS>,
    Results: [
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        }
    ]
}
```


**Get available vehicle variants for a selected Model Year, Make and Model with Crash Rating** 

*GET Request* 
```
GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true
```

The new field is `CrashRating` and it will be a string field whose possible values are:

* `"Not Rated"`
* `"0"`
* `"1"`
* `"2"`
* `"3"`
* `"4"`
* `"5"`

*Response* 

```
{
    Count: <NUMBER OF RESULTS>,
    Results: [
        {
            CrashRating: "<CRASH RATING>"
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },...
    ]
}
``` 

**HTTP 404 response**
```
{
    "code": "not-found",
    "message": "Api route not found"
}
``` 
**HTTP 500 response**
```
{
    "code": "internal-error",
    "message": "Internal server error"
}
``` 

### Test Cases 
 
- GET request test case class: tests/Feature/VehicleGetApiTest.php
- POST request test case class: tests/Feature/VehiclePostApiTest.php
- Run test case command `$ phpunit` _(make sure you have phpunit configured)_
