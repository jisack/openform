# openform
form to make a api form for other device. this only for laravel project !

##Requirements
* [Laravel 5.3](https://laravel.com/docs/5.3)
* [Laravel 5.2](https://laravel.com/docs/5.2)

##Installation
  in composer.json add
  ```json
    "Wisdom\\Openform\\" : "vendor/wisdompackage/openform/src"
  ```
  to "psr-4"
  
  in config/app add
  ```php
    Wisdom\Openform\OpenformServiceProvider::class,
  ```
  to array provider
  
  
  and then run 
  ```bash
      $ composer require wisdompackage/openform
  ```
  
  run
  ```bash
      $ php artisan vendor:publish
      $ php aritsan migrate
  ```
  
  so now you can run a form package thought
  
  <b>www.yourdomain.com\form</b>
  
##Usage
  Send your answer by http POST
  
  type : json
  
  example
  
```json
    { "form_id" : 1 ,
        "answers" : [
            {"question_id" : 1,
              "type" : "text",
              "value" : "eiei"
            },
            {"question_id" : 2,
              "type" : "single",
              "value" : 2
            },
            {
              "question_id" : 3,
              "type" : "multiple",
              "values" : [1,2,3]
            }
        ]	
    }
```





