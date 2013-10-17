restapi
=======

Pragmatic REST API Implementation on PHP

 Summary
 - Based on the documentation by APIGEE on Web API Design
 - request based on HTTP method 'GET', 'POST', 'PUT' and 'DELETE'

 Requirements:
 - PHP v5.3.0 or greater
 - apache mod_rewrite for removing index.php

 Installation Notes
 - modify .htaccess to point to correct document root
 - import sample database(restapi.sql)
 
 Configuration Files:
 - configs/default.php
 - configs/database.php

 Usage:
 - sample resource/controller is located in 'resources/users.php'
 - sample database model is located in 'models/mod_users.php'
 
 To access the API
 - authenticate first: http://myurl.com/v1/authorization/login
 - uses HTTP DIGEST AUTHENTICATION
 - response will generate the user id and token
 - sample resource http://myurl.com/v1/users
 - to request a resource, set the Authorization Header to: Authorization AUTH_HEADER_LABEL user_id token
     - AUTH_HEADER_LABEL set in configs/default.php
     - user_id is response from digest authentication
     - token is response from digest authentication
