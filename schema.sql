CREATE TABLE AA (
    userID INT,
    aid INT
);

CREATE TABLE account(
    userID PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(30) UNIQUE,
    password VARCHAR(30),
    fname VARCHAR(30),
    lname VARCHAR(30),
    email VARCHAR(30)
);


CREATE TABLE admin(
    userID INT PRIMARY KEY
);

CREATE TABLE allergen(
    aid INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30)
);

CREATE TABLE AM(
    userID INT,
    mealID INT,
    date datetime
);

CREATE TABLE IA(
    ingID INT,
    aid INT
);

CREATE TABLE ingredient(
    ingID int AUTO_INCREMENT,
    name VARCHAR(30)
);

CREATE TABLE meal(
    mealID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30),
    description VARCHAR(200),
    mealType VARCHAR(30),
    recipe VARCHAR(200),
    imagePath VARCHAR(200),
    calories INT
);

CREATE TABLE MI(
    mealID INT,
    ingID INT
);

CREATE TABLE rating(
    rid INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    mealID INT,
    rating INT,
    comment VARCHAR(200)
);
