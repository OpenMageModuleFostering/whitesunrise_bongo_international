<?php
class Bongo_International_Helper_Data extends Mage_Core_Helper_Abstract {
	public function sendNotificationEmail($subject, $message) {
		$customName = Mage::getStoreConfig ( 'trans_email/ident_custom2/name' );
		$customEmail = Mage::getStoreConfig ( 'trans_email/ident_custom2/email' );
		
		$html = <<<END
Hello {$strCustomName},<br /><br />
The Bongo International module has reported the following critical notification:<br /><br />
{$message}<br /><br />
If you have any questions regarding this notification, please refer to your Magento logs specific to the Bongo International module (prefixed with "bongo_"), the documentation for the module or contact Bongo International directly.<br /><br />
Sincerely,<br /><br />
Bongo International
END;
		$mail = Mage::getModel ( 'core/email' );
		$mail->setToName ( $customName );
		$mail->setToEmail ( $customEmail );
		$mail->setBody ( $html );
		$mail->setSubject ( 'Bongo International Critical Notification' . (! empty ( $subject ) ? ": {$subject}" : '') );
		$mail->setFromName ( $customName );
		$mail->setFromEmail ( $customEmail );
		$mail->setType ( 'html' );
		
		try {
			$mail->send ();
		} catch ( Exception $e ) {
			Mage::log ( "Critical Notification email could not be sent: {$e->getMessage()}; Subject: {$subject}; Message: {$message}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ), null, 'bongo_exception.log' );
		}
	}
}