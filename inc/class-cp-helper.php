<?php
class Cp_Helper{
	function get_param( $param, $default ) {
		return isset( $_GET[ $param ]) ? $_GET[ $param ] : ( isset( $_POST[ $param ] ) ? $_POST[ $param ] : $default );
	}
}
?>