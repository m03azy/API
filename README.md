# API
This is a simple REST API programs that performs CRUD operations on blogs that must includes users,posts and comments under GET,POST,PUT and DELETE.

in order to perform those operations use it is better to use API testing tools like Postman or RestClient

in user.php file, there is a list of users in database tables which having userId,FirstName,LastName and age
in post.php file, there is a list of post in database tables which having postId(primary key),userId(foreign key refered to users),title,body and time
in comment.php file, there is a list of comments in database tables which having commentId(pimary key),postId(foreign key),title,comments and time
