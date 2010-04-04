<?php defined('_JEXEC') or die('Restricted access'); ?>
<script>
    //Close modal frame
function close_modal(elid, unidad_id )
{
	document.getElementById( 'sbox-window' ).close();
	actualizar_lista();

        if (elid>0) edit(elid, unidad_id );
        //edit(elid, unidad_id );


}
function jSelectArticle(id, title, object) {
			document.getElementById(object ).value = id;
			document.getElementById(object + '_name').value = title;
                        document.getElementById(object + '_tmp').value = title;
			document.getElementById('sbox-window').close();
		}

</script>
<?php  if($this->isframe) {$file = 'index3.php';}else{$file = 'index.php';} ?>
<?php echo JHTML::_( 'form.token' ); ?>
<?php jimport('joomla.html.pane'); ?>
<div class="col100">
 

        <form action="<?php echo $file?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
                <?php if($this->isframe) { ?>
                <table>
		<tr>
			<td align="left" width="100%">

			</td>
			<td nowrap="nowrap">
                <?php echo $this->bar->render();?>
                        </td></tr></table>
                        <?php } ?>
		<input type="hidden" name="option" value="com_perchadownloadsattach" />
                <input type="hidden" name="id" value="<?php echo $this->unidad->id; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="controller" value="unidad" />
                
                <table width="100%"><tr><td width="850">
                <table class="admintable"> 
		<tr>
			<td width="100" align="right" class="key">
				<label for="nombre">
					<?php echo JText::_( 'Title' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->unidad->title;?>" />
			</td> 
		</tr>
		<tr>
			<td class="key">
				<?php  
				$published		=  "";
				if($this->unidad->published) $published	=  "checked='checked'";
				?>
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td><input  name="published" type="checkbox" value="1" <?php echo $published;?>  /></td>
		</tr>
                <?php  if(!$this->isframe) {?>
                <tr>
                    <td class="key">
				<?php
				$published		=  "";
				if($this->unidad->published) $published	=  "checked='checked'";
				?>
				<?php echo JText::_( 'Article attached' ); ?>:
			</td>
		    <td>
                        
                        <input  name="articleid" id="articleid" type="hidden"  value="<?php echo $this->unidad->articleid;?>"  />
                        <input  name="articleid_name" id="articleid_name" type="hidden"  value="<?php echo $this->unidad->articleid_name;?>"  size="32" maxlength="250"  />
                        <input  name="articleid_tmp" id="articleid_tmp" type="text"  value="<?php echo $this->unidad->articleid_name;?>"  size="32" maxlength="250"  disabled  />
                        <div class="right"><div class="button2-left"><div class="blank"><a class="modal-button thumbnail" id="popup-thumbnail"  title="<?php echo JText::_( 'Article attached' ); ?>" href="index.php?option=com_content&task=element&tmpl=component&object=articleid" rel="{handler: 'iframe', size: {x: 550, y: 450}}">
                        <?php echo JText::_( 'Article attached' ); ?>
                        </a></div></div> </div>
                    </td>
                    
                </tr>
                <?php }else{ ?>
                        <input  name="articleid" id="articleid" type="hidden"  value="<?php echo $this->unidad->articleid;?>"  />
                        <input  name="articleid_name" id="articleid_name" type="hidden"  value="<?php echo $this->unidad->articleid_name;?>"  size="32" maxlength="250"  />
                        <input  name="articleid_tmp" id="articleid_tmp" type="hidden"  value="<?php echo $this->unidad->articleid_name;?>"  size="32" maxlength="250"  disabled  />

                <?php } ?>
                <!--<tr>
			<td width="100" align="right" class="key">
				<label for="intro">
					<?php echo JText::_( 'Intro' ); ?>:
				</label>
			</td>
			<td>
				<?php
				// parameters : areaname, content, width, height, cols, rows, show xtd buttons
				echo $this->editor->display( 'intro',  $this->unidad->intro, '550', '200', '60', '20') ;
				?>
			</td>

		</tr>  -->
		 
		
                <?if( $this->unidad->id > 0) { ?>
                <tr>
                    <td  class="key">
                        <?php echo JText::_( 'File' ); ?>:
                    </td>
                    <td>
                        <div id="introimage" style="border: 1px dashed #ee3344; padding:5px; margin-bottom:5px;">
                        <?php
                        $file = 'images/downloads/'.$this->unidad->file; 
                        if(file_exists($this->mosConfig_absolute_path.$file) && (!empty($this->unidad->file))){
                            echo '<a href="'.JURI::base().'/../../images/downloads/'.$this->unidad->file.'" target="_blanck">'.$this->unidad->file.'</a>';
                        
                             echo '<input type="hidden" name="image" value="" />';
                        }else{
                            ?>
                            <input type="file" name="image" onchange="document.forms['adminForm']['file'].value= this.value" />
                        <?php
                        }
                        ?>
                        </div>
                         <!-- <div class="right"><div class="button2-left"><div class="blank"><a class="modal-button thumbnail" id="popup-thumbnail"  title="<?php echo JText::_( 'Unit image' ); ?>" href="index3.php?option=com_perchadownloadsattach&controller=unidad&task=unitimage&unidad_id=<?php echo $this->unidad->id; ?>" rel="{handler: 'iframe', size: {x: 550, y: 450}}">
                        <?php echo JText::_( 'Upload' ); ?>
                        </a></div></div> </div>-->
                        <input type="hidden" name="file" value="<?php echo $this->unidad->file;?>" />
                        
                        <br /> <?php echo JText::_( 'Delete file' ); ?>
                        <input type="checkbox" name="delete" value="1" />
                    </td>
                </tr>                 
                <?php }else{  ?>
                <tr><td colspan="2"><?php echo JText::_( 'First create the new, then you can upload the image' ); ?>  </td></tr>
                <?php }  ?>
                <tr>
			<td width="100" align="right" class="key">
				<label for="description">
					<?php echo JText::_( 'description' ); ?>:
				</label>
			</td>
			<td>
				<?php
				// parameters : areaname, content, width, height, cols, rows, show xtd buttons
				echo $this->editor->display( 'description',  $this->unidad->description, '550', '200', '60', '20') ;
				?>
			</td>

		</tr> 
                <tr><td></td><td><br /><br /></td></tr>

               
                </table>
                        </td><td valign="top">
                            <?php
            jimport('joomla.html.pane');
            jimport( 'joomla.html.parameter.element.calendar' );

            $pane =& JPane::getInstance( 'sliders' );



            echo $pane->startPane( 'content-pane' );

            // First slider panel
            // Create a slider panel with a title of SLIDER_PANEL_1_TITLE and a title id attribute of SLIDER_PANEL_1_NAME
            echo $pane->startPanel( JText::_( 'Dates' ), 'PARAMETERS' );
            ?>
            <table class="admintable">
		<tr>
                    <td>
            <?php echo JText::_( 'Creation date ' ); ?>:
                    </td>
                    <td><?php echo JHTML::_('date',  $this->unidad->createdate, JText::_('DATE_FORMAT_LC1') ); ?>
                        <input type="hidden" id="createdate" name="createdate" value="<?php echo $this->unidad->createdate;?>" />
                    </td>
                </tr>
                <tr>
                    <td> 
                        <label for="publishdate">
                        <?php echo JText::_( 'Publishing date' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?php
                        echo  JHTML::_('calendar', $this->unidad->publishdate, 'publishdate', 'publishdate', $format = '%Y-%m-%d',array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19'));
                        ?>
                    </td>
                </tr> 
            </table>
 <?php
           
           
            // Display the parameters defined in the <params> group with no 'group' attribute
            //echo $this->params->render( 'params' );
            echo $pane->endPanel();

            echo $pane->endPane();

?></td></tr></table>
               
                	
                </form> 
            

</div> 

