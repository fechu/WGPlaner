# Access Control List (ACL)

This document describes how the ACL is setup and works within the WGPlaner application. 

# Webapp

The webapp uses `Zend\Permission\Acl` for access right management. `Application\Acl\Acl` is a subclass of `Zend\Permission\Acl` and prepares all roles, resources and rules. The Application module then sets it up as a service in the `serviceManager`. 

In the `Module.php` file of the Application module is also a small listener registerd to listen on the `route` event. It then checks if the route contains an `accountid` parameter. If yes, a check with the ACL is performed automatically and it is checked if the user has access to that account. 


# Api

The API currently manages its own Access right management. This is very easy and depends only on the account which is referenced with the `accountid` parameter. It is then manually checked if the user which is logged in is allowed to access that account.