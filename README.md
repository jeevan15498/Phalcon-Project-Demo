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