<?php

	require_once "DataObject.class.php";

	class Survey extends DataObject {

		protected $data = array(
			"SurveyID" => "",
			"Q1" => "",
			"Q2" => "",
			"Q3" => "",
			"Q4" => "",
			"Q5" => "",
			"Q6" => "",
			"Q7" => "",
			"Q8" => "",
			"Q9" => "",
			"Q10" => "",
			"Q11" => "",
			"Q12" => "",
			"Q13" => "",
			"Q14" => "",
			"Q15" => "",
			"Q16" => "",
			"Q17" => "",
			"Q18" => "",
			"Q19" => "",
			"Q20" => "",
			"Q21" => "",
			"Q22" => "",
			"Q23" => "",
			"Q24" => "",
			"Q25" => "",
			"Q26" => ""
		);


		public static function showSurvey( $startRow, $numRows, $order ) {
			$conn = parent::connect();
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . TBL_SURVEY . " ORDER BY $order LIMIT :startRow, :numRows";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":startRow", $startRow, PDO::PARAM_INT );
				$st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
				$st->execute();
				$Survey = array();
				foreach ( $st->fetchAll() as $row ) {
					$Survey[] = new Survey( $row );
				}
				$st = $conn->query( "SELECT found_rows() as totalRows" );
				$row = $st->fetch();
				parent::disconnect( $conn );
				return array( $Survey, $row["totalRows"] );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}


		public function insert() {
			$conn = parent::connect();
			$sql = "INSERT INTO " . TBL_SURVEY . " (
					Q1,
					Q2,
					Q3,
					Q4,
					Q5,
					Q6,
					Q7,
					Q8,
					Q9,
					Q10,
					Q11,
					Q12,
					Q13,
					Q14,
					Q15,
					Q16,
					Q17,
					Q18,
					Q19,
					Q20,
					Q21,
					Q22,
					Q23,
					Q24,
					Q25,
					Q26

				) VALUES (
					:Q1,
					:Q2,
					:Q3,
					:Q4,
					:Q5,
					:Q6,
					:Q7,
					:Q8,
					:Q9,
					:Q10,
					:Q11,
					:Q12,
					:Q13,
					:Q14,
					:Q15,
					:Q16,
					:Q17,
					:Q18,
					:Q19,
					:Q20,
					:Q21,
					:Q22,
					:Q23,
					:Q24,
					:Q25,
					:Q26
				)";
			try {
				$st = $conn->prepare( $sql );
				$st->bindValue( ":Q1", $this->data["Q1"], PDO::PARAM_STR );
				$st->bindValue( ":Q2", $this->data["Q2"], PDO::PARAM_STR );
				$st->bindValue( ":Q3", $this->data["Q3"], PDO::PARAM_STR );
				$st->bindValue( ":Q4", $this->data["Q4"], PDO::PARAM_STR );
				$st->bindValue( ":Q5", $this->data["Q5"], PDO::PARAM_STR );
				$st->bindValue( ":Q6", $this->data["Q6"], PDO::PARAM_STR );
				$st->bindValue( ":Q7", $this->data["Q7"], PDO::PARAM_STR );
				$st->bindValue( ":Q8", $this->data["Q8"], PDO::PARAM_STR );
				$st->bindValue( ":Q9", $this->data["Q9"], PDO::PARAM_STR );
				$st->bindValue( ":Q10", $this->data["Q10"], PDO::PARAM_STR );
				$st->bindValue( ":Q11", $this->data["Q11"], PDO::PARAM_STR );
				$st->bindValue( ":Q12", $this->data["Q12"], PDO::PARAM_STR );
				$st->bindValue( ":Q13", $this->data["Q13"], PDO::PARAM_STR );
				$st->bindValue( ":Q14", $this->data["Q14"], PDO::PARAM_STR );
				$st->bindValue( ":Q15", $this->data["Q15"], PDO::PARAM_STR );
				$st->bindValue( ":Q16", $this->data["Q16"], PDO::PARAM_STR );
				$st->bindValue( ":Q17", $this->data["Q17"], PDO::PARAM_STR );
				$st->bindValue( ":Q18", $this->data["Q18"], PDO::PARAM_STR );
				$st->bindValue( ":Q19", $this->data["Q19"], PDO::PARAM_STR );
				$st->bindValue( ":Q20", $this->data["Q20"], PDO::PARAM_STR );
				$st->bindValue( ":Q21", $this->data["Q21"], PDO::PARAM_STR );
				$st->bindValue( ":Q22", $this->data["Q22"], PDO::PARAM_STR );
				$st->bindValue( ":Q23", $this->data["Q23"], PDO::PARAM_STR );
				$st->bindValue( ":Q24", $this->data["Q24"], PDO::PARAM_STR );
				$st->bindValue( ":Q25", $this->data["Q25"], PDO::PARAM_STR );
				$st->bindValue( ":Q26", $this->data["Q26"], PDO::PARAM_STR );


				$st->execute();
				parent::disconnect( $conn );
			} catch ( PDOException $e ) {
				parent::disconnect( $conn );
				die( "Query failed: " . $e->getMessage() );
			}
		}












	}

?>
