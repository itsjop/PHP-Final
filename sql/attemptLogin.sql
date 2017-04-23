/* Select customers where the username and password match those passed as parameters */
select customerid, name, city, address from customers where :username = username and :password = password
