DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_username` varchar(255) NOT NULL, 
    `user_password` varchar(255) NOT NULL
);

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
    `book_id` INT AUTO_INCREMENT PRIMARY KEY,
    `book_title` varchar(255) NOT NULL, 
    `book_genre` varchar(255) NOT NULL,
    `book_user` varchar(255) NOT NULL,
    `book_location` varchar(255) NOT NULL,
    `book_buyprice` INT(255) NOT NULL,
    `book_rentprice` INT(255) NOT NULL,
    `book_image` varchar(255) NOT NULL,
    `book_rentdue` varchar(255) NOT NULL,
    `book_rentduration` varchar(255) NOT NULL,
    `book_description` varchar(255) NOT NULL,
    `book_user_image` varchar(255) NOT NULL,
    `book_condition` varchar(255) NOT NULL
);

INSERT INTO books(
    book_title, book_genre, book_user, book_location, 
    book_buyprice, book_rentprice, book_image, 
    book_rentdue, book_rentduration, book_description, 
    book_user_image, book_condition
)
VALUES 
(
    "Philippine Politics & Governance",
    "Academic",
    "Anton Magbanua",
    "City of Kabankalan",
    320,
    50,
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/img4.jpg",
    "week",
    "2 months",
    "Sample Desc",
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/Anton.jpg",
    "Used"
),
(
    "Everyday Life in World Literature 10",
    "Academic",
    "Argian Cortez",
    "Talisay City",
    350,
    50,
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/img3.jpg",
    "week",
    "2 months",
    "Sample Desc",
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/Argian.jpg",
    "New"
),
(
    "Kayamanan",
    "Academic",
    "Russ Allen Garde",
    "Bacolod City",
    300.00,
    50,
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/img2.jpg",
    "week",
    "2 months",
    "Sample Desc",
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/Russ.jpg",
    "Used"
),
(
    "Pinagyamang Pluma 9",
    "Academic",
    "Romeo Seva III",
    "Silay City",
    350.00,
    50,
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/img1.png",
    "week",
    "2 months",
    "Sample Desc",
    "https://raw.githubusercontent.com/RJSeebs02/LibrooImages/main/Romeo.jpg",
    "Slightly Used"
);

DROP TABLE IF EXISTS `carting`;
CREATE TABLE `carting` (
    `cart_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(10) NOT NULL,
    `cart_price` INT(255) NOT NULL
);

DROP TABLE IF EXISTS `carting_item`;
CREATE TABLE `carting_item` (
    `carting_item_id` INT AUTO_INCREMENT PRIMARY KEY,
    `cart_id` INT(10) NOT NULL,
    `book_id` INT(10) NOT NULL,
    `product_quantity` INT(255) NOT NULL,
    KEY (`cart_id`),
    KEY (`book_id`)
);
