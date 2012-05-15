<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Document sans titre</title>
</head>

<body>
<div>
  <?php if(!$fb_data['me']): ?>
  Please login with your FB account: <a href="<?php echo $fb_data['loginUrl']; ?>">login</a>
  <?php else: ?>
  <img src="https://graph.facebook.com/<?php echo $fb_data['uid']; ?>/picture" alt="" class="pic" />
  <p>Hi <?php echo $fb_data['me']['name']; ?>,<br />
    <a href="<?php echo site_url('topsecret'); ?>">You can access the top secret page</a> or <a href="<?php echo $fb_data['logoutUrl']; ?>">logout</a> </p>
  <?php endif; ?>
</div>
</body></html>