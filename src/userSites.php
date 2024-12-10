<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Alumni-Search-by-Location-assignment/src/MysqliDb.php';

class userSites {
    
    public function siteByUsers() {
       
        $conn = new MysqliDb('localhost','root','','assigndb');
        
        if (!$conn) {
            echo 'Connection Aborted';
        } else {
            
        $user_id = $_GET['user_id'];
        
        $query = "SELECT users.id as user_id,users.name as user_name,sites.site_name,sites.site_link FROM user_sites 
                  join users on users.id = user_sites.user_id
                  left join sites on sites.id = user_sites.site_id
                  WHERE user_sites.user_id = $user_id";
        
        $sites = $conn->query($query);
        
        echo '<table class="table align-middle" border="1" style="width:100%; border-collapse:collapse;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Name</th>';
        echo '<th>Site Name</th>';
        echo '<th>Site Link</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            
        foreach ($sites as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['user_name']) . '</td>';
            echo '<td><a href='.$row['site_link'].' target="_blank">' . htmlspecialchars($row['site_name']) . '</a></td>';
            echo '<td><a href='.$row['site_link'].' target="_blank">' . htmlspecialchars($row['site_link']) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        }
    }
}

$obj = new userSites();
$obj->siteByUsers();
?>
