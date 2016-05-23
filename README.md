# onlineShopping

A simple online shopping website specifically created for eyeglasses.


How to use:

1. Install xampp server.
2. clone this repository inside the htdocs folder of xampp
3. create an empty folder images inside the folder of this repository.
4. start the xampp server, turn on apache & MYSQL.
5. if a database called shops exists, drop it & then begin.
6. you can start the website by starting with welcomePage.php

Functionalities:
There are 3 types of users inside this database,
administrator, seller & customer.
All of thier data is saved inside a users table (no encryption) hence here is a massive security flaw.
Customer can order/bid items on the website.
Seller can sells items on place them on bid, after they get an administrator approval.
Administrator is the head of the database, the adminPage.php continually shows the nessecary stats of the database & 
has the right to approve/reject the items that are uploaded by the seller.

Database View:
users (userID int primary key auto_increment, userName varchar(50), userType char(1));
orders ( orderId int primarty key auto_increment, custId int references users(userId) , itemId references items(itemId) , quantity int )
items (itemId int primary key auto_increment, itemName varchar (50) , sellerId int references users(userId) , shape varchar (20),brand varchar(25), color varchar (30), price int , imgLoc varchar(50),category varchar(50),type char(1),custId int default null,status char(1) default 'N')

Description of attributes:
1.userId = an Id alloted to every user to quickly search & locate it.
2.userName = a unique name to ever user, if a same name is given during login, the user is warned & has to choose a different name.
  although userName itself can be used as a primary key, searching & sorting with numbers is much faster.
3.userType can be either 'A' - administrator , 'C' - customer , 'S' - seller.

4.orderId = id automatically generated upon an order to keep track of it.
5.custId = refers to the id of the customer who ordered the item, hence, to maintain consistency, a foreign key is used where the custId is derived from userId of users.
6.itemId = refers to the itemId derived from the items table, keeping this in the orders table helps us in finding any detail about the item.
7.quantity = is the amount of item(s) ordered by the user.
8.itemId = is an id that is automatically alloted to every item that is stored inside the database, it is used to search for an item as number wise searching is faster & 2 items may have the same name.
9. itemName = name of item, a search parameter.
10. sellerId refers to userId from users but the userType is a seller.
11. shape = is a search parameter.
12. brand = is the name of the brand of the item, it is also a simple search parameter.
13. color = color of the item, another search parameter.
14. price = price of the item.
15. category = search parameter, but a little complicated one.
16. type = defines whether the item is on a sale or on bidding.
17. imgLoc = stores the location of the place where the image is stored, the image is only uploaded to the server & it's location on the server is stored inside the database.
18.status = remains 'N' (rejected) until the admin approves it 'N' -> 'Y'
19. custId = remains null for all the items on sale, but will filled with a value when someone bids for it.
