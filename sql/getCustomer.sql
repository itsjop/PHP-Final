/* Select customers where the customerid is equal to a passed customer id */
select name, address, city, username from customers where customerid = :customerid