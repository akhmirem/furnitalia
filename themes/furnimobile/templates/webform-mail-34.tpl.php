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

	global $base_url, $theme_path;
	
	//$data = '<pre>' . print_r($submission, TRUE) .'</pre>';	
	//$data .= '<br/><br/><pre>Email var:<br/>' . print_r($email, TRUE) .'</pre>';	
	
	$is_user = $email['email'] == 3; 
	$nid = (int)$submission->data[7]['value'][0];
	$n = node_load($nid);
	
	$url = l($n->title, "node/$nid", array("attributes" => array("rel" => "nofollow", "_target" => "blank"), 'absolute' => true));
	
	$image = $n->field_image['und'][0];

	$image_html = theme('image_style', array(
		'style_name' => 'medium',
		'path' => $image['uri'],
		'alt' => $image['alt'],
	));
	$image_html_rendered = render($image_html);	
	$img = l($image_html_rendered, "node/" . $n->nid, array('html' => TRUE, 'absolute' => TRUE));
	
	$theme = $base_url . '/' . $theme_path;

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
        <!-- MENU -->
        <tr>
          <td style="text-align:center; background-color:#CCCCCC; color:#A29999; text-transform:uppercase;max-height:26px;font-family:Arial, Helvetica, sans-serif;">
            <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border-spacing:0;max-height:26px;">
              <tr><td colspan="7" style="height:7px;max-height:7px">
                <div style="height:7px;max-height:7px;overflow:hidden;"><img src="<?php print $theme; ?>/images/webmail/spacer-grey.gif" style="height:3px;" border="0"/></div>
              </td></tr>
              <tr>
                <td style="width:13%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/bedroom" rel="nofollow" style="text-decoration:none; color:#AB0000; " title="Bedroom Furniture" target="_blank">Bedroom</a></span></td>
                <td style="width:12%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/living" rel="nofollow" style="text-decoration:none; color:#AB0000; " title="Living Room Furniture" target="_blank">Living</a></span></td>
                <td style="width:13%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/dining" rel="nofollow" style="text-decoration:none; color:#AB0000; "title="Dining Room Furniture" target="_blank">Dining</a></span></td>
                <td style="width:16%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/accessories" rel="nofollow" style="text-decoration:none; color:#AB0000; " title="Accessories" target="_blank">Accessories</a></span></td>
                <td style="width:15%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/lighting" rel="nofollow" style="text-decoration:none; color:#AB0000; " title="Lighting" target="_blank">Lighting</a></span></td>
                <td style="width:14%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/contact" rel="nofollow" style="text-decoration:none; color:#AB0000; " title="Contact us" target="_blank">Contact</a></span></td>
                <td style="width:15%;text-align:center;font-size: 12px;"><span style="color:#AB0000;"><a href="http://sale.furnitalia.com/stores" rel="nofollow" style="text-decoration:none; color:#AB0000;" title="Contact us" target="_blank">Stores</a></span></td>
              </tr>
              <tr><td colspan="7" style="height:7px;max-height:7px">
                <div style="height:7px;max-height:7px;overflow:hidden;"><img src="<?php print $theme; ?>/images/webmail/spacer-grey.gif" style="height:3px;" border="0"/></div>
              </td></tr>
            </table>
          </td>
        </tr>
        <!-- \MENU -->
       
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
										Thanks for your interest...</span>
										<br/>                 
										<p>Hello <span style="font-style:italic;">%value[first_name]</span>, we have 
										received your request for the &quot;<span style="font-weight:bold; font-style:italic;"><?php print $url; ?></span>&quot;. One of Furnitalia&acute;s design consultants will be in contact with you shortly to help you choose a product that will transform your space and showcase your distinct style and taste.
										</p>      
										<p>Due to our pricing structure and our company policy we don&quot;t email or fax price quotes/proposals that reflect our discounts or percentages off. Speaking to you directly helps us maintain our personal customer service standard and protects our low price guarantee.</p>
										<p>We look forward to connecting with you and to the opportunity to earn your business.</p>
									</td>
									<td>
										<?php print $img; ?>
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
                    <a href="http://www.furnitalia.com/stores" rel="nofollow" title="Locate Furnitalia Stores" target="_blank" style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-weight:bold; font-size:14px;text-decoration:none;">Furnitalia Main Store</a>
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
                    <a href="http://www.furnitalia.com/stores" rel="nofollow" title="Locate Furnitalia Stores" target="_blank" style="color:#AB0000; font-family:Arial, Verdana, sans-serif; font-weight:bold; font-size:14px; text-decoration:none;">Fountains at Roseville</a>
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
							Thanks for your interest in our Contemporary Italian furniture. By requesting information, you will be automatically included in our email communications. Its the best way to stay informed about upcoming events, 
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

<?php else: ?>

	<div class="request">

		<p><b>A new request was created on %site on %date</b></p>

		<span><?php print $img; ?></span>

		<p>%email[item]</p>
		<p>%email[first_name]</p>
		<p>%email[last_name]</p>
		<p>%email[phone]</p>
		<p>%email[zip_code]</p>
		<p>%email[email]</p>
		<p>%email[question]</p>

		<?php if ($nid) : ?>
		<p>If you are unable to locate the item, click the link <?php print $url; ?></p>
		<?php endif; ?>
		<p><span style="font-weight:bold;">Please check Furnitalia Lead Admin page.</span></p>
	</div>


<?php endif; ?>
