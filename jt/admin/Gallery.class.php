<?php

	require_once "DataObject.class.php";

	class Gallery extends DataObject {

		protected $data = array(
			"ImageID" => "",
			"ImageFile" => "",
			"ImageDescription" => "",
			"MenuTitle" => "",
			"MenuCategory" => "",
			"MenuPrice" => ""

		);



		public static function showGallery( $startRow, $numRows, $order ) {
			$conn = parent::connect();
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_GALLERY . " ORDER BY $order LIMIT :startRow, :numRows";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":startRow", $startRow, PDO::PARAM_INT );
				$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
				$st->execute();
				$Gallery = array();
				foreach ( $st->fetchAll() as $row ) {
					$Gallery[] = new Gallery( $row );
				}
				$st = $conn->query( "SELECT found_rows() as totalRows" );
				$row = $st->fetch();
				parent::disconnect( $conn );
				return array( $Gallery, $row["totalRows"] );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

		public static function getImage( $ImageID ) {
			$conn = parent::connect();
			$sql = "SELECT * FROM " . TBL_GALLERY . " WHERE ImageID = :ImageID";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageID", $ImageID, PDO::PARAM_INT );
				$st->execute();
				$row = $st->fetch();
				parent::disconnect( $conn );
				if ( $row ) return new Gallery( $row );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

		public static function getMenuItem( $ImageID ) {
			$conn = parent::connect();
			$sql = "SELECT * FROM " . TBL_GALLERY . " WHERE ImageID = :ImageID";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageID", $ImageID, PDO::PARAM_INT );
				$st->execute();
				$row = $st->fetch();
				parent::disconnect( $conn );
				if ( $row ) return new Gallery( $row );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}


		public function insert() {
			$conn = parent::connect();
			$sql = "INSERT INTO " . TBL_TOUR . " (
					TourDate,
					TourVenue,
					TourCity,
					TourCountry,
					TourTicket
				) VALUES (
					:TourDate,
					:TourVenue,
					:TourCity,
					:TourCountry,
					:TourTicket
				)";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":TourDate", $this->data["TourDate"] );
				$st->bindValue( ":TourVenue", $this->data["TourVenue"], PDO::PARAM_STR );
				$st->bindValue( ":TourCity", $this->data["TourCity"], PDO::PARAM_STR );
				$st->bindValue( ":TourCountry", $this->data["TourCountry"], PDO::PARAM_STR );
				$st->bindValue( ":TourTicket", $this->data["TourTicket"], PDO::PARAM_STR );
				$st->execute();
				parent::disconnect( $conn );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

		public function insertgallery() {
			$conn = parent::connect();
			$sql = "INSERT INTO " . TBL_GALLERY . " (
					ImageFile,
					ImageDescription,
					MenuTitle,
					MenuCategory,
					MenuPrice
				) VALUES (
					:ImageFile,
					:ImageDescription,
					:MenuTitle,
					:MenuCategory,
					:MenuPrice
				)";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageFile", $this->data["ImageFile"] );
				$st->bindValue( ":ImageDescription", $this->data["ImageDescription"], PDO::PARAM_STR );
				$st->bindValue( ":MenuTitle", $this->data["MenuTitle"], PDO::PARAM_STR );
				$st->bindValue( ":MenuCategory", $this->data["MenuCategory"], PDO::PARAM_INT );
				$st->bindValue( ":MenuPrice", $this->data["MenuPrice"], PDO::PARAM_STR );
				$st->execute();
				parent::disconnect( $conn );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}


		public function update() {
			$conn = parent::connect();
			$sql = "UPDATE " . TBL_GALLERY . " SET
				MenuTitle = :MenuTitle,
				ImageDescription = :ImageDescription,
				MenuPrice = :MenuPrice
				WHERE ImageID = :ImageID";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageID", $this->data["ImageID"], PDO::PARAM_INT );
				$st->bindValue( ":MenuTitle", $this->data["MenuTitle"], PDO::PARAM_STR );
				$st->bindValue( ":ImageDescription", $this->data["ImageDescription"], PDO::PARAM_STR );
				$st->bindValue( ":MenuPrice", $this->data["MenuPrice"], PDO::PARAM_STR );
				$st->execute();
				parent::disconnect( $conn );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}




		public function deletegallery() {
			$conn = parent::connect();
			$sql = "DELETE FROM " . TBL_GALLERY . " WHERE ImageID = :ImageID";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageID", $this->data["ImageID"], PDO::PARAM_INT );
				$st->execute();
				parent::disconnect( $conn );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

		public function authenticate() {
			$conn = parent::connect();
			$sql = "SELECT * FROM " . TBL_MEMBERS . " WHERE username = :username AND password = password(:password)";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":username", $this->data["username"], PDO::PARAM_STR );
				$st->bindValue( ":password", $this->data["password"], PDO::PARAM_STR );
				$st->execute();
				$row = $st->fetch();
				parent::disconnect( $conn );
				if ( $row ) return new Member( $row );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

	}

?>
