<?

/* Project name:  		Template
 * Current version: 	0.1b6
 * Developer(s):		Sebastian Jansson
 * Release date:		2003-10-11
 */ 

class template
{
	var $var_names	= array();
	var $files 		= array();
	var $start		= '{';
	var $end		= '}';
	
	/**
	 * @return void
	 * @param file_id Template_id
	 * @param filename Templatefile
	 * @desc Constructor. Calls the class function 'loadtemplate' to load a template file.
	 */
	function template() // template($file_id,$filename)
	{
		//$this->loadtemplate($file_id,$filename);
		
	}

	/**
	 * @return void
	 * @param file_id Template_id
	 * @param filename Templatefile
	 * @desc Loads the file $filename and puts the content in the array $files, with the file_id as identifier and the file content as key.
	 */
	function loadtemplate($file_id,$filename)
	{
		if(file_exists($filename) and is_file($filename)){
			$this->files[$file_id] = fread($fp = fopen($filename, 'r'), filesize($filename));
			fclose($fp);
		}else{
			//Filen finns inte.
 			$errarr = debug_backtrace();
 			trigger_error('Filen <em>'.$filename.'</em> kunte inte hittas!<br />Felursprung: ' . $errarr[0]['file']. ' ['.$errarr[0]['line'].']');
		}
	}
	
	/**
	 * @return void
	 * @param file_id FileID
	 * @param var_name VariableName
	 * @desc Registers a variable in the class array var_names, with the fileid as first key and an ascending integer as second key.
	 */
	function registervariable($file_id,$var_name)
	{
		$var_array = split(",",$var_name);
		
		while (list($key,$val) = each($var_array))
		{
			$val = trim($val);
			$this->var_names[$file_id][] = $val;
		}
	}
	
	
	/**
	 * @return result
	 * @param file_id FileID
	 * @desc Parses the file
	 */
	function parse($file_id)
	{
		$resultat = $this->files[$file_id];
		if(isset($this->var_names[$file_id]) and count($this->var_names[$file_id]) != 0) {
			while (list($key,$val) = each($this->var_names[$file_id])){
				$sokefter = $this->start . $val . $this->end;
				global $$val;
				$resultat = str_replace($sokefter,$$val,$resultat);
			}
		}
		return $resultat;
	}
	
	/**
	 * @return void
	 * @param file_id File_ID
	 * @param array_name Array_Name
	 * @desc Parses an array repeat, called with <repeat name"arrayname">{arraykey}{arraykey2}</repeat name="arrayname">.
	 */
	function array_repeat($file_id, $array_name)
	{
		global $$array_name;
			$repeat_code = '';

			$start_pos = strpos(strtolower($this->files[$file_id]), '<repeat name="'.$array_name.'">') + strlen('<repeat name="'.$array_name.'">');
			$end_pos = strpos(strtolower($this->files[$file_id]), '</repeat name="'.$array_name.'">');

			$repeat_code = substr($this->files[$file_id], $start_pos, $end_pos-$start_pos);

			$start_tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '<repeat name="'.$array_name.'">'),strlen('<repeat name="'.$array_name.'">'));
			$end_tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '</repeat name="'.$array_name.'">'),strlen('</repeat name="'.$array_name.'">'));

			if($repeat_code != ''){
				$new_code = '';
				for($i=0; $i<count($$array_name); $i++){
					$temp_code = $repeat_code;
					while(list($key,) = each(${$array_name}[$i])){
						$temp_code = str_replace($this->start.$key.$this->end,${$array_name}[$i][$key], $temp_code);
					}
					$new_code .= $temp_code;
				}
				$this->files[$file_id] = str_replace($start_tag.$repeat_code.$end_tag, $new_code, $this->files[$file_id]);
				
			}
	}	
	
               function parse_if($file_id, $var_name){

                   $var_names = explode(',', $var_name);

                   for($i=0; $i<count($var_names); $i++){
                        $if_code	= '';
                        $start_pos	= strpos(strtolower($this->files[$file_id]), '<if name="'.strtolower(trim($var_names[$i])).'">') + strlen('<if name="'.strtolower(trim($var_names[$i])).'">');
                        $end_pos	= strpos(strtolower($this->files[$file_id]), '</if name="'.strtolower(trim($var_names[$i])).'">');

                        $if_code	= substr($this->files[$file_id], $start_pos, $end_pos-$start_pos);
                        $start_tag	= substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '<if name="'.strtolower(trim($var_names[$i])).'">'),strlen('<if name="'.strtolower(trim($var_names[$i])).'">'));
                        $end_tag	= substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '</if name="'.strtolower(trim($var_names[$i])).'">'),strlen('</if name="'.strtolower(trim($var_names[$i])).'">'));

                        $new_code = '';
                        if($if_code != ''){
                                global ${trim($var_names[$i])};
                                if(@${trim($var_names[$i])})
                                    $new_code = $if_code;

                                $this->files[$file_id] = str_replace($start_tag.$if_code.$end_tag, $new_code, $this->files[$file_id]);
                        }
                    }
                }
}

/*
$tapa = new template("mittid","c:\\dinmamma.txt");
$tapa->registervariable("mittid","envariabel, entillvariabel, entredjevariabel");
//tmp

$envariabel = "Ersättningstext för variabeln \$envariabel";
$entillvariabel = "Ersättningstext för variabeln \$entillvariabel";
$entredjevariabel = "Ersättningstext för variabeln \$entredjevariabel";

$namnosv = array();
$namnosv[] = array( 'namn' => 'Sebastian Jansson',
                      'telefonnummer' => '036168689',
                      'adress' => 'Fogdegatan 17');
                      
$namnosv[] = array( 'namn' => 'Per Andersson',
						'telefonnummer' => "666",
						'adress' => 'norrahammar 1337');

$tapa->array_repeat("mittid","namnosv");
$resultat = $tapa->parse("mittid");
echo $resultat;
*/
?>