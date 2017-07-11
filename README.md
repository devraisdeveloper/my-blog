My Blog
============================

This is my attempt to create a blog and experiment with Yii2 framework.

Description

There are people who can post text, image, video posts into the blog. Actually all their roles as
admins and they can invite (add) other people to the group. Also there should be user-friendly
interface to manage the posts and users. Anonymous users should have possibility to leave their
comments.

- Yii2 Framework
- MySQL
- AJAX
- HTML and CSS

DOWNLOAD PROJECT
-------------------

Download url: 

~~~
https://github.com/devraisdeveloper/my-blog.git
~~~

You can clone or download a zip-folder.


INSTALLATION
------------

### Create a virtual host

```
<VirtualHost *:80>
        ServerAdmin admin@blog.com
        ServerName blog.com
        ServerAlias blog.com
        DocumentRoot /var/www/blog.com/public_html/blog/web
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


### Download "Vendor" files to project

~~~
composer update
~~~

### Add permissions
~~~
/runtime

/web/assets

/web/images
~~~


### Open main page

Main url for project should be :
~~~
http://blog.com
~~~

In case you have problems with pretty urls you can disable them
This can be done /config/web.php

This is how you can disable:

```php
        'urlManager' => [
            'enablePrettyUrl' => false,
        /*    'showScriptName' => false,
            'rules' => [
            ],*/
        ],
```


CONFIGURATION
-------------

### Database

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.

You will use "SocialData" database to store your data

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=YourDatabaseName',
    'username' => 'YourUserName',
    'password' => 'YourPassword',
    'charset' => 'utf8',
];
```

### Create tables

In order to create tables you will need to use migrations

~/Documents/www/blog.com/public_html$ ./yii migrate/up

Code for migrations is in floder : /migrations


Rights
---------------------

Guest users can view index pages and view posts. Also can comment

Authenticated users can create, update and delete ......

In order to become a user use "Register" link located in the header

Authenticated users can add other users (bloggers) to communities. You need to be a member
in order to see community-posts.

### Additional info 

Yii2 framework uses MVC pattern. All code is in corresponding folders respectfully.
Also jquery and css files are located in web folder : /web

If you have a project path with additional subfolders you will encounter difficulties when attempting to add a comment. My jquery functions are not supposed solve dynamic rooting problems.
Example:

Get Url: http://blog.com/post/comment?id=1

You will need to hardcode subfolders to this path in web/js/j.js.

~~~
function openCommentForm(postId){
   $('#modal-'+ postId).modal('show');
 $('#modalContent-' + postId).load('/post/comment?id='+ postId);
 ~~~
