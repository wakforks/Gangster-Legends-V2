<?php 

	$_roundCache = false;

	class Round {

		public $currentRound = null;
		public $nextRound = null;
		public $id = 0;

		function __construct() {
			global $db, $_roundCache;

			if ($_roundCache) {
				$this->currentRound = $_roundCache["currentRound"];
				$this->nextRound = $_roundCache["nextRound"];
				$this->id = $_roundCache["id"];
			} else {
				$_roundCache["currentRound"] = $this->currentRound = $db->select("
					SELECT
						R_id as 'round', 
						R_name as 'name', 
						R_start as 'start', 
						R_end as 'end'
					FROM rounds 
					WHERE UNIX_TIMESTAMP() BETWEEN R_start AND R_end
				");

				$_roundCache["nextRound"] = $this->nextRound = $db->select("
					SELECT
						R_id as 'round', 
						R_name as 'name', 
						R_start as 'start', 
						R_end as 'end'
					FROM rounds 
					WHERE R_start > UNIX_TIMESTAMP()
					ORDER BY R_start ASC
				");

				$roundID = 0;

				if ($this->currentRound) {
					$roundID = $this->currentRound["round"];
				} else if ($this->nextRound) {
					$roundID = $this->nextRound["round"];
				}

				$_roundCache["id"] = $this->id = $roundID;

			}

		}

	}