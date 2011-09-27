<?
/* Project name:  		Database class
 * Current version: 	0.1b5
 * Developer(s):		Sebastian Jansson
						Error handling and some comments: Per Andersson
 * Release date:		2003-09-02
 */
 
 
 class Database
 {
 	//Variable declarations
 	var $hostname;
 	var $username;
 	var $password;
 	var $db;
 	var $connection;
 	var $queries;
 	var $processtime;

 	//Constructor.
 	/**
 	 * @return Database
 	 * @desc Default_parameters
 	 */
 	function Database()
 	{
 		$this->queries = 0;
 		global $settings;
 		$this->set_database_parameters($settings['db_host'],$settings['db_user'],$settings['db_password'],$settings['db_name']); //Default-parameters
 	}

 	/**
 	 * @return void
 	 * @param hostname str
 	 * @param username str
 	 * @param password str
 	 * @param db str
 	 * @desc Sets the database parameters. Basicly called from the constructor.
 	 */
 	function set_database_parameters($hostname, $username, $password, $db)
 	{
 		$this->hostname = $hostname;
 		$this->username = $username;
 		$this->password = $password;
 		$this->db = $db;
 	}
 	
 	/**
 	 * @return unknown
 	 * @param value str
 	 * @desc Checks if magic_quotes() is enabled on the server.
 	 */
 	function slashes($value)
 	{
 		if (get_magic_quotes_gpc())			//If the server uses magic quotes, then do NOT addslashes() (they're added by default)
 			return $value;
 		else 
 			return addslashes($value);		//Necessary for safe communication to the db.
 	}
 	
 	
 	/**
 	 * @return res
 	 * @param query str
 	 * @desc Executes a database query.
 	 */
 	function query($query)
 	{
 		$error = false;
 		$starttime = microtime();
 		$this->connection = @mysql_connect($this->hostname,$this->username,$this->password) 
 			or $error = true;
 		@mysql_select_db($this->db,$this->connection)
 			or $error = true;
 		$result = @mysql_query($query, $this->connection)
 			or $error = true;
 		if($error) {
 			$errarr = debug_backtrace();
 			trigger_error('SQL-fel: '. mysql_errno() . ' - '. mysql_error(). '; Felursprung: ' . $errarr[0]['file']. ' ['.$errarr[0]['line'].']');
 		}
 		$this->queries++;
 		$this->query_process_time($starttime,microtime());
 		return $result; //Returnerar svaret.
 		//mysql_errno() ." - ". mysql_error()
 	}

 	
	/**
	 * @return int
	 * @param input res
	 * @desc Counts the number of rows in the resource-handler
	 */
	function num_rows($input)
	{
		$output = mysql_num_rows($input);
		return $output;
	}
 	
 	/**
 	 * @return void
 	 * @desc Closes the database
 	 */
 	function close_database()
 	{
 		mysql_close($this->connection);
 	}
 	
 	/**
 	 * @return array
 	 * @param input res
 	 * @desc Vectorizes the result.
 	 */
 	function format_output($input)
 	{
 		$output = mysql_fetch_array($input,MYSQL_ASSOC); //Sets $output to array with mysql column names as index
 	 	return $output;
 	}
 	
 	/**
 	 * @return int
 	 * @desc Returns the IDnr from the last inserted post.
 	 */
 	function last_inserted_id()
 	{
 		return mysql_insert_id();
 	}
 	
 	 function query_process_time($start,$end){
	 	$start_time = explode(' ',$start);
		$start_time = $start_time[1] + $start_time[0];
		$end_time = explode(' ', $end);
		$end_time = $end_time[1] + $end_time[0];
		$this->processtime = $this->processtime + $end_time - $start_time; 
 	}
 	
 	/**
 	 * @return str
 	 * @desc Returns $queries
 	 */
 	function number_of_queries()
 	{
 		return $this->queries;
 	}
 }
 ?>