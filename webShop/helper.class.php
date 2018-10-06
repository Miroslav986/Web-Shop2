<?php 

class Helper {

	public static function session_start() {
		if(!isset($_SESSION)) {
			session_start();
		}
	}
	public static function session_destroy() {
		 session_start();
		 session_destroy();
		
	}
	public static function error($message,$title = "Error" ) {
		echo '<div class="alert alert-danger" role="alert">';
		echo '<b>' . $title . ' </b>';
		echo "<br>";
  			if(is_array($message)) {
  				foreach ($message as $msg) {
  					echo "<b> $msg </b> <br>";
  				} 
  			}
				else {
  					echo "<b> $message </b>";
  				}
  			
        echo '</div>';
	}
	public static function success($message,$title = "Success !!") {
		echo "<br>";
		echo '<div class="alert alert-success" role="alert">';
		echo '<b>' . $title . '<b>';
		echo "<br>";
		echo $message;


		echo '</div>';
	}
	
	public static function warn($message, $title = "Warning!") {
		echo '<div class="alert alert-warning">';
		echo '<b>' . $title . '</b>';
		echo $message;
		echo '</div>';
	}

}



/* pozivanje obicne metode 

$h = new Helper();
$h->session_start();

Pozivanje staticke metode 
Helper::sesion_start();
*/