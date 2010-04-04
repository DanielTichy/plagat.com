<?php defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript">
    function submitbutton(task)
    {
        if(task == "help_unit") {
            window.open("components/com_perchadownloadsattach/help/index.html","help","width=900px, height=900px")
        }
    }
    </script>
<table class="adminform">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
                        <div style="float:left;">
                                <div class="icon">
                                    <a href="index.php?option=com_perchadownloadsattach&section=unidades">
                                        <img src="components/com_perchadownloadsattach/images/item_units.png" alt="News"  />
                                        <span><?php echo JText::_( 'Files Attached' );?></span>
                                    </a>
                                </div>
                        </div> 
			
                         <div style="float:left;">
                                <div class="icon">
                                    <a href="index.php?option=com_perchadownloadsattach&section=editcss">
                                        <img src="components/com_perchadownloadsattach/images/item_css.png" alt="Css"  />
                                        <span><?php echo JText::_( 'Edit Css Frontend' );?></span>
                                    </a>
                                </div>
                        </div><p>&nbsp;</p>
			<div style="width:100%;text-align:center;padding:0;margin:30px 0 0 0;border:0">
				 
			</div>
                        
			</div>
		</td>

		<td width="45%" valign="top">
			<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
			<div style="float:right;margin:10px;">
				<img src="components/com_perchadownloadsattach/images/logo.gif" alt="Percha.com"  /></div>
			<h3>Version</h3>
			<p>0.5beta</p>

			<h3>Copyright</h3>
			<p>© 2009 - 2012 Cristian Grañó Reder<br />
			<a href="http://www.percha.com/" target="_blank">www.percha.com</a></p>

			<h3>License</h3>
			<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>
			<p>&nbsp;</p> 

			</div>
		</td>
	</tr>
</table>
