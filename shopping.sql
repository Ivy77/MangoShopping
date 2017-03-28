DROP TABLE IF EXISTS Customers;
DROP TABLE IF EXISTS Products;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderProduct;
DROP TABLE IF EXISTS Administrator;
--SHOULD WE HAVE A VISITOR TABLE?

CREATE TABLE Customers(
	CustomerID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR() NOT NULL,
	Email VARCHAR(100) NOT NULL,
	HashedPass VARCHAR(255) NOT NULL,
    Address VARCHAR(100),
    PhoneNumber INT,
	PRIMARY KEY(CustomerID)
);

CREATE TABLE Orders(
	OrderID INT NOT NULL AUTO_INCREMENT,
    CustomerID INT UNSIGNED NOT NULL,
    OrderDate Date DEFAULT GETDATE(), 
	Payment Int NOT NULL,   -- amt of money that customer paid
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
	PRIMARY KEY(ProductID)
    
);


CREATE TABLE OrderProduct(
    OPID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ProductID INT UNSIGNED NOT NULL,
    OrderID INT UNSIGNED NOT NULL,
    PRIMARY KEY (OPID)    
);

CREATE TABLE Administrator(
    AdminID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    AdminName VARCHAR() NOT NULL,
	Email VARCHAR(100) NOT NULL,
	HashedPass VARCHAR(255) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    AdminLevel INT NOT NULL,    --will do something with this to manage what this person can do.....
    PRIMARY KEY (AdminID)    
);


