<?
/* Project name:  		Image graph class
 * Current version: 	0.1
 * Developer(s):		Anton Hedström
 * Release date:		-
 */

class ImageGraph {

/* VARIABLES */
	protected $upload_dir = "upload/";
	protected $drawingTime;
	
	// Setups
	protected $height;
	protected $width;
	protected $im;
	protected $drawYgrid; // Bool
	protected $drawYvalue; // Bool
	protected $graphType;
	protected $lineWidth;
	protected $barWidth;
	protected $gridDist;
	
	// Values
	protected $values = array();
	protected $Y_max;
	protected $X_max;
	protected $X_min;
	protected $Xlabel; // String
	protected $Ylabel; // String

	// Font
	protected $Xaxis_fontsize;
	protected $Yaxis_fontsize;
	protected $Yvalue_fontsize;

	// Colors
	protected $background;
	protected $background_graph;
	protected $color;
	protected $grid_color;
	protected $Xaxis_fontcolor;
	protected $Yaxis_fontcolor;
	protected $Yvalue_fontcolor;
	//protected $coloralpha;
	//protected $alphavalue;

	// Distances
	protected $padding_left;
	protected $padding_bottom;
	protected $padding_top;
	
/* FUNCTIONS */
	
	 /**
 	 * @return void
 	 * @desc Set defaults 
 	 */
	public function __construct() {
		if (func_num_args() == 2) {
			if ( (is_numeric(func_get_arg(0)) AND is_numeric(func_get_arg(1))) OR die("Non numeric arguments.") );
			$this->width = func_get_arg(0);
			$this->height = func_get_arg(1); 
		}
		else {
			$this->width = 400;
			$this->height = 300;
		}
			
		$this->im = imagecreate( $this->width, $this->height ) or die('Cannot Initialize new GD image stream');
		
		// Font sizes
		$this->Xaxis_fontsize = 2;
		$this->Yaxis_fontsize = 2;
		$this->Yvalue_fontsize = 2;
		
		// Defaults
		$this->lineWidth 	= 2;
		$this->barWidth 	= 0.8;
		$this->gridDist 	= 25; // The distance between grids that the image should aim at
		//$this->alphavalue 	= 1; // Totally visible

		// Colors
		$this->background 			= imagecolorallocate( $this->im, 255, 255, 255 ); // First allocated color = Background color
		$this->background_graph 	 = imagecolorallocate( $this->im, 230, 230, 230 );
		$this->color 				       = imagecolorallocate( $this->im, 115, 163, 115);
		//$this->coloralpha 			 = imagecolorallocatealpha( $this->im, 0, 51, 102, 1 );
		$this->grid_color 			   = imagecolorallocate( $this->im, 220, 220, 220 );
		$this->Xaxis_fontcolor 		 = imagecolorallocate( $this->im, 0, 0, 0 );
		$this->Yaxis_fontcolor 		 = imagecolorallocate( $this->im, 0, 0, 0 );
		$this->Yvalue_fontcolor 	 = imagecolorallocate( $this->im, 120, 120, 120 );
		$this->Yvalue_fontcolor_2  = imagecolorallocate( $this->im, 255, 255, 255 );

		// Booleans
		$this->drawYgrid		= TRUE;
		$this->drawYvalue		= TRUE;
	}

	 /**
 	 * @return void
 	 * @desc Free used resources
 	 */
	public function __destruct() {
		//imagecolordeallocate( $this->im, $this->coloralpha );
		imagecolordeallocate( $this->im, $this->color );
		imagecolordeallocate( $this->im, $this->grid_color );
		imagecolordeallocate( $this->im, $this->Xaxis_fontcolor );
		imagecolordeallocate( $this->im, $this->Yaxis_fontcolor );
		imagecolordeallocate( $this->im, $this->Yvalue_fontcolor );
		imagecolordeallocate( $this->im, $this->Yvalue_fontcolor_2 );
		imagecolordeallocate( $this->im, $this->backgroundcolor_axis );
		imagecolordeallocate( $this->im, $this->background );
		imagedestroy( $this->im );
	}

	 /**
 	 * @return bitstream
 	 * @desc Return the image as a bitstream
 	 */
	public function getImage() {
		$this->draw();

		// start buffering
		ob_start();
		
		if (func_num_args() == 1) {
			switch (strtolower(func_get_arg(0))) {
				case "jpg":
					imagejpg( $this->im );
					break;
				case "jpeg":
					imagejpg( $this->im );
					break;
				case "gif":
					imagegif( $this->im );
					break;
				case "png":
					imagepng( $this->im );
					break;
				default:
					imagepng( $this->im );
					break;
			}
		}
		else
			imagepng( $this->im );

		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	 /**
 	 * @return str
 	 * @param filename str (optional)
 	 * @desc Try to find the file. If fails, create a temporary image.
 	 */
	public function getURL() {
		if ( func_num_args() == 1 ) {
			$arg1 = strtolower(func_get_arg(0));
			$ext = end(explode(".", $arg1 ));
			if (!isset($ext) OR ( $ext!="gif" AND $ext!="jpg" AND $ext!="jpeg" AND $ext!="png" ) ) {
				$ext = "png";
				$filename = $arg1;
			}
			else
				$filename = substr($arg1, 0, strlen($arg1) - strlen($ext) - 1);
				
			// If file exists, return path
			if ( isset($filename) AND isset($ext) AND file_exists($this->upload_dir . $filename . "." . $ext) )
				return $this->upload_dir . $filename . "." . $ext;
			
			return "data:image/" . $ext . ";base64," . base64_encode($this->getImage( $ext ));
		}
		return "data:image/png;base64," . base64_encode($this->getImage("png"));			
	}

	 /**
 	 * @return int
 	 * @param filename str
 	 * @desc Saves the image to the upload path
 	 */
	public function saveImage($f) {
		$ext = strtolower(end(explode(".", $f)));
		$filename = strtolower(substr($f, 0, strlen($f) - strlen($ext) - 1));
		if ( isset($filename) AND isset($ext) ) {
			/*if ( file_exists($this->upload_dir . $filename . "." . $ext) ) {
				$last_modified = filemtime("upload/" . $filename . "." . $ext);
				if ( time() - $last_modified < 60*60*24  ) // Timediff in seconds < 24hours : Done
					return 0;
			}*/	
			$image = $this->getImage( $ext );		
			$fh = fopen($this->upload_dir . $filename . "." . $ext, "w" ) or die("Unable to create/update image file.");
			fwrite( $fh, $image );
			fclose( $fh );
			return 1;
		}
		return 0;
	}

	 /**
 	 * @return void
 	 * @param key str/int
	 * @param value int
 	 * @desc Adds a value to the value array
 	 */
	public function addValue($k, $v) {
		if ( is_numeric($v) ) {
			$this->values[$k] = $v;
		}
	}
	
	 /**
 	 * @return void
 	 * @param boolean
 	 * @desc Sets if to draw Y grid
 	 */
	public function showGrid($bool) {
		if ($bool)
			$this->drawYgrid = TRUE;
		else
			$this->drawYgrid = FALSE;
	}
	
	 /**
 	 * @return void
 	 * @param boolean
 	 * @desc Sets if to draw Y value
 	 */
	public function showYValue($bool) {
		if ($bool)
			$this->drawYvalue = TRUE;
		else
			$this->drawYvalue = FALSE;
	}
	
	 /**
 	 * @return void
 	 * @param Label str
 	 * @desc Sets the X label
 	 */
	public function setXlabel($s) {
		if ( is_string($s) OR die("setXlabel: Input argument is not a string.") )
			$this->Xlabel = $s;
	}
	
	 /**
 	 * @return void
 	 * @param Label str
 	 * @desc Sets the Y label
 	 */
	public function setYlabel($s) {
		if ( is_string($s) OR die("setYlabel: Input argument is not a string.") )
			$this->Ylabel = $s;
	}
	
	 /**
 	 * @return void
 	 * @param width int
 	 * @desc Sets the line width
 	 */
	public function setLineWidth ($a) {
		if ( is_numeric($a) )
			$this->lineWidth = $a;
	}
	
	 /**
 	 * @return void
 	 * @param width int
 	 * @desc Sets the bar width
 	 */
	public function setBarWidth ($a) {
		if ( is_numeric($a) AND $a>0 AND $a<=1) 
			$this->barWidth = $a;
	}
	
	 /**
 	 * @return void
 	 * @param color string
 	 * @desc Sets new color to draw with
 	 */
	public function setColor ($c) {
		if ( is_string($c) ) {
		  if (strlen($c) == 3) { // #ABC
		    $red    = substr($c,0, 1) . substr($c,0, 1); 
		    $green  = substr($c,1, 1) . substr($c,1, 1); 
		    $blue   = substr($c,2, 1) . substr($c,2, 1); 
		  }
		  elseif (strlen($c) == 6) { // #ABCDEF
		    $red    = substr($c,0, 2);
		    $green  = substr($c,2, 2); 
		    $blue   = substr($c,4, 2); 
		  }
		  $this->color = imagecolorallocate($this->im, hexdec($red), hexdec($green), hexdec($blue));
		}
	}
	
	 /**
 	 * @return void
 	 * @param alphavalue int
 	 * @desc Updates the alphacolor
 	
	public function setAlpha ($a) {
		if ( is_numeric($a) AND $a>=0 AND $a<=1) 
			$this->coloralpha = imagecolorallocatealpha( $this->im, 0, 102, 51 , round(127*(1-$a)) ); 
	} */
	
	 /**
 	 * @return void
 	 * @param number int
 	 * @desc Sets the number of grids in the graph
 	 */
	public function setGrids($a) { // $a = number of grids
		if ( is_numeric($a) )
			$this->gridDist = $this->getGraphHeight() / $a;
	}
	
	 /**
 	 * @return void
 	 * @desc Main draw function. Calls other draw functions.
 	 */
	public function draw() {
 		$starttime = microtime();
		
		if ( empty($this->values) ) {
			imagefilledrectangle($this->im, 0, 0, $this->width, $this->height, $this->background_graph);
			$text = "No values to present.";
			imagestring($this->im, 4, 
						$this->getWidth()*0.5 - imagefontwidth(4)*strlen($text)*0.5,
						$this->getHeight()*0.5 - 0.5*imagefontheight(4),
						$text,
						$this->color);
		}
		else {
			// Calculate distances
			$this->Y_max 	= max($this->values);
			$this->X_max 	= max(array_keys($this->values));
			$this->X_min 	= min(array_keys($this->values));
			if ( !is_numeric($this->X_max) OR !is_numeric($this->X_min) ) {
				$this->X_max = count($this->values);
				$this->X_min = 1;
			}
			
			$this->padding_left 	= imagefontwidth($this->Yaxis_fontsize)*strlen(floor($this->Y_max));
			$this->padding_left		+= imagefontheight($this->Yaxis_fontsize + 1)*0.5;
			if ( isset($this->Ylabel) )
				$this->padding_left += imagefontheight($this->Yaxis_fontsize+1);
			
			$this->padding_bottom 	= imagefontheight($this->Xaxis_fontsize) + 4;
			if ( isset($this->Xlabel) )
				$this->padding_bottom += imagefontheight($this->Xaxis_fontsize+1);
			
			$this->padding_top 		= 2 + max(imagefontheight($this->Yvalue_fontsize), imagefontheight($this->Yaxis_fontsize));
			
			// Drawing
			$this->drawBackgroundGraph();
			$this->drawYaxis();
			$this->drawXaxis();
			
			
		}
		$this->drawingTime = round((microtime() - $starttime)*10000)/10000;

	}

	 /**
 	 * @return bool
 	 * @param graphtype int/string
 	 * @desc Sets graphtype
 	 */
	public function setGraphType($a) {
		if (func_num_args() == 1) {
			if ( is_string($a) ) {
				$this->graphType = $a;
				return TRUE;
			}
			elseif ( is_numeric($a) ) {
				switch ($a) {
					case 0:
						$this->graphType = "bar";
						break;
					case 1:
						$this->graphType = "line";
						break;
					default:
						$this->graphType = "bar";
				}
				return TRUE;
			}
		}
		return FALSE;
	}

	 /**
 	 * @return str
 	 * @desc Returns path to upload dir
 	 */
	public function getDir() {
		return $this->upload_dir;
	}
	
	 /**
 	 * @return void
 	 * @desc Clear all values
 	 */
	public function clearValues() {
		$this->values = array();
		imagefilledrectangle($this->im, 0, 0, $this->getWidth(), $this->getHeight(), $this->background);
		
	}
	
	private function my_Xmin($A) {
		if ( is_array($A) OR die("Argument has wrong datatype. Should be array.") ) {
			foreach ($A AS $element) {
				if ( !is_numeric($element) ) 
					return 0;
			}
			return min($A);
		}
	}
	
	private function my_Xmax($A) {
		if ( is_array($A) OR die("Argument has wrong datatype. Should be array.") ) {
			foreach ($A AS $element) {
				if ( !is_numeric($element) ) 
					return count($A);
			}
			return max($A);
		}
	}
	
	 /**
 	 * @return int
 	 * @desc Return image width
 	 */
	private function getWidth() {
		return $this->width;
	}
	
	 /**
 	 * @return int
 	 * @desc Return image height
 	 */
	private function getHeight() {
		return $this->height;
	}

	 /**
 	 * @return int
 	 * @desc Return graph width
 	 */
	private function getGraphHeight() {
		return ($this->getHeight() - $this->padding_bottom); 
	}
	
	 /**
 	 * @return int
 	 * @desc Return graph width
 	 */
	private function getGraphWidth() {
		return ($this->getWidth() - $this->padding_left); 
	}
	
	 /**
 	 * @return void
 	 * @desc Draws background color to graph
 	 */
	private function drawBackgroundGraph() {
		imagefilledrectangle($this->im, $this->padding_left, 0, $this->width, $this->getGraphHeight(), $this->background_graph);
	}

	 /**
 	 * @return void
 	 * @desc Draws X label and decides witch type of graph that should be drawn
 	 */
	private function drawXaxis() { // Draw graph type
		if ( isset($this->Xlabel) )
			imagestring($this->im, $this->Xaxis_fontsize +1, 
			/*X pos:*/	$this->padding_left + $this->getGraphWidth()*0.5 - 0.5*imagefontwidth($this->Xaxis_fontsize+1)*strlen($this->Xlabel),
			/*Y pos:*/	$this->getHeight() - imagefontheight($this->Xaxis_fontsize+1),
						$this->Xlabel,
						$this->color);
					
		switch ($this->graphType) {
			case "bar":
				$this->drawBar();
				break;
			case "line":
				$this->drawLine();
				break;
			default:
				$this->drawBar();
		}
	}

	 /**
 	 * @return void
 	 * @desc Draws values for Y axis. Eventually even Y label and Y value in graph 
 	 */
	private function drawYaxis() {
		if ( isset($this->Ylabel) ) 
			imagestringup($this->im, $this->Yaxis_fontsize+1, 
			/*X pos:*/	0,
			/*Y pos:*/	$this->getHeight()*0.5 + 0.5*imagefontwidth($this->Yaxis_fontsize+1)*strlen($this->Ylabel),
						$this->Ylabel,
						$this->color);
						
		imagesetthickness ( $this->im, $this->grid_thickness );
		$ratio = $this->getPixRatio();  // Ratio: V峤e / pix    800/(287 - 15)=800/272~2.9411
		$grid_value = $this->getGridValue( $ratio * $this->gridDist ); // 73.5.. => 70
		$grid_dist = $grid_value / $ratio; // Avst毤 i pix mellan de nya linjern
		
		$limit = $this->getGraphHeight() - imagefontheight($this->Yaxis_fontsize)*0.3;
		for ($i=0; $i < $limit; $i+=$grid_dist) {
			if ($this->drawYgrid)
				imageline($this->im, $this->padding_left, $this->getGraphHeight() - $i, $this->width, $this->getGraphHeight() - $i, $this->grid_color );
			$value = $grid_value * ($i/$grid_dist);
			imagestring( $this->im, $this->Yaxis_fontsize, $this->padding_left - 2 - imagefontwidth($this->Yaxis_fontsize)*strlen($value), $this->getGraphHeight() - $i - imagefontheight($this->Yaxis_fontsize)/2, $value, $this->Yaxis_fontcolor );
		}
	}
	
	 /**
 	 * @return float (Valus/pix)
 	 * @desc Calculates how much each pix in graph is worth
 	 */
	private function getPixRatio() {
		return ($this->Y_max / ( $this->getGraphHeight() - $this->padding_top));
	}
	
	 /**
 	 * @return int
	 * @param approxvalue int
 	 * @desc Calculates value differens between grids.
 	 */
	private function getGridValue($new) {
		if (is_numeric($new) OR die("Non numeric value for setGridValue().") ) {
			if ($new < 2)
				return 2;
			elseif ($new >= 20) {
				$factor = pow(10, (floor(log($new, 10))));
				return round($new/$factor)*$factor;
			}
			else
				return round($new);
		}
		return 0;
	}
	
	 /**
 	 * @return void
 	 * @desc Draw bars out of values
 	 */
	private function drawBar() {
		$x_dist = $this->getGraphWidth() / ($this->X_max - $this->X_min + 1);
		imagesetthickness ( $this->im, $this->barWidth * $x_dist);
		$startpos = $this->padding_left + $x_dist*0.5;
		$i = 0;
		foreach ($this->values AS $key => $val) {
			if ( is_numeric($key) )
				$val_pos = $key;
			else {
				$val_pos = $i++;
			}

			$pos_x = $startpos + $val_pos * $x_dist;
			$pos_y = $this->getGraphHeight() - $val * (1/$this->getPixRatio() );// + imagefontheight($this->Yvalue_fontsize);

			imageline( $this->im, $pos_x, $this->getGraphHeight(), $pos_x, $pos_y, $this->color );
			
			imagestring( $this->im, $this->Xaxis_fontsize, 
									$pos_x - imagefontwidth($this->Xaxis_fontsize)*strlen($key) * 0.5, 
									$this->getGraphHeight() + 2, 
									$key, 
									$this->Xaxis_fontcolor );
			
			$pos_y -= imagefontheight($this->Yvalue_fontsize);
			if ($this->drawYvalue) {
				imagestring( $this->im, $this->Yvalue_fontsize, 
										$pos_x - imagefontwidth($this->Yvalue_fontsize)*strlen($val) * 0.5, 
										$pos_y, 
										$val,
										$this->Yvalue_fontcolor );
			}

		}
	}
	
	 /**
 	 * @return void
 	 * @desc Draws lines out of values
 	 */
	private function drawLine() {
		$x_dist = $this->getGraphWidth() / ($this->X_max - $this->X_min + 1);
		imagesetthickness ( $this->im, $this->lineWidth );
		$startpos = $this->padding_left + $x_dist*0.5;
		$old_xpos;
		$old_ypos;
		if ( is_numeric(array_shift(array_keys($this->values))) ) // Is the first key element numeric? Should check all.
			ksort($this->values);
		$i = 0;
		foreach ($this->values AS $key => $val) {
			if ( is_numeric($key) )
				$x_pos = $key;
			else
				$x_pos = $i++;

			$new_xpos = $startpos + $x_pos * $x_dist;
			$new_ypos = $this->getGraphHeight() - $val * (1/$this->getPixRatio() );
		
			if (isset($old_xpos)) {
				imageline( $this->im, $old_xpos, $old_ypos, $new_xpos, $new_ypos, $this->color );	
			}
			imagestring( $this->im, $this->Xaxis_fontsize, 
									$new_xpos - imagefontwidth($this->Xaxis_fontsize)*strlen($key) * 0.5, 
									$this->getGraphHeight(), 
									$key, 
									$this->Xaxis_fontcolor );
			
			$old_xpos = $new_xpos;
			$old_ypos = $new_ypos;
			
			$new_ypos -= imagefontheight($this->Yvalue_fontsize);
			if ($this->drawYvalue) {
				imagestring( $this->im, $this->Yvalue_fontsize, 
										$new_xpos - imagefontwidth($this->Yvalue_fontsize)*strlen($val) * 0.5, 
										$new_ypos, 
										$val,
										$this->Yvalue_fontcolor );
			}
		}
	}
}
?>