<?php
// $Id: webform-mail.tpl.php,v 1.3.2.3 2010/08/30 20:22:15 quicksketch Exp $

/**
 * @file
 * Customize the e-mails sent by Webform after successful submission.
 *
 * This file may be renamed "webform-mail-[nid].tpl.php" to target a
 * specific webform e-mail on your site. Or you can leave it
 * "webform-mail.tpl.php" to affect all webform e-mails on your site.
 *
 * Available variables:
 * - $node: The node object for this webform.
 * - $submission: The webform submission.
 * - $email: The entire e-mail configuration settings.
 * - $user: The current user submitting the form.
 * - $ip_address: The IP address of the user submitting the form.
 *
 * The $email['email'] variable can be used to send different e-mails to different users
 * when using the "default" e-mail template.
 */
?>

<?php

	global $base_url, $theme_path, $conf;
	
	//$data = '<pre>' . print_r($submission, TRUE) .'</pre>';	
	//$data .= '<br/><br/><pre>Email var:<br/>' . print_r($email, TRUE) .'</pre>';	
	
	$is_user = $email['email'] == 3; 

	$theme = $base_url . '/' . $theme_path;
	$coupon_path = $theme . "/images/landing/10_percent_coupon.jpg";

  $site = 'desktop site';
  if (isset($conf['SITE_ID']) && $conf['SITE_ID'] == 'mobile') {
    $site = 'mobile site';
  }
  
  
 	//------------------------
 	// ATTACHMENTS
  /*
  global $drupal_hash_salt;
  $hash = md5($drupal_hash_salt);
  $mime_boundary = "==Multipart_Boundary_x{$hash}x";
  ob_start();
  print "--" . $mime_boundary . "\r\n" . "Content-Type: text/html; charset=\"UTF-8\"; format=flowed; delsp=yes" . "\r\n";*/
	//------------------------

?>

<?php if ($is_user) : ?>

	<div>

<html>
<body>
<table cellpadding="0" cellspacing="0" width="600" align="center" style="border-collapse:collapse;">
  <tr>
    <td>
      <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0;">
        <tr>
          <td><Greeting/></td>
        </tr>
        <!-- LOGO -->
        <tr><td style="text-align:center;"><span><a href="http://www.furnitalia.com" target="_blank"  rel="nofollow" title="Furnitalia" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/logo.jpg" alt="Furnitalia" border="0" width="599" height="100"/></a></span></td></tr>
       
        <tr>
          <td style="height:15px">
            <span><img src="<?php print $theme; ?>/images/webmail/space.gif" style="height:15px;" border="0"/></span>
          </td>
        </tr>
        <!-- CONTENT -->
        
        <tr>
          <td align="center" style="text-align:center;width:688px;border:2px solid #CCC;padding:10px;">
                    
          	<table width="598" border="0" cellspacing="0" cellpadding="0">                      
      				<tbody>
      					<tr>                        
      						<td width="9" height="159" align="left" valign="top">
      							<img src="<?php print $theme; ?>/images/webmail/space.gif" alt="" width="9" height="159" hspace="0" vspace="0" border="0" style="display:block;">
      						</td>                        
      						<td width="595" align="left" valign="top">
      							<table width="595" border="0" cellspacing="0" cellpadding="0">                            
      							<tbody>
      								<tr>                              
      									<td width="370" height="135" align="left" valign="top" style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;font-size:14px;line-height:130%;font-weight:normal;text-decoration:none;color:#555555;">
      										<span style="font-family:Helvetica Neue, Helvetica, Arial,sans-serif;font-size:19px;font-weight:bold;text-decoration:none;color:#E12800;">
      										We Move, You SAVE!</span>
      										<br/>        
      										<p>
      										%email[submission_code]
      										</p>         
      										
                          <img src="<?php print $coupon_path;?>" alt="10% OFF Coupon during Furnitalia Moving Sale"/>
      										
      									</td>
      								</tr>                            
      								
      							</tbody>
      							</table>
      						</td>                        
      						                
      					</tr>                    
      				</tbody>
      			</table>                    
                    
          </td>
        </tr>
       
        <!-- \CONTENT -->
        <tr>
          <td style="height:15px">
            <span><img src="<?php print $theme; ?>/images/webmail/space.gif" style="height:40px;" border="0"/></span>
          </td>
        </tr>
        <!-- STORES -->
        <tr>
          <td>
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse; border-spacing:0;">
              <tr style="text-align:center">
                    <td>
                  <span style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-size:13px;">
                    <a href="http://www.furnitalia.com/contact" rel="nofollow" title="Locate Furnitalia Stores" target="_blank" style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-weight:bold; font-size:14px;text-decoration:none;">Furnitalia Main Store</a>
                  </span><br/>
                  <span style="color:#6A6A6A; font-family:Arial,Verdana,sans-serif; font-size:11px;">5270 Auburn Blvd.</span><br/>
                  <span style="color:#6A6A6A; font-family:Arial,Verdana,sans-serif; font-size:11px;">Sacramento, CA 95841</span><br/>
                  <span style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-size:14px; font-weight:bold;">916.484.0333</span>
                </td>               
                <td>
                  <span>
                    <a href="http://www.furnitalia.com" title="Furnitalia" rel="nofollow" target="_blank" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/website.jpg" style="height:41px;width:181px" border="0" alt="Furnitalia.com"/></a>
                  </span>
                </td>
                <td>
                  <span style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-size:13px">
                    <a href="http://www.furnitalia.com/contact" rel="nofollow" title="Locate Furnitalia Stores" target="_blank" style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-weight:bold; font-size:14px; text-decoration:none;">Fountains at Roseville</a>
                  </span><br/>
                  <span style="color:#6A6A6A; font-family:Arial, Verdana, sans-serif; font-size:11px;">1198 Roseville Pkwy</span><br/>
                  <span style="color:#6A6A6A; font-family:Arial, Verdana, sans-serif; font-size:11px;">Roseville, CA 95678</span><br/>
                  <span style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-size:14px; font-weight:bold;">916.742.7900</span>
                </td>             
              </tr>
            </table>
          </td>
        </tr>
        <!-- \STORES -->
        <tr>
          <td>&nbsp;</td>
        </tr>
       
        <!-- SOCIAL -->
        <tr>
          <td style="text-align:center;">
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0;">
              <tr>
                <td style="width:25%; text-align:center;"><span><a href="http://www.facebook.com/pages/Sacramento-CA/Furnitalia/65283884934" rel="nofollow" target="_blank" title="Follow Furnitalia on Facebook!" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/facebook.jpg" alt="Furnitalia on Facebook!" border="0" width="122" height="50"/></a></span></td>
                <td style="width:20%; text-align:center;"><span><a href="http://www.twitter.com/furnitalia" rel="nofollow" target="_blank" title="Follow Furnitalia on Twitter!" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/twitter.jpg" alt="Furnitalia on Twitter!" border="0" width="114" height="50"/></a></span></td>
                <td style="width:15%; text-align:center;"><span><a href="http://www.flickr.com/photos/furnitalia/" rel="nofollow" target="_blank" title="Follow Furnitalia on Flickr!" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/flickr.jpg" alt="Furnitalia on Flickr!" border="0" width="85" height="50"/></a></span></td>
                <td style="width:20%; text-align:center;"><span><a href="http://www.youtube.com/user/Furnitalia" rel="nofollow" target="_blank" title="Follow Furnitalia on Youtube!" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/youtube.jpg" alt="Furnitalia on Youtube!" border="0" width="96" height="50"/></a></span></td>
                <td style="width:20%; text-align:center;"><span><a href="http://www.yelp.com/biz/furnitalia-sacramento" rel="nofollow" target="_blank" title="Check Furnitalia out on Yelp!" style="text-decoration:none"><img src="<?php print $theme; ?>/images/webmail/yelp.jpg" alt="Furnitalia on Yelp!" border="0"  width="84" height="50" /></a></span></td>                             
              </tr>
            </table>
          </td>
        </tr>
        <!-- \SOCIAL -->
        
        <!-- PRIVACY -->
        <tr>      
			<td align="left" valign="top">
				<table width="600" border="0" cellspacing="0" cellpadding="0">          
				<tbody>
					<tr>            
						<td width="600" align="left" valign="top" style="font-family:Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight:normal;color:#999999;">
							<br/>              
							<strong>Note</strong>:
							Thanks for your interest in our Contemporary Italian furniture. By requesting information, you will be automatically included in our email communications. It's the best way to stay informed about upcoming events, 
	new products, and special announcements and offers. Each email includes links to help you send feedback or unsubscribe.<br/>
	The e-mail address you provided will be used only to send you emails from Furnitalia; we wont share it with anyone. Your privacy is important to us. Read our full <a href=http://www.furnitalia.com/policies>Privacy Policy</a> for details.
							<br/>              
							<br/>						
           
							<strong>Furnitalia Sacramento</strong>&nbsp;| 5270 Auburn Blvd | Sacramento | CA | 95841 <BR>
							<strong>Furnitalia Roseville</strong>&nbsp;&nbsp;| 1198 Roseville Parkway, Suite 120 | Roseville | CA | 95678 <BR/>
			
						</td>          
					</tr>     
				</tbody>
				</table>
			</td>    
		</tr> 
        <!-- \PRIVACY -->
      
      </table>
    </td>
  </tr>
</table>
</body>
</html>

	
	

	</div>
	
<?php
	//------------------------
	//  ATTACHMENTS
    /*print "--" . $mime_boundary . "\r\n"
      ."Content-Type: application/pdf; name=\"Furnitalia_moving_sale_coupon.pdf\"" . "\r\n"
      ."Content-Disposition: attachment; filename=\"Furnitalia_moving_sale_coupon.pdf" ."\"\r\n"
      ."Content-Transfer-Encoding: base64\r\n\r\n"
      .chunk_split(base64_encode(file_get_contents($theme . '/files/landing/Furnitalia_moving_sale_coupon.pdf')))."\r\n";
   print "--" . $mime_boundary . "--\r\n";
   print(ob_get_clean());*/
	//------------------------
?>	

<?php else: ?>

	<div class="request">

    <p><b>A new 10% OFF coupon registration was received on <?php print $site;?> on %date</b></p>

		<p>%email[first_name]</p>
		<p>%email[last_name]</p>
		<p>%email[email]</p>

	</div>
	
	<?php
	//------------------------
  //print(ob_get_clean());
 	//------------------------
  ?>


<?php endif; ?>


