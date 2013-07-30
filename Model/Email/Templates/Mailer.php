<?php

class Aschroder_SMTPPro_Model_Email_Template_Mailer extends Mage_Core_Model_Email_Template_Mailer
{
    /**
     * Send all emails from email list
     * @see self::$_emailInfos
     *
     * @return Mage_Core_Model_Email_Template_Mailer
     */
    public function send()
    {
    	if (Mage::helper('aschroder_smtppro')->isDisabled($this->getTemplateId())) {
    		return $this;
    	}

        $emailTemplate = Mage::getModel('core/email_template');
        // Send all emails from corresponding list
        while (!empty($this->_emailInfos)) {
            $emailInfo = array_pop($this->_emailInfos);
            // Handle "Bcc" recepients of the current email
            $emailTemplate->addBcc($emailInfo->getBccEmails());
            // Set required design parameters and delegate email sending to Mage_Core_Model_Email_Template
            $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $this->getStoreId()))
                ->sendTransactional(
                $this->getTemplateId(),
                $this->getSender(),
                $emailInfo->getToEmails(),
                $emailInfo->getToNames(),
                $this->getTemplateParams(),
                $this->getStoreId()
            );
        }
        return $this;
    }
}
