DROP TABLE IF EXISTS Administrator;
DROP TABLE IF EXISTS OrderProduct;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS Customers;
--SHOULD WE HAVE A VISITOR TABLE?

CREATE TABLE Customers(
	CustomerID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR(50) NOT NULL,
	Email VARCHAR(100) NOT NULL,
	HashedPass VARCHAR(255) NOT NULL,
    Address VARCHAR(100),
    PhoneNumber INT,
	PRIMARY KEY(CustomerID)
);

CREATE TABLE Orders(
	OrderID INT NOT NULL AUTO_INCREMENT,
    CustomerID INT UNSIGNED NOT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	Payment INT NOT NULL,   -- amt of money that customer paid
	HashedPass VARCHAR(255) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    PrefDeliveryDate DATE,
    SetDeliveryDate DATE NOT NULL,
    ActDeliveryDate DATE,
    Status VARCHAR(50) NOT NULL,
	PRIMARY KEY(OrderID)
);

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
  AdminLevel INT NOT NULL,
  PRIMARY KEY(AdID)
);

INSERT INTO Category(CategoryName) VALUES('Vegetables');
INSERT INTO Category(CategoryName) VALUES('Fruits');
INSERT INTO Category(CategoryName) VALUES('Asian');
INSERT INTO Category(CategoryName) VALUES('Snacks');

INSERT INTO Products(ProductName, CategoryID, ProductDescription, UnitPrice, UnitCost,Unit) VALUES("Annie's White Cheddar Popcorn, 4.4 Ounce(Pack of 12)", 4, "Made with Goodness No artificial flavors, synthetic colors or preservatives Organic cheese from cows raised without antibiotics or synthetic hormones Certified organic ingredients are grown without persistent pesticides",38.88,35.55,"pack");
