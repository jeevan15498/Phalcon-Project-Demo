# Phalcon Project

https://github.com/jeevan15498/Phalcon-Project-Demo

## Processing Topics

* Bootstrap CSS framework use /layouts
* include part of footer, header
* ACL (get roles (admin, guest, user) from databases)
* ajax post or get sample
* multi-language support
* 404 error for If controller or action not exits.

## Completed Topics

* Bootstrap CSS framework use `/layouts` without use footer, header files

    | User Types | Routes | Layout File |
    | ---------- | ------ | ----------- |
    | Guest | `/` | `app\views\layouts\guestLayout.volt` |
    | User |  `/admin` | `app\views\layouts\userLayout.volt` |
    | Admin | `/user` | `app\views\layouts\adminLayout.volt` |

* ACL (get roles (admin, guest, user) from databases)
    - https://docs.phalcon.io/3.4/en/tutorial-base#designing-a-sign-up-form
    - Design Login and Sign Up Template `(User Role = 1, Admin Role = 2)`
    - Create a new database in phpmyadmin `phalcon-demo`
    - Create a new database table `users` in the `phalcon-demo` database
        ```sql
        CREATE TABLE `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` text NOT NULL,
        `email` text NOT NULL,
        `password` text NOT NULL,
        `role` text NOT NULL,
        `active` int(11) NOT NULL DEFAULT '0',
        `created` text NOT NULL,
        `updated` text NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1   
        ```
    - Set database in the phalcon `app/config/config.php` file
    - Create `users` model in phalcon shell `'phalcon create-model users --get-set --mapcolumn'`
    - https://docs.phalcon.io/3.4/en/validation#validation
    - Update Validation Class Name in users model
    - Check Signup Form
    - Create Login Method in `IndexController.php` file
    - https://docs.phalconphp.com/en/3.3/session#start
