# Laravel Filter Query String
#### Filter your queries based on url query string parameters like a breeze.

### Describing the Problem

You have probably faced the situation where you needed to filter your query based on given parameters in url query-string and after developing the logics, You've had such a code:

```php
$users = User::latest();

if(request('username')) {
    $users->where('username', request('username'));
}

if(request('age')) {
    $users->where('age', '>', request('age'));
}

if(request('email')) {
    $users->where('email', request('email'));
}

return $users->get();

```

This works, But it's not a good practice.

When the number of parameters starts to grow, The number of these kind of `if` statements also grows and your code gets huge and hard to maintain.
 
Also it's against the Open/Closed principal of SOLID principals, Because when you have a new parameter, You need to get into your existing code and add a new logic (which may breaks the existing implementations).

So we have to design a way to make our filters logics separated from each other and apply them into the final query, which is the whole idea behind this package.

### Usage
1. First you need to install the package:

`$ composer require mehradsadeghi/laravel-filter-querystring`

2. Then you should `use` the `FilterQueryString` trait in your model, And define `$filters` property which can be consist of available filters or your custom filters (we'll get to it later).

```php
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class User extends Model
{
    use FilterQueryString;

    protected $filters = [];

    ...
}
```
3. You need to use `filter()` method in your eloquent query. For example:

```php
User::select('name')->filter()->get();
```

#### Available Methods
- sort
- greater
- greater_or_equal
- less
- less_or_equal
- between
- not_between
- in
- like
- where clause

For the purpose of explaining each method, Imagine we have such data in our `users` table:

| id  |   name   |           email            |  username  |  age | created_at 
|:---:|:--------:|:--------------------------:|:----------:|:----:|:----------:|
|  1  | mehrad   | mehrad<i></i>@example.com  | mehrad123  |  20  | 2020-09-01 |
|  2  | reza     | reza<i></i>@example.com    | reza123    |  20  | 2020-10-01 |
|  3  | hossein  | hossein<i></i>@example.com | hossein123 |  22  | 2020-11-01 |
|  4  | dariush  | dariush<i></i>@example.com | dariush123 |  22  | 2020-12-01 |

And assume our query is something like this:

```php
User::filter()->get();
```

#### sort
Sort is the equivalent of `order by` sql statement which can be used flexible in `FilterQueryString`:

Conventions:

```
?sort=field
?sort=field,sort_type
?sort=field1,field2
?sort=field1,field2,sort_type
?sort=field1,sort_type,field2,sort_type

```

In User.php
```php
protected $filters = ['sort'];
```
Single `sort`:

`https://example.com?sort=created_at`

Output:

|   name   |           email            |  username  |  age | created_at 
|:--------:|:--------------------------:|:----------:|:----:|:----------:|
| mehrad   | mehrad<i></i>@example.com  | mehrad123  |  20  | 2020-09-01 |
| reza     | reza<i></i>@example.com    | reza123    |  20  | 2020-10-01 |
| hossein  | hossein<i></i>@example.com | hossein123 |  22  | 2020-11-01 |
| dariush  | dariush<i></i>@example.com | dariush123 |  22  | 2020-12-01 |

*Note that when you're not defining `sort_type`, It'll be `asc` be default.*

Multiple `sort`s:

`https://example.com?sort[0]=age,desc&sort[1]=created_at,desc`

Output:

|   name   |           email            |  username  |  age | created_at 
|:--------:|:--------------------------:|:----------:|:----:|:----------:|
| dariush  | dariush<i></i>@example.com | dariush123 |  22  | 2020-12-01 |
| hossein  | hossein<i></i>@example.com | hossein123 |  22  | 2020-11-01 |
| reza     | reza<i></i>@example.com    | reza123    |  20  | 2020-10-01 |
| mehrad   | mehrad<i></i>@example.com  | mehrad123  |  20  | 2020-09-01 |
