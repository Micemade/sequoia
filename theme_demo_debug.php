<?php 
/**
 * Template: Theme options debug
 * Description: Theme options debug
 */
 
?>

<pre>
<?php
global $of_sequoia;
$data_r = print_r( $of_sequoia, true ); 
$data_r_sans = htmlspecialchars( $data_r, ENT_QUOTES ); 
echo $data_r_sans; ?>
</pre>