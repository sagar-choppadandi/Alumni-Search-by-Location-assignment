<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Alumni-Search-by-Location-assignment/src/MysqliDb.php';

class User{
    
    public function users(){
        
        $conn = new MysqliDb('localhost','root','','assigndb');
    
        if (!$conn) {
            echo 'Connection Aborted';
        } else {
        
        $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : '';
        $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : '';
        $radius = isset($_GET['radius']) ? $_GET['radius'] : 10;
            
        if($latitude != '' && $longitude != '' && $radius != ''){
              $query = $this->getNearbyLocations($latitude, $longitude, $radius);
        }else{
              $query = "select * from users";
        }
        
        $users = $conn->query($query);
        
        echo '<table class="table align-middle" border="1" style="width:100%; border-collapse:collapse;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '<th>Latitude</th>';
        echo '<th>Longitude</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($user['id']) . '</td>';
            echo '<td><a href="userSites.php?user_id='.$user['id'].'">' . htmlspecialchars($user['name']) . '</a></td>';
            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
            echo '<td>' . htmlspecialchars($user['latitude']) . '</td>';
            echo '<td>' . htmlspecialchars($user['longitude']) . '</td>';
            echo '</tr>';
        }
            
            echo '</tbody>';
            echo '</table>';
            
       }
        
    }
    
    public function getNearbyLocations($latitude, $longitude, $radius)
    {
        $earthRadius = 6371;
        
        $query = "SELECT *,
        ($earthRadius * ACOS(
            COS(RADIANS($latitude)) * COS(RADIANS(users.latitude))
            * COS(RADIANS(users.longitude) - RADIANS($longitude))
            + SIN(RADIANS($latitude)) * SIN(RADIANS(users.latitude))
        )) AS distance
        FROM users
        HAVING distance <= $radius
        ORDER BY distance";
        
        return $query;
    }
}

$obj = new User();
$obj->users();


