<strong>URL : <?php echo $message[ 'Feedback' ][ 'url' ]; ?></strong>
<hr>
<?php echo $message[ 'Feedback' ][ 'message' ]; ?>
<br>
<br>
<?php echo $message[ 'Feedback' ][ 'name' ], ', ' . $message[ 'Feedback' ][ 'email' ]; ?>
<hr>
<pre>
	<?php pr( $_SERVER ); ?>
</pre>