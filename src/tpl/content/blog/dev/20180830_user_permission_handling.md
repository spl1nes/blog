# User Permission Handling

User permission handling is something that everyone probably has to deal with once during his career as backend developer. While this a very common problem I always had problems with finding articles which way is the best or how to implement a specific solution. In this article we will talk about about two ideas how to implement permission handling and discuss one of the two in more detail with examples. 

## Two Solutions To One Problem

Before going into any detail the basic decision that has to be made is if you want to implement a hierarchal or a flat permission structure. 

## Hierarchal 

This permission structure means that users or groups can inherit permissions from parent users/groups. This inheritance could be a one-to-one or one-to-many relation either way this allows for some very neat and clean permission management. 

At the same time you have to be very careful while setting permissions as child groups will inherit all of the permissions which can have unwanted side effects if the person who manages the permission doesn't keep in mind all the relations. 

Additionally the implementation in the backend is not trivial as this requires a recursive database read of all the groups and their relations or un-normalized tables which get updated every time once any parent user/group changes. While this would definitely be a interesting concept I personally would not implement it because of the two above mentioned challenges. 

## Flat

A flat permission structure means that one user or group has all the permissions directly assigned instead of inheriting any permissions. This means that a lot of groups may look very similar permission wise. 

At the same time the user and group permissions are very easy to understand since no permission relations to other users/groups exists which have to be considered.

Additionally the implementation in the backend is very easy since only the permissions directly assigned to the user and permissions of the groups a user is part of have to be checked. It's also much easier to see which users are part of one group.

### Groups

Usually you want to assign permissions to groups instead of users since this allows for an easier permission management and forces you to actually plan permissions more carefully. Some applications hard code permissions into groups. This means that if a user is part of a specific group (e.g. `news_create` for creating/modifying news articles) this user can perform the associated tasks. This permission check can either be done in the controller/specific method, router, dispatcher or additional permission handlers.

While this is very verbose especially in the controllers or permission handlers it may require a lot of groups for every specific action. This way of hard coding specific groups is only recommended for small applications where only a handful of permissions exist, but for large applications where many features need to be managed and also have to be managed on a very granular level this is not recommended (e.g. allow to create news article but not new news tags). 

![Group/Permission UML]({/base}/src/tpl/content/blog/dev/img/group_permission_uml.svg "Group/Permission UML")

### Accounts

While I still believe permissions should be always managed in groups instead of directly in users/accounts the implementation isn't too difficult since all that has to be done is assign permissions to accounts and merge them on the backend with the group permissions the account/user is part of. Alternatively the user can have a one-to-many relation with the groups and also iterate over all groups and check the existence of the permissions.

![Account/Permission UML]({/base}/src/tpl/content/blog/dev/img/account_permission_uml.svg "Account/Permission UML")

### Permission

The actual permission structure/flags highly depend on your application but I recommend the following permission structure (which should be defined as enums):

* Create = Allows to create something (e.g. value = 2)
* Read = Allows to read/see something (e.g. value = 4)
* Update = Allows to update/modify something (e.g. value = 8)
* Delete = Allows to delete/remove something (e.g. value = 16)
* Permission = Allows to change permissions (e.g. value = 32)

Additionally to the permissions it's also necessary to assign these permissions to categories in the application. What I generally use is the following structure:

* App = The application for this permission (usually either backend, frontend or api, depending on the application not required)
* Module = The module (e.g. news module, task module etc, depending on the application not required)
* Type = The type of the module (e.g. task_analysis, task_dashboard, task_settings)
* Element = The specific model id (e.g. task id)
* Component = The specific function (e.g. task badge)

With these categories it's easy to manage permissions very granular. All that's left is to decide how these categories should be checked. This means a user may not be allowed to delete news articles except one specific news article which he received permissions for. What happens if only the `App` is defined and all others are `null`? What happens if only `App` and `Component` are defined? In general the permission check should be done as follows:

1. Check if the user has any permission to perform a specific task, either globally, for the whole module, for a specific type etc. 
2. If the user has the permission at one level -> perform the operation.

#### Permission Check

The following permission check should be implemented in the account model.

```php
function hasPermission(int $permission, string $app = null, string $module = null, int $type = null, int $element = null, int $component = null) : bool
{
    // $this->permissions is an array of permissions of this account
    // $p is a model that contains the values for the categories, permission flags (bit mask e.g. READ = 2 | UPDATE = 4 | DELETE = 8)
    foreach ($this->permissions as $p) {
        if (($p->getApp() === $app || $p->getApp() === null || $app === null)
            && ($p->getModule() === $module || $p->getModule() === null || $module === null)
            && ($p->getType() === $type || $p->getType() === null || $type === null)
            && ($p->getElement() === $element || $p->getElement() === null || $element === null)
            && ($p->getComponent() === $component || $p->getComponent() === null || $component === null)
            && ($p->getPermission() | $permission) === $p->getPermission()
        ) {
            return true;
        }
    }

    // ... iterate over all groups if group and account permissions are not merged in the $this->permissions array

    return false;
}
```

The permission parameter is the actual permission we would like to check if the user has it (e.g. READ = 2). In order to prevent unexpected behavior it is recommended to always set the values of the previous values in the permission check (e.g. if you want to check against a specific element you should also set the type, module and app since a different module may have the same element identifier).

> Note: If you don't want to merge the account and group permissions into one permission array you can simple make the groups a member of the account and extend this method by additionally iterating over all groups and check the permissions.

