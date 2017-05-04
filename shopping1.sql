DROP TABLE IF EXISTS Administrator;
DROP TABLE IF EXISTS OrderProduct;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Customers;
-- SHOULD WE HAVE A VISITOR TABLE?

CREATE TABLE Customers(
	CustomerID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR(50) NOT NULL,
	Email VARCHAR(100) NOT NULL,
	HashedPass VARCHAR(255) NOT NULL,
    Address VARCHAR(100),
    PhoneNumber VARCHAR(50),
	PRIMARY KEY(CustomerID)
);

INSERT INTO Customers(CustomerName, Email, HashedPass, Address, PhoneNumber)VALUES('Yun Ma','yun-ma@alibaba.com','1234567890', 'Hangzhou, Zhejiang', '88888888');

CREATE TABLE Orders(
    OrderID INT NOT NULL AUTO_INCREMENT,
    CustomerID INT UNSIGNED NOT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Payment INT NOT NULL,   -- amt of money that customer paid
    Address VARCHAR(100) NOT NULL,
    PrefDeliveryDate DATE,
    ActDeliveryDate DATE,
    Status VARCHAR(50) NOT NULL,
    -- four deliverty status: received, processed, delivered, returned--
	PRIMARY KEY(OrderID)
);

INSERT INTO Orders(CustomerID,Payment,Address,PrefDeliveryDate,Status) VALUES(1,88,'100 University Avenue, Riverside, CA',CURRENT_TIMESTAMP,'Received');

INSERT INTO Orders(CustomerID,Payment,Address,PrefDeliveryDate,Status) VALUES(1,87,'100 University Avenue, Riverside, CA',CURRENT_TIMESTAMP,'Shipped');

INSERT INTO Orders(CustomerID,Payment,Address,PrefDeliveryDate,Status) VALUES(1,89,'100 University Avenue, Riverside, CA',CURRENT_TIMESTAMP,'Delivered');

INSERT INTO Orders(CustomerID,Payment,Address,PrefDeliveryDate,Status) VALUES(1,80,'100 University Avenue, Riverside, CA',CURRENT_TIMESTAMP,'Returned');

CREATE TABLE Category(
    CategoryID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    CategoryName VARCHAR(100) NOT NULL,
    PRIMARY KEY (CategoryID)    
);

CREATE TABLE Products(
    ProductID INT NOT NULL AUTO_INCREMENT,
    CategoryID INT UNSIGNED NOT NULL,
    ProductName VARCHAR(50) NOT NULL,
	ProductDescription VARCHAR(100),
	UnitPrice INT NOT NULL,
    UnitCost INT NOT NULL,
    Unit VARCHAR(50) NOT NULL,
    Picture VARCHAR(128),
	PRIMARY KEY(ProductID)
    
);

CREATE TABLE OrderProduct(
    OPID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ProductID INT UNSIGNED NOT NULL,
    OrderID INT UNSIGNED NOT NULL,
    PRIMARY KEY(OPID)    
);

CREATE TABLE Administrator(
  AdID INT UNSIGNED NOT NULL AUTO_INCREMENT,
  AdminName VARCHAR(50) NOT NULL,
  Email VARCHAR(100) NOT NULL,
  HashedPass VARCHAR(255) NOT NULL,
  Address VARCHAR(100) NOT NULL,
  AdminLevel BOOLEAN NOT NULL,
  -- currently just make this strings "manager" or "staff" and if is manager the True
  PRIMARY KEY(AdID)
);

INSERT INTO Category(CategoryName) VALUES('Vegetables');
INSERT INTO Category(CategoryName) VALUES('Fruits');
INSERT INTO Category(CategoryName) VALUES('Asian');
INSERT INTO Category(CategoryName) VALUES('Snacks');

INSERT INTO Products(ProductName, CategoryID, ProductDescription, UnitPrice, UnitCost,Unit) VALUES("Annie's White Cheddar Popcorn, 4.4 Ounce(Pack of 12)", 4, "Made with Goodness No artificial flavors, synthetic colors or preservatives Organic cheese from cows raised without antibiotics or synthetic hormones Certified organic ingredients are grown without persistent pesticides",38.88,35.55,"pack");
