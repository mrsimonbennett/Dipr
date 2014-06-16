Dipr
====

Dependency Injection for Methods in Laravel Controllers

Enabling you to use the IOC to inject into controllers methods. Why? Well somethings you have a instance of something you only want for one of a controllers methods so does not make sense to inject via the constructor.

Quick Example
```
 public function index( \ACME\Validation\Company $companyValidation)
    {    }
```



#Requirments


- Laravel 4.2
- PHP 5.4
- Changing the Base Controller/Your Controller to Extend from the packages


#Installing

Add To composer.json

```json
{
 "require": {
   "mrsimonbennett/dipr": "dev-master"
 }
}
 ```
I recommend you use a BaseController of some sort, if not your controller need to extend Mrsimonbennett\Dipr\Controller
```php
 <?php
use Mrsimonbennett\Dipr\Controller;
class BaseController extends Controller {
}
```

#Usage Example
(needs more work)

```php
/**
 * Class ProcessController
 * @package Amce\Controllers
 */
class ProcessController extends \BaseController
{

    /**
     * @param Request                $request
     * @param PerformCountDownSolver $performCountDownSolver
     */
    public function getRequest(Request $request)
    {
        return $request->All();
    }
    public function getRequestWithURLSlug(Request, $request, $slugFromRouteConfig)
    {
    
    }
    
    public function getRequestWithModelFromRouter(User $userFromRouteConfig,Request $request, $randomSlugStringFromRouteConfig)
    {
    
    }
}```

As you can see objects are always first before strings/ints loaded using the router.php

#How It works

1) The code looks at the signuture of the method.
2) Looks at parameters the router is sending
3) If the parameters are objects there are stored in a list
4) The code them matches the objects in the stored list with the signuture
5) If the object in the signuture does not exist in the use the App::Make() method is called which will use the magic of the laravel IOC to pass you that object (whether it needed created or already existed)
6) Anything thats not in the process like slugs and ints are passed to themethod last. 

If you get stuck you can always use var_dump the func_get_args() and see whats going on
