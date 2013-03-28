<strong>URL : <?php echo $message[ 'Feedback' ][ 'url' ]; ?></strong>
<hr>
<br>
<?php echo $message[ 'Feedback' ][ 'message' ]; ?>
<br>
<hr>
<strong><?php echo $message[ 'Feedback' ][ 'name' ]; ?></strong><br>
IDUL : <?php echo $this->Session->read( 'User.idul' ); ?><br>
E-mail : <?php echo $message[ 'Feedback' ][ 'email' ]; ?><br>
<hr>
<pre>
	<?php pr( $_SERVER ); ?>
</pre>