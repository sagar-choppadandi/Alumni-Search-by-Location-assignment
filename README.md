README for Geo-Based User and Site Assignment Application

Database Overview

The database for this application, named assigndb, is designed to manage users, sites, and their relationships efficiently. It comprises three tables:

1. users Table

The users table stores detailed information about each user.

Columns:

id: A unique identifier for each user (Primary Key, int(11), NOT NULL).

name: Name of the user (varchar(100), NOT NULL).

email: Email address of the user (varchar(255), NOT NULL).

location: Physical location (e.g., city or country) (varchar(125), DEFAULT NULL).

latitude: Geographic latitude (decimal(10,8), NOT NULL).

longitude: Geographic longitude (decimal(11,8), NOT NULL).

created_at: Timestamp of record creation (timestamp, DEFAULT CURRENT_TIMESTAMP).

updated_at: Timestamp of last record update (timestamp, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP).

2. user_sites Table

This table manages the relationship between users and sites, allowing users to be associated with multiple sites.

Columns:

id: A unique identifier for each record (Primary Key, int(11), NOT NULL).

user_id: ID of the user (Foreign Key referencing users.id, int(11), NOT NULL).

site_id: ID of the site (Foreign Key referencing sites.id, int(11), NOT NULL).

created_at: Timestamp of record creation (timestamp, DEFAULT CURRENT_TIMESTAMP).

3. sites Table

The sites table stores all site information.

Columns:

id: A unique identifier for each site (Primary Key, int(11), NOT NULL).

site_name: Name of the site (varchar(100), NOT NULL).

site_link: URL of the site (varchar(2083), DEFAULT NULL).

created_at: Timestamp of record creation (timestamp, DEFAULT CURRENT_TIMESTAMP).

updated_at: Timestamp of last record update (timestamp, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP).

Application Features

1. User List Display

The User.php class is responsible for displaying a list of users.

It uses a MySQLi database connection class to fetch user details and the sites assigned to them.

Clicking on a username displays detailed information about the sites assigned to that user.

2. Filter Functionality

Users can filter results to display users geographically close to a specified location.

Query string parameters for filtering:

latitude: Latitude of the location.

longitude: Longitude of the location.

radius: Radius within which to search.

Example:

http://localhost/Alumni-Search-by-Location-assignment/src/user.php?latitude=17.43258940&longitude=78.40706910&radius=5

3. Opening Links in a New Tab

Clicking on a site link opens the site in a new browser tab.

API Endpoints

API functionalities are implemented in the /api folder.

1. UpdateUser.php

Updates user information such as name, email, and location.

Utilizes the MySQLi database connection class.

Accepts parameters for updating the user and responds in JSON format.

2. searchByLocation.php

Searches for users within a specified radius based on geographic coordinates.

Parameters:

latitude

longitude

radius

Implements a getNearbyLocations() function to calculate distances and filter users based on the specified radius.

Response Format

All API endpoints return data in JSON format for easy testing and integration.

Testing API Endpoints

Use Postman to test the following endpoints:

UpdateUser.php

searchByLocation.php

A Postman collection for testing these APIs is available in the Documentation Folder.

File Structure

/src
   - Contains all web pages and classes for the application.
/api
   - Contains API files for updating users and geolocation-based search.
/Documentation
   - Includes the Postman collection and additional documentation.

Usage Guide

Creating a Database Connection

A reusable MySQLi database connection class is included to manage database connections.

Include this file in all pages to establish a connection automatically.

Updating User Information

Use the UpdateUser.php API to update user details.

Pass parameters such as name, email, and location.

Verify updates using Postman or the application interface.

Filtering Users by Location

Access the searchByLocation.php endpoint.

Provide latitude, longitude, and radius as query parameters.

View the list of users within the specified radius.

Notes

Ensure that the assigndb database and its tables are set up before using the application.

Test all API functionalities with the provided Postman collection.

Follow the specified folder structure for seamless integration and execution.

