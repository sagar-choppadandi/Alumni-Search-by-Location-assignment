<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Alumni-Search-by-Location-assignment/src/MysqliDb.php';

class UpdateUser
{
    public function update()
    {
        try {
            
            $conn = new MysqliDb('localhost','root','','assigndb');
            
            if (!$conn) {
                echo 'Connection Aborted'; exit();
            }
            
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                
                // Decode incoming JSON payload
                $input = json_decode(file_get_contents("php://input"), true);
                
                // Sanitize and validate inputs
                $userId = isset($input['id']) ? intval($input['id']) : null;
                $name = isset($input['name']) ? trim($input['name']) : null;
                $email = isset($input['email']) ? trim($input['email']) : null;
                $location = isset($input['location']) ? trim($input['location']) : null;
                $latitude = isset($input['latitude']) ? trim($input['latitude']) : null;
                $longitude = isset($input['longitude']) ? trim($input['longitude']) : null;
                
                if (!$userId || !$name || !$email || !$location || !$latitude || !$longitude) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Invalid input. All fields are required.']);
                    exit;
                }
                
                $tableData = [
                    'name' => $name,
                    'email' => $email,
                    'location' => $location,
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ];
                
                $conn->where('id', $userId);
                
                if ($conn->update('users', $tableData)) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'User details updated successfully',
                        'affected_rows' => $conn->count
                    ],200);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update user details: ' . $conn->getLastError()
                    ]);
                }
                
            } else {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Only PUT is allowed.']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
}

$obj = new UpdateUser();
$obj->update();
