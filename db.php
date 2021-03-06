
  <?php
	function db_connect()
  {
		$dbhost = "localhost";
		$dbport = 3306;
		$dbName = "schedule";
		$username = "root";
		$password = "";
		// Create connection
		$connection = new PDO("mysql:host=$dbhost;dbname=$dbName;port=$dbport", $username, $password,
		[
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		]);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $connection;
  }

	function getFloors($connection, $building, $floor) {
		$result = false;
		$floorStatement = "SELECT map
				FROM floorMap
				WHERE building LIKE :b AND floor = :f";
		$query = $connection->prepare($floorStatement);
		$query->execute(['b' => $building, 'f' => $floor]);
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]['map'];
	}

	function getRooms($connection, $building, $floor) {
		$result = false;
		$statement = "SELECT room, type, seatsCnt, computers, whiteBoard
				FROM rooms
				WHERE building LIKE :b AND floor = :f";
		$query = $connection->prepare($statement);
		$query->execute(['b' => $building, 'f' => $floor]);
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	function getRoomsTakenDate($connection, $date) {
		$result = false;
		$statement = "SELECT building, room, floor, title, type, lecturer, speciality, groupAdm, year, date, duration
				FROM roomTaken
				WHERE :d BETWEEN date AND DATE_ADD(date, INTERVAL duration HOUR)";
		$query = $connection->prepare($statement);
		$query->execute(['d' => $date]);
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}


	function get($connection, $statement) {
		$result = false;
		$query = $connection->prepare($statement);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	function post($connection, $statement, $data) {
		$result = false;
	 	$query = $connection->prepare($statement);
	 	$query->execute($data);
	}

	?>
