<?php

	require_once "DataObject.class.php";

	class News extends DataObject {

		protected $data = array(
			"ImageID" => "",
			"MenuTitle" => "",
			"ImageDescription" => "",
			"MenuPrice" => "",
			"ImageFile" => ""
		);


		public static function getNews( $startRow, $numRows, $order ) {
			$conn = parent::connect();
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_GALLERY . " ORDER BY $order LIMIT :startRow, :numRows";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":startRow", $startRow, PDO::PARAM_INT );
				$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
				$st->execute();
				$News = array();
				foreach ( $st->fetchAll() as $row ) {
					$News[] = new News( $row );
				}
				$st = $conn->query( "SELECT found_rows() as totalRows" );
				$row = $st->fetch();
				parent::disconnect( $conn );
				return array( $News, $row["totalRows"] );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}

		public static function getNewsRecent( $startRow, $numRows, $order ) {
			$conn = parent::connect();
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_GALLERY . " ORDER BY $order DESC LIMIT :startRow, :numRows";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":startRow", $startRow, PDO::PARAM_INT );
				$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
				$st->execute();
				$News = array();
				foreach ( $st->fetchAll() as $row ) {
					$News[] = new News( $row );
				}
				$st = $conn->query( "SELECT found_rows() as totalRows" );
				$row = $st->fetch();
				parent::disconnect( $conn );
				return array( $News, $row["totalRows"] );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}


		public static function getNewsItem( $ ) {
			$conn = parent::connect();
			$sql = "SELECT * FROM " . TBL_GALLERY . " WHERE ImageID = :ImageID";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":ImageID", $ImageID, PDO::PARAM_INT );
				$st->execute();
				$row = $st->fetch();
				parent::disconnect( $conn );
				if ( $row ) return new News( $row );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}


		public function insert() {
			$conn = parent::connect();
			$sql = "INSERT INTO " . TBL_GALLERY . " (
					MenuTitle,
					ImageDescription,
					MenuPrice
				) VALUES (
					:MenuTitle,
					:ImageDescription,
					:MenuPrice
				)";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":MenuTitle", $this->data["MenuTitle"], PDO::PARAM_STR );
				$st->bindValue( ":ImageDescription", $this->data["ImageDescription"], PDO::PARAM_STR );

				//$st->bindValue( ":MenuPrice", $this->data["MenuPrice"] );
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

		public function delete() {
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
