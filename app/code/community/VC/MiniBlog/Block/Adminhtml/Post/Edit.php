<?php
class VC_MiniBlog_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'vc_miniblog';
        $this->_controller = 'adminhtml_post';
        
        $this->_updateButton('save', 'label', Mage::helper('vc_miniblog')->__('Save Post'));
        $this->_updateButton('delete', 'label', Mage::helper('vc_miniblog')->__('Delete Post'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('description') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'description');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'description');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action + 'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('post_data') && Mage::registry('post_data')->getId() ) {
            return Mage::helper('vc_miniblog')->__("Edit Post '%s'", $this->htmlEscape(Mage::registry('post_data')->getTitle()));
        } else {
            return Mage::helper('vc_miniblog')->__('Add Post');
        }
    }
}