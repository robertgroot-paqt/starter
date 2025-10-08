# Laravel starter project

## Laravel sanctum

API auth :)

## Laravel data

No more magically typed requests and resources, but strictly typed data object everywhere!
- Automatic validation rules when injected from a request
- Automatic serialization to array/json/responses

## Laravel query builder

Use query params to filter, sort and include models. See ./app/Models/Traits/HasQueryBuilder.php

Uses Laravel json api paginate to adhere to the json spec.

## Laravel route attributes

No more route files, but Attributes in the controllers to define routes. Use `route:list` to quickly view all routes.

## Action pattern

Use actions to do stuff. Controllers call actions.

## The magic stuff 🤔

There is some custom logic that handles some stuff automagically:
- `ApiController` to quickly add new API endpoints
- `ApiAuthorizeMiddleware` to add policy checks on API endpoints
- `Data::dataCollection` function so we can use the DataCollection functions when transforming to responses.
- A custom `TransformationContext` to add meta data to the transformed response:
    - Operations: use model policies to handle authorization checks in the FE
        - Includeable via query param
        - TODO: is there a better way to get the Model in the operation?

## TODOs

- Folder structure: feature folders?
- Typescript generation
    - Data types
    - Type safe fetch functions for API controllers
