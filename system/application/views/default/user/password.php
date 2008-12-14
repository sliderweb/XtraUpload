<?php if(!$this->session->userdata('id'))redirect('home');?>
<h2><img alt="" class="nb" src="<?=base_url().'img/icons/user_32.png'?>" /><?php echo $this->lang->line('user_password_header')?></h2>
<?=$errorMessage?>

<form action='<?php echo site_url('user/changePassword')?>' method="post">

	<h3>Change Password<?php echo $this->lang->line('user_password_header')?></h3>
    <p>
        <label style="font-weight:bold" for="username"><?php echo $this->lang->line('user_password_1')?></label>
        <input type="text" class="readonly" readonly="readonly" name="username" value="<?=$this->session->userdata('username')?>" size="50" /><br /><br />
    
        <label style="font-weight:bold" for="password"><?php echo $this->lang->line('user_password_2')?></label>
        <input type="password" name="oldpassword" size="50" /><br />
        
        <label style="font-weight:bold" for="password"><?php echo $this->lang->line('user_password_3')?></label>
        <input type="password" name="newpassword" size="50" /><br />
        
        <label style="font-weight:bold" for="passconf"><?php echo $this->lang->line('user_password_4')?></label>
        <input type="password" name="newpassconf" size="50" /><br /><br />
        
        <?=generateSubmitButton($this->lang->line('user_password_5'), base_url().'img/icons/ok_16.png', 'green')?><br />
	</p>
</form>