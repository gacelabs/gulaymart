PASSWORD REQUEST SUCCESSFUL!
<br>
Your email address is <?php echo $email;?>
<br>
Your password is <?php echo $password;?>
<br>
<br>
OR Just <a href="<?php echo base_url('sign-in/?email_address='.$email.'&password='.md5($password).'&ismd5=1');?>">Click here</a> for instant Sign in!