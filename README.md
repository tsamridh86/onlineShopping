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

Various Pages:
1. welcomePage.php
2. loginPage.php
3. signUp.php
4. search.php
5. orderPage.php
6. orderComplete.php
7. sellerPage.php
8. adminPage.php
9. NLI.php
10. config.php

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

Page functionalities :
1. welcomePage.php = this is where the site should begin, in other words, this is the homepage of the website. It has
    a. search bar, that searches items on the basis of thier itemName attribute.(name = query)
    b. navigation bar just below it, that searches on the basis of thier category attribute.(name = cat)
    c. a place for the logo of the page.
    d. a place for the login/logout tab for the user.
    e. a div to display now trending items. (Algorithm details in another file)

2. loginPage.php = this page is used to login to the website if the userName & password is correct. It includes a simple form only. (algorithms details in another file)

3. signUp.php = this page is used to login to the website & add entry to the database if & only if one chooses a unique userName.

4. search.php = this page containes the obtained search data & various search parameters for next search. All the searches performed lead to this page only, the sidebar contains search by:
    a.color
    b.shape
    c.brand
    d.seller
    e.category
    f.type

5.orderPage.php = when a user presses the order button it is always redirected here. This page will contain the quantity of the items to be bought or the new amount that needs to be placed if the item is on bid.

6.orderComplete.php = when a user is done with ordering or the bidding of the item, this page generates a type of reciept that is shown to the user, this page has a redirect link to the welcomePage.php.

7.sellerPage.php = this page is for the seller, a seller can upload it's item either for sale or for bid. However, every item that has been uploaded needs a permission from the admin to be seen in the search results. This page also contains a delete option, where a seller may choose to delete the items it had previously uploaded. Finally, it shows a list of orders that have been issued & the on going bidding.

8. adminPage.php = adminPage shows all the stats of the page & it also has all previliges both customer & seller have. This page generally shows stats only & can approve the requests of the seller that want to upload thier items.

9. NLI.php = This page denotes that it is not logged in, this page simply destroys a session & asks whether the user want to leave or the re-enter the page.

10. config.php = this page contains the various connectivity codes that are required to connect to the database.
