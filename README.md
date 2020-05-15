# Lyfestyle
<p align="center">
  <img src="https://raw.githubusercontent.com/Albert-C-Ong/Lyfestyle/master/assets/images/Lyfestyle_banner.png" width=500/>
</p>

## Overview
Lyfestyle: a calorie tracking app

## Installation
### Linux
#### 1. Install Apache, PHP, and MySQL
```
sudo apt-get install apache2
sudo apt-get install php libapache2-mod-php
sudo apt-get install mysql-server
```
#### 2. Restart Apache once the packages are installed
```
sudo systemctl restart apache2
```
#### 3. Install Git
```
sudo apt install git
```
Check git version to verify installation
```
git --version
```
#### 4. Clone the repository from Github to the directory /var/www/html
```
cd /var/www/html
git clone https://github.com/Albert-C-Ong/Lyfestyle.git
```
#### 5. Start apache and open Lyfestyle in your browser
```
sudo systemctl start apache2
```
Enter the following address into your internet browser
```
localhost/Lyfestyle
```

## Technologies and Tools
**Design**
* [Inkscape](https://inkscape.org/) // Art assets

**Front-end**
* HTML
* CSS
* [PHP](https://www.php.net/)

**Back-end**
* [MySQL](https://www.mysql.com/)

**Et Cetera**
* [GitHub](https://github.com/) // Version Control
* [Brackets](http://brackets.io/) // IDE
* [MySQL Workbench](https://www.mysql.com/products/workbench/)
* [phpmyadmin](https://www.phpmyadmin.net/)

## Acknowledgement
CMPE 133 Spring 2020 Team #20: [Gary Chang](https://github.com/1234momo), [Alisha Mehndiratta](https://github.com/alisha8899), and [Albert Ong](https://github.com/Albert-C-Ong). 
