<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

class loggedInUser {
	public $email = NULL;
	public $hash_pw = NULL;
	public $user_id = NULL;
	
	//Simple function to update the last sign in of a user
	public function updateLastSignIn()
	{
		global $mysqli,$db_table_prefix;
		$time = time();
		$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
			SET
			last_sign_in_stamp = ?
			WHERE
			id = ?");
		$stmt->bind_param("ii", $time, $this->user_id);
		$stmt->execute();
		$stmt->close();	
	}
	
	//Return the timestamp when the user registered
	public function signupTimeStamp()
	{
		global $mysqli,$db_table_prefix;
		
		$stmt = $mysqli->prepare("SELECT sign_up_stamp
			FROM ".$db_table_prefix."users
			WHERE id = ?");
		$stmt->bind_param("i", $this->user_id);
		$stmt->execute();
		$stmt->bind_result($timestamp);
		$stmt->fetch();
		$stmt->close();
		return ($timestamp);
	}
	
	//Update a users password
	public function updatePassword($pass)
	{
		global $mysqli,$db_table_prefix;
		$secure_pass = generateHash($pass);
		$this->hash_pw = $secure_pass;
		$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
			SET
			password = ? 
			WHERE
			id = ?");
		$stmt->bind_param("si", $secure_pass, $this->user_id);
		$stmt->execute();
		$stmt->close();	
	}
	
	//Update a users email
	public function updateEmail($email)
	{
		global $mysqli,$db_table_prefix;
		$this->email = $email;
		$stmt = $mysqli->prepare("UPDATE ".$db_table_prefix."users
			SET 
			email = ?
			WHERE
			id = ?");
		$stmt->bind_param("si", $email, $this->user_id);
		$stmt->execute();
		$stmt->close();	
	}
	
	//Is a user has a permission
	public function checkPermission($permission)
	{
		global $mysqli,$db_table_prefix,$master_account;
		
		//Grant access if master user
		
		$stmt = $mysqli->prepare("SELECT id 
			FROM ".$db_table_prefix."user_permission_matches
			WHERE user_id = ?
			AND permission_id = ?
			LIMIT 1
			");
		$access = 0;
		foreach($permission as $check){
			if ($access == 0){
				$stmt->bind_param("ii", $this->user_id, $check);
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0){
					$access = 1;
				}
			}
		}
		if ($access == 1)
		{
			return true;
		}
		if ($this->user_id == $master_account){
			return true;	
		}
		else
		{
			return false;	
		}
		$stmt->close();
	}
	
	//Logout
	public function userLogOut()
	{
		destroySession("userCakeUser");
	}	

	public function getApis()
	{
		global $mysqli,$db_table_prefix;

                $personal = array();
                $group = array();

                $sql = "SELECT u.id id, u.user_name name, d.design design 
                                         FROM ".$db_table_prefix."users u, ".$db_table_prefix."designs d 
                                         WHERE d.user_id IS NOT NULL AND d.user_id = u.id AND d.user_id = ?";
		$stmt = $mysqli->prepare($sql); 
		$stmt->bind_param("i", $this->user_id);
                $success = $stmt->execute();
                if (!$success) {
                   die();
                }
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                     array_push($personal, $row);
                  }
                }
		$stmt->close();
		$stmt = $mysqli->prepare("SELECT u.id id, u.user_name name, p.name 'group', d.design design 
                                          FROM ".$db_table_prefix."users u, ".$db_table_prefix."permissions p, ".$db_table_prefix."user_permission_matches m, ".$db_table_prefix."designs d 
                                          WHERE u.id = m.user_id AND p.id = m.permission_id AND u.id = ? 
                                                AND  d.permission_id IS NOT NULL AND d.permission_id = p.id
                                                ORDER BY 'group'"); 
		$stmt->bind_param("i", $this->user_id);
                $success = $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                     $arr = null;
                     if (!isset($group[$row['group']])) {
                        $arr = array();
                     } else {
                        $arr = $group[$row['group']];
                     }
                     array_push($arr, $row);
                     $group[$row['group']] = $arr;

                  }
                }
		$stmt->close();
                $apis = array( 'personal' => $personal, 'group' => $group);
                return $apis;
	}

	public function createNewApi($api, $group)
	{
		global $mysqli,$db_table_prefix;

                $errors = array();
                
                $base = dirname(__DIR__).'/spec-files/';
                if (file_exists($base.$api.'.yaml')) {
                    $design = null;
                    $stmt = $mysqli->prepare("SELECT user_id, permission_id, design FROM ".$db_table_prefix."designs d 
                                              WHERE d.design = ?");
                    $stmt->bind_param("s", $group);
                    $success = $stmt->execute();
                    $success = $stmt->bind_result($design);
                    $stmt->fetch();
                    $stmt->close();
                    if ($design != null && $design == $group) {
                       array_push($errors, 'Api with name "'.$api.'" already exists.');
                       return $errors;
                    }
                } else { 
                   if (!file_exists($base.'default.yaml') || !is_file($base.'default.yaml')) {
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error 404.');  
                       return $errors;
                    }
                    if (!is_readable($base.'default.yaml')) {
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error 403.');  
                       return $errors;
                    }
                    $success = copy($base.'default.yaml', $base.$api.'.yaml');
                    if ($success == FALSE) {
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error: 500.');  
                       return $errors;
                    }
                }
                $personal = $group == null || $group == 'personal';

                $permission_id = null;
                if (!$personal) {
                    $stmt = $mysqli->prepare("SELECT p.id FROM ".$db_table_prefix."users u, ".$db_table_prefix."permissions p, ".$db_table_prefix."user_permission_matches m 
                                              WHERE u.id = m.user_id and p.id = m.permission_id and p.name = ? and u.id = ?");
                    $stmt->bind_param("si", $group, $this->user_id);
                    $success = $stmt->execute();
                    if ($success) {
                       $stmt->close();
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error: 1000.');  
                       return $errors;
                    }
                    $success = $stmt->bind_result($permission_id);
                    if ($success) {
                       $stmt->close();
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error: 1002.');  
                       return $errors;
                    }
                    $stmt->fetch();
                    if ($success != TRUE) {
                       $stmt->close();
                       array_push($errors, 'Cannot create new api with name "'.$api.'". Error: 1003.');  
                       return $errors;
                    }
                    $stmt->close();
                } 
                $stmt = $mysqli->prepare("INSERT INTO ".$db_table_prefix."designs (" . ($personal? 'user_id' : 'permission_id') . " , design) values (?, ?)");

                $stmt->bind_param("is", ($personal ? $this->user_id : $permission_id ), $api);
                $success = $stmt->execute();
                if (!success) {
                   $stmt->close();
                   array_push($errors, 'Cannot create new api with name "'.$api.'". Error: 1001.');  
                   return $errors;
                } 
                $inserted_id = $mysqli->insert_id;
                $stmt->close();
                return $errors;
	}
}

?>
