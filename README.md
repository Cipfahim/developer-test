# Really Simple Systems Developer Test

This test has been designed to allow us to see your Laravel and Vue knowledge.
If you have any questions regarding this test you can email dev@reallysimplesystems.com, and we will answer any questions that you may have.

We recommend taking 1-2 hours to complete the tasks, remember that if in this time you don't complete all the tasks this won't have a negative effect on your result, this test is about your approach and how you aim/aimed to tackle the problem at hand.

Using the project provided we want you to create a mini CRM, using Laravel, Inertia.js and Vue.js.

Once you have the repository cloned onto your machine, run the following commands:

```composer install```

```npm install```

```npm run dev```

Once you have completed the Database and Model tasks run:

```php artisan migrate --seed```

There  are a set of tests implemented for you to check your work against, you will need to add a file called ```database.sqlite``` to the ```database directory```. Once you have done this you can run them at any time using:

```php artisan test```

### Tasks

#### Database
- Add ```owner_id``` as a foreign key to accounts table where ```owner_id === user.id```
- Add ```account_id``` as a foreign key to contacts table that cascades when an account is deleted

#### Models
- Add an eloquent relationship to the ```User``` model for a user to have multiple accounts associated with it
- Add an eloquent relationship to the ```Account``` model for an account to have an owner
- Add an eloquent relationship to the ```Account``` model for an accounts to have multiple contacts associated with it
- Add an eloquent relationship to the ```Contact``` model for a contact to have an account

#### Routes
- Make the welcome view only accessible if the user is unauthenticated
- Make the dashboard and all the account and contact related routes only accessible to an authenticated user

#### Controllers
- Implement the method stubs on the ```AccountController``` the associated front end Vue components are available in ```js/Pages/Accounts```
- Implement the method stubs on the ```ContactController``` the associated front end Vue components are available in ```js/Pages/Contacts```

#### UI
- Display the account and contact data in the tables in the ```Index.vue``` skeleton files (don't worry about pagination)
- Display the account and contact data in the description area in the ```Show.vue``` skeleton files
- Bind all inputs and implement form submission functionality in the ```Create.vue``` and ```Edit.vue``` skeleton files

### Optional Tasks (only if you have time)
- Add a middleware to only allow active user requests, logging a user out and returning them to login if they are inactive
- Add a create user command