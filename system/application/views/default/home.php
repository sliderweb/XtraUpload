<h2 style="vertical-align:middle"><img src="<?php echo $base_url.'img/other/home2_32.png'?>" class="nb" alt="" /> <?php echo $this->lang->line('home_title')?></h2>
<div id="flash" style="display:"><h3 class="error"><?php echo $this->lang->line('home_error')?></h3><p><?php echo $this->lang->line('home_no_flash_1')?> <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash"><?php echo $this->lang->line('home_no_flash_2')?></a></p></div>
<?php if(!empty($flashMessage)){ echo '<p>'.$flashMessage.'</p>';}?>
<?php if(!empty($this->startup->site_config['home_info_msg'])){ echo '<span class="note">'.$this->startup->site_config['home_info_msg'].'</span>';}?>
<div id="info_div" style="display:none">
	<h3>
		<a href="javascript:void(0);" onclick="$('#upload_limits').slideDown();$(this).parent().remove();">
			<img src="<?php echo $base_url?>img/icons/about_24.png" class="nb" alt="" /><?php echo $this->lang->line('home_upload_res')?>
		</a>
	</h3>
	<p>
		<span style="display:none" id="upload_limits" class="info">
			<?php echo $this->lang->line('home_upload_limit_1')?>
			<strong><?php echo intval($upload_num_limit)?></strong> 
			<?php echo $this->lang->line('home_upload_limit_2')?> 
			<strong><?php echo intval($upload_limit)?></strong><?php echo $this->lang->line('home_upload_limit_3')?>
			<?php if(trim($files_types) != '' and $files_types != '*')
			{
				?>
				<br />
				<?php echo $this->lang->line('home_upload_limit_4')?> 
				<strong><?php if($file_types_allow_deny){echo $this->lang->line('home_upload_limit_5'); }else{echo $this->lang->line('home_upload_limit_6');}?></strong>
				 <?php echo $this->lang->line('home_upload_limit_7')?> .<?php echo str_replace('|', ', .', $files_types)?>
				<?php
			}
			
			if(trim($storage_limit) != '' and $storage_limit != '0')
			{
				?>
				<br /><br />
				<strong>Your account is limited by storage space:</strong><br />
				You have 
				<strong><?php echo $storage_used?></strong>
				of
				<strong><?php echo $storage_limit;?> MB</strong>
				 remaining 
				<?php
			}
			?>
		</span>
	</p>
</div>

<div id="uploader" style="display:none">
	<h3 style="padding-top:8px;"><?php echo $this->lang->line('home_select_files')?></h3><br />
	<div style=" padding-left:12px;">
		<div style="display: block; width:90px; height:22px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px; padding-top:6px; padding-left:6px;"><span id="spanButtonPlaceholder"></span></div>
	</div>
	<br />
</div>


<div id="files" style="display:none">
	<h3 style="padding-top:8px;"><?php echo $this->lang->line('home_queued')?></h3>
	<div id="file_list">
		<p>
			<?php echo $this->lang->line('home_selected_files')?> (<span id="summary">0</span> <?php echo $this->lang->line('home_files')?>).<br />
			<span class="alert" id="alert1" style="display:none">
				<?php echo $this->lang->line('home_select_error_1')?><br />
				<?php echo $this->lang->line('home_files_removed')?>.
			</span>
			<span class="alert" id="alert2" style="display:none">
				<?php echo $this->lang->line('home_select_error_2')?><br />
				<?php echo $this->lang->line('home_files_removed')?>.
			</span>
			<span class="alert" id="alert3" style="display:none">
				<?php echo $this->lang->line('home_select_error_3')?><br />
				<?php echo $this->lang->line('home_files_removed')?>.
			</span>
			<span class="alert" id="alert4" style="display:none">
				<?php echo $this->lang->line('home_select_error_4')?> <strong><?php echo intval($upload_num_limit)?></strong> <?php echo $this->lang->line('home_files')?>.<br />
				<?php echo $this->lang->line('home_select_error_5')?>.
			</span>
			<span class="alert" id="alert5" style="display:none">
				<?php echo $this->lang->line('home_select_error_6')?>.<br />
				<?php echo $this->lang->line('home_select_error_7')?>.
			</span>
		</p>
		<div class="float-right" style=" margin-bottom:1em">
			<?php echo generateLinkButton($this->lang->line('home_upload'), 'javascript:void(0);', $base_url.'img/icons/up_16.png', 'green', array('onclick'=>'swfu.startUpload();'))?>
		</div>
		<table border="0" style=" padding:0;width:98%;clear:both" id="file_list_table">
			<tr>
				<th style="width:470px" class="align-left"><?php echo $this->lang->line('home_table_1')?></th>
				<th style="width:90px"><?php echo $this->lang->line('home_table_2')?></th>
				<th style="width:85px"><?php echo $this->lang->line('home_table_3')?> <img title="Delete All?" src="<?php echo $base_url?>img/icons/delete_16.png" onclick="clearUploadQueue()" alt="" style="cursor:pointer" class="nb" /></th>
			</tr>
		</table>
		<div class="float-right">
			<?php echo generateLinkButton($this->lang->line('home_upload'), 'javascript:void(0);', $base_url.'img/icons/up_16.png', 'green', array('onclick'=>'swfu.startUpload();'))?>
		</div>
	</div>
</div>

<input id="fid" type="hidden" />
<input id="uid" type="hidden" value="<?php echo $this->session->userdata('id')?>" />
<div id="filesHidden" style="display:none"></div>

<script type="text/javascript">
	var fileObj = new Array();
	var prevFile = false;
	var fileToBig = false;
	var fileNotAllowed = false;
	var subtractFilesFromTotal = 0;
	var curFileId = '';
	var pbUpd = 0;
	var flashUploadStartTime = '';
	var fileIcons = new Array(<?php echo $file_icons?>);
	
	function ___getMaxUploadSize()
	{
		return '<?php echo intval($upload_limit)?>';
	}
	
	function ___serverUrl()
	{
		return '<?php echo $server?>';
	}
	
	function ___getFilePipeString()
	{
		return '<?php echo $files_types?>';
	}
	
	function ___getFileIcon(icon)
	{
		if(in_array(icon, fileIcons))
		{
			return icon;
		}
		else
		{
			return 'default';
		}
	}
	
	function ___getFileTypesAllowOrDeny()
	{
		return <?php echo intval($file_types_allow_deny)?>;
	}
	
	function ___toManyFilesError()
	{
		$('#alert4').show();
		setTimeout('$("#alert4").hide("normal");', 2500);
		fileToBig = false;
	}
	
	function ___generalError()
	{
		$('#alert5').show();
		setTimeout('$("#alert5").hide("normal");', 2500);
		fileToBig = false;
	}
	
	function ___upLang(key)
	{
		var lang = new Array();
		lang['pc' ] 	= '<?php echo $this->lang->line('home_js_1')?>';
		lang['kbr'] 	= '<?php echo $this->lang->line('home_js_2')?>';
		lang['remain']	= '<?php echo $this->lang->line('home_js_3')?>';
		lang['desc']	= '<?php echo $this->lang->line('home_js_4')?>';
		lang['fp']  	= '<?php echo $this->lang->line('home_js_5')?>';
		lang['sc']  	= '<?php echo $this->lang->line('home_js_6')?>';
		lang['efd'] 	= '<?php echo $this->lang->line('home_js_7')?>';
		lang['rm']  	= '<?php echo $this->lang->line('home_js_8')?>';
		
		return lang[key];
	}
	
	$(document).ready(function()
	{
		var settings_object = { 
			file_types : "*.*", 
			file_types_description: "<?php echo $this->lang->line('home_js_9')?>", 
			file_upload_limit : <?php echo intval($upload_num_limit)?>, 
			file_size_limit : (<?php echo intval($upload_limit)?> * 1024),
			file_queue_limit : <?php echo intval($upload_num_limit)?>, 
			flash_url : ___baseUrl()+"flash/upload.swf", 
			flash_width : "1px", 
			flash_height : "1px", 
			flash_color : "#CCCCCC", 
			debug:false,
			
			// Button settings
			button_image_url : "<?=$base_url.'img/flash_upload.png'?>",	// Relative to the SWF file
			button_placeholder_id : "spanButtonPlaceholder",
			button_width: 90,
			button_height: 18,
			button_text : '<'+'span class="button"><?=$this->lang->line('home_files_browse')?></'+'span>',
			button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt;  font-weight:bold; color:#565656; }',
			button_text_top_padding: 0,
			button_text_left_padding: 22,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			
			upload_progress_handler : flashUploadProgress, 
			upload_error_handler : flashUploadError, 
			file_dialog_complete_handler : fileDialogComplete, 
			file_queue_error_handler : flashUploadQueueError,
			file_queued_handler : addFileQueue,
			upload_start_handler : beforeUploadStart, 
			upload_complete_handler : uploadDone
		};
		
		swfu = new SWFUpload(settings_object);
		if (swfu) {
			$('#flash').html('');
			$('#browser').attr('disabled', false);
			$('#uploader').show();
			$('#files').show();
			$('#info_div').show();
		}
	});
</script>