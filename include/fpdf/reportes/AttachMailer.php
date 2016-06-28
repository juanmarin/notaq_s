<?php

/*
 * @author : nicolas Lattuada <nicolas@icare-net.com>
 * send emails with pdf attachment
 */

class AttachMailer{
	
	private $from, $to, $subject, $mess, $hash, $output;
	private $documents = Array();
	
	/*
	 * @params from: adresse de l'envoyeur(+reponse)
	 * 		   to : adresse a qui on envoie
	 * 		   subject : le sujet du message
	 * 		   mess : le message lui meme(format html)
	 */
	function __construct($_from, $_to, $_subject, $_mess){
		$this->from = $_from;
		$this->to = $_to;
		$this->subject = $_subject;
		$this->mess = $_mess;
		$this->hash = md5(date('r', time()));
	}
	
	/*
	 * @params url du document ajouté
	 */	
	public function attachFile($url, $name = ""){
		$attachment = chunk_split(base64_encode(file_get_contents($url)));
		$docName    = $name == "" ? basename($url) : $name;
		$randomHash = $this->hash;
		$docOutput = "--PHP-alt-$randomHash--\r\n\r\n"
					 ."--PHP-mixed-$randomHash\r\n"
					 ."Content-Type: application/pdf; name=\"$docName\" \r\n"
					 ."Content-Transfer-Encoding: base64 \r\n"
					 ."Content-Disposition: attachment \r\n\r\n"
					 .$attachment . "\r\n";
		$this->documents[] = $docOutput;
	}
	
	private function makeMessage(){
		$randomHash = $this->hash;
		$messageOutput = "--PHP-mixed-$randomHash\r\n"
						 ."Content-Type: multipart/alternative; boundary=PHP-alt-$randomHash\r\n\r\n"
						 ."--PHP-alt-$randomHash\r\n"
						 ."Content-Type: text/plain; charset='iso-8859-1'\r\n"
						 ."Content-Transfer-Encoding: 7bit\r\n\r\n"
						 .$this->mess . "\r\n\r\n"
						 ."--PHP-alt-$randomHash\r\n"
						 ."Content-Type: text/html; charset='iso-8859-1'\r\n"
						 ."Content-Transfer-Encoding: 7bit\r\n\r\n"
						 . $this->mess . "\r\n";
						 
		foreach($this->documents as $document){
			$messageOutput .= $document; 
		}
		$messageOutput .="--PHP-mixed-$randomHash;--";
		$this->output = $messageOutput;
	}
	
	public function send(){
		$this->makeMessage();
		$from = $this->from;
		$randomHash = $this->hash;
		$headers = "From: $from\r\nReply-To: $from";
		$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-$randomHash\"";
		$mail_sent = @mail( $this->to, $this->subject, $this->output, $headers );
		return $mail_sent ? true : false;
	}
	
}