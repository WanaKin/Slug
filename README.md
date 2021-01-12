# Slug
This package provides a simple slug service for Laravel, which makes it easy to use whatever slug you want for model binding instead of the ID. For example, instead of `https://example.com/posts/123`, you can have `https://example.com/posts/hello-world`. 

## Usage
### Models
Before you can use the service with a model, you'll need to add the `WanaKin\Slug\Sluggable` trait to it. This defines the `sluggable` relationship, as well as a `resolveRouteBinding` method which will bind the models according to the slug. You don't need to make any modifications to your model's database table.

### Service
This package comes with both a `WanaKin\Slug\SlugService` service as well as a facade (`WanaKin\Slug\SlugService`). Use the former if you'd like to use dependency injection or the latter otherwise.

#### Creating/Retrieving A Slug
In order to create or get a slug for a model, use the `get` method:

```php
$slug = SlugService::get( $model );
```

If no other arguments are passed, and no slug exists, then a UUID will be generated. If you'd like to provide your own default, you can do so by passing a second argument:

```php
$slug = SlugService::get( $model, 'hello-world' );
```

In order to help prevent collisions, the method will add an 8 character random string at the end of the provided slug, so the complete slug will be something like `hello-world-4nf6ADpe`. If you'd like to disable this safety, pass a boolean `FALSE` as the third argument:

```php
$slug = SlugService::get( $model, 'hello-world', FALSE );
```

The slug (whether newly created or already existing) will be returned.


#### Resolving
Although you shouldn't have to do this manually, you can pass a string to the `resolve` method, and the model to which the slug points to will be returned (or `NULL` if none exists):

```php
$model = SlugService::resolve( 'hello-world' );
```

#### Deleting
In order to delete a slug, use the `delete` method. This method works with either a slug string or slugged model:

```php
SlugService::delete( 'hello-world' );
SlugService::delete( $model ); // This will only delete the slug, not the model itself!
```

This will soft delete the slug. If `resolve` is called on the slug, it will return `NULL` which will throw a 404 if used for model binding. However, if `get` is called on the model, the slug will be restored (or updated to the passed default value). If you'd like to permanently delete a slug, pass a boolean `TRUE` as the second argument:

```php
SlugService::delete( 'hello-world', TRUE );
```

If `get` is called again, a new slug will be generated.
