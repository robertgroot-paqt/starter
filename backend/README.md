# Laravel starter project

## Laravel sanctum

API auth :)

## Laravel data

No more magically typed requests and resources, but strictly typed data object everywhere!
- Automatic validation rules when injected from a request
- Automatic serialization to array/json/responses

## Laravel query builder

Use query params to filter, sort and include models. See the [HasQueryBuilder](./app/Models/Traits/HasQueryBuilder.php)

Uses Laravel json api paginate to adhere to the json spec.

## Laravel route attributes

No more route files, but Attributes in the controllers to define routes. Use `route:list` to quickly view all routes.

## Action pattern

Use actions to do stuff. Controllers call actions.

## The magic stuff 🤔

There is some custom logic that handles some stuff automagically:
- [`ApiController`](./app/Http/Controllers/ApiController.php) to quickly add new API endpoints
- [`ApiAuthorizeMiddleware`](./app/Http/Middleware/ApiAuthorizeMiddleware.php) to add policy checks on API endpoints
- [`Data::dataCollection`](./app/Data/Responses/ResponseData.php) function so we can use the DataCollection functions when transforming to responses.
- A custom [`TransformationContext`](./app/Data/Responses/ResponseTransformationContext.php) to add meta data to the transformed response:
    - Operations: use model policies to handle authorization checks in the FE
        - Includeable via query param

## Typescript generation

See [the typescript folder](./app/Data/Typescript) for some customization of the laravel data typescript generator that generates code so that de API can be called type safely

Run `php artisan typescript:transform` in the backend to generate the file that is symlinked to the frontend folder. Type safe fetch functions for API controllers are generated that rely on a useApi function that should be defined in the frontend.

## TODOs

- Folder structure: feature folders?
- Database backed translations?
- Laravel Saloon?
- Laravel passport?
