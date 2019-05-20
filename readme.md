# Laravel Nova Record Locks

![](https://i.imgur.com/ujUZCZT.png)

## Introduction

A record will be locked when edited by a user, to prevent access by other users. Record locks can be removed by selecting the checkbox and chosing: `Remove record locks` in the list overview.

A user is only allowed to remove it's own locks. 

![](https://i.imgur.com/y2HIKq6.png)

## Configuration & Installation

`composer require douma/laravel-nova-record-locks`

Publish the ServiceProvider:

`php artisan vendor:publish --provider="Douma\RecordLocks\ServiceProvider"`

Register the service provider `config/app.php`:

`Douma\RecordLocks\ServiceProvider::class`

### Create database table

For now create a database table. 
We will provide a migration for this in the future. 

```
CREATE TABLE `record_locks` (
  `model` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
```

### Create a policy class

Create a policy class for lists you wish to protect. If you have no policy, just `return true` for each method. Register the policy class in `config/record_locks.php` in the `policies` namespace. 

### Add action to list

Register the `LockedBy` field in the fields method:

```php
public function fields(Request $request)
{
    return [
        ID::make()->sortable(),
        LockedBy::make(),
        //... other 
    ];
}
```

This makes the lock symbol visible in the list. 

### Add RemoveRecordLock action in the list


Register `RemoveRecordLock` in the actions method:

```php
public function actions(Request $request)
{
    return [
        //...others
        new RemoveRecordLock()
    ];
}
```

This makes it possible to remove locks from the list overview. 

# Todo

This package is still in beta. 

## Admin feature

Under construction. 

## Nicer images

## Migrations
