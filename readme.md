
## About Coupon Demo App

This is a demo project to cover rudimentary admin & normal user requirements in a simple couponing system.

**Admin Actions:**
* List Active Coupons (with multiple filtering and ordering on all fields)
* Create new coupon (Normal Code & Unique Code)
* Update a coupon
* Show a coupon information
* Delete a coupon

*Note*: The coupons that used by users (already have users) could not be deleted and their codes are not editable.

**User Actions:**
* List Active Coupons (with multiple filtering and ordering on all fields)
* Show a coupon information (and it assigned to the user)

## Installation

Clone the project and install it:
```
git clone https://github.com/khalilst/coupon.git
composer install
cp .env.example .env
````

Put your desired configurations inside `.env` file, `database` & `queue` configurations.
Then run the following commands:
```
php artisan migrate
php artisan passport:install
```


**Default users**

To create Admin and two fake normal users run the following command:
```
php artisan db:seed --class UsersTableSeeder
```


Now you have the following users:
```
1. Admin: admin@coupon.test
2. Normal User: user1@coupon.test
2. Normal User: user2@coupon.test
```
The password is `password` for all users.


**Fake Data**

To generate default users plus fake `categories`, `brands` & `coupons` run the following command:
```
php artisan db:seed
```


**Codes Helper Command**

To generate a codes file with thousands of lines run the following command:
```
php artisan make:codes codes.txt
```
It will generate a file in `storage/codes/codes.txt` with 500,000 random codes per each line.

However you can change the number of codes:
```
php artisan make:codes codes.txt --count=1000000
```


## API Documentation
Please import `Postman` collection and evironment json files from project folder.

You can find couple of examples for available routes, just consider the `environment` variables to have correct values.

For the sake of *ease*, first run the `login` API to fill up the `Bearer Token` variable **automatically**, then use other APIs.