<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Alumni-Search-by-Location-assignment/src/MysqliDb.php';

class searchByLocation
{
    public function search()
    {
        try {
            $conn = new MysqliDb('localhost','root','','assigndb');
            
            if (!$conn) {
                echo 'Connection Aborted';
            } else {
            
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                
                // Decode incoming JSON payload
                $input = json_decode(file_get_contents("php://input"), true);
                
                // Sanitize and validate inputs
                $radius = isset($input['radius']) ? floatval(trim($input['radius'])) : null;
                $latitude = isset($input['latitude']) ? floatval(trim($input['latitude'])) : null;
                $longitude = isset($input['longitude']) ? floatval(trim($input['longitude'])) : null;
                $page = isset($input['page']) ? intval(trim($input['page'])) : 1; // Default page is 1
                $perPage = isset($input['per_page']) ? intval(trim($input['per_page'])) : 10; // Default items per page is 10
                $offset = ($page - 1) * $perPage;
                
                // Validate the required fields
                if (!$radius || !$latitude || !$longitude) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Invalid input. All fields are required.']);
                    exit;
                }
                
                // Build the query based on inputs
                if($latitude != '' && $longitude != '' && $radius != ''){
                    
                    // Prepare the query for getting nearby locations with distance calculation
                    $query = $this->getNearbyLocations($latitude, $longitude, $radius, $offset, $perPage);
                }else{
                    $query = "select * from users LIMIT $offset, $perPage";
                }
                
                // Execute the query
                $users = $conn->query($query);
                
                // Prepare results
                $results = [];
                foreach ($users as $user){
                    $results = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'location' => $user['location'],
                        'latitude' => $user['latitude'],
                        'longitude' => $user['longitude']
                    ];
                }
                
                // Return response
                if (!empty($results)) {
                        http_response_code(200);
                        echo json_encode([
                                'status' => 'success',
                                'data' => $results,
                                'message' => 'Users Info',
                                'page' => $page,
                                'per_page' => $perPage
                            ]);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'No data Found!']);
                }
            } else {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Only PUT is allowed.']);
            }
          }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
    
    public function getNearbyLocations($latitude, $longitude, $radius, $offset, $perPage)
    {
        // Prepare the query for getting nearby locations with distance calculation
        
        $earthRadius = 6371; //Default (Kilometers)
        
        $query = "SELECT id,name,email,location,latitude,longitude,
        ($earthRadius * ACOS(
            COS(RADIANS($latitude)) * COS(RADIANS(users.latitude))
            * COS(RADIANS(users.longitude) - RADIANS($longitude))
            + SIN(RADIANS($latitude)) * SIN(RADIANS(users.latitude))
        )) AS distance
        FROM users
        HAVING distance <= $radius
        ORDER BY distance
        LIMIT $offset, $perPage"; // optimization for query loading performance Add LIMIT and OFFSET for pagination 
        
        return $query;
    }
}

$obj = new searchByLocation();
$obj->search();
