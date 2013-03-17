Encrypted-Client-Side-Sessions
==============================

This is an example for storing PHP session data securely on the client browser in a cookie.

Why do this?
------------

PHP code can use session variables to store page by page session information about a visitor (for example, the ID of the currently logged in user).

This information is usually stored on the server. By default, this information is stored in a file, but it is also common to store session information in a database.

Storing session information on the client has a number of advantages, but the primary advantage which is of interest to me (and probably you) is that client side sessions simplify things somewhat when operating a site in a load balanced environment.

Requirements
------------

 * PHP 5.3
 * Mcrypt

Usage
-----

Override the default PHP session handler using session_set_save_handler before you start your session.

```php
session_set_save_handler(
        "ClientSideCookieSession::open_53",
        "ClientSideCookieSession::close_53",
        "ClientSideCookieSession::read_53",
        "ClientSideCookieSession::write_53",
        "ClientSideCookieSession::destroy_53",
        "ClientSideCookieSession::gc_53");
    
    session_start();
```
    
There's also a stub for the SessionHandler() override class, but you need to be running PHP 5.4 for that to work.

You should also call session_write_close(); before you send any non-header output to the client.

Limitations
-----------

 * Cookies can only contain a small amount of data, so you are limited in the amount of data you can send. A sensible maximum is 4000 bytes.
 * You must be done with sessions, i.e. call session_write_close() before you write any non-header output. This is less of a problem if you're using output buffering (like Elgg's template system.
 * Since sessions are stored client side, its obvious that you should keep your encryption key secure. Changing the key will destroy all current sessions (this may actually be a useful feature).
 
See
---

 * Marcus Povey <marcus@marcus-povey.co.uk>, http://www.marcus-povey.co.uk
