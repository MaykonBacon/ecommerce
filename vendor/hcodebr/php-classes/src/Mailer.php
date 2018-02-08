<?php

namespace Hcode;

use Rain\Tpl;

class Mailer{

	const USERNAME = "rpcel2017@gmail.com";
	const PASSWORD = "reparo2017";
	const NAME_FROM = "MBSTORE";

	private $mail;

	public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
	{

		$config = array(
		    //"base_url"      => null,
		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT']."/views/email/",
		    "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
		    "debug"         => false
		);

		Tpl::configure( $config );
		//Ser der treta olha aqui ()
		$tpl = new Tpl();

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName, true);

		$this->mail = new \PHPMailer;

		//Tell PHPMailer to use SMTP
		#Método que prepara o smtp
		$this->mail->isSMTP();

		#Correção do erro
		$this->mail->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);

		//Enable SMTP debugging
		// 0 = off (for production use) (produção)
		// 1 = client messages (testes)
		// 2 = client and server messages (Desenvolvimento)
		$this->mail->SMTPDebug = 0;

		//Set the hostname of the mail server
		#Se quiser colocar outro servidor é só colocar após o ;
		$this->mail->Host = 'smtp.gmail.com';
		// use
		// $this->mail->Host = gethostbyname('smtp.gmail.com');
		// if your network does not support SMTP over IPv6

		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		#Porta de envio de emails
		$this->mail->Port = 587;

		//Set the encryption system to use - ssl (deprecated) or tls
		#Segurança
		$this->mail->SMTPSecure = 'tls';

		//Whether to use SMTP authentication
		#Se vai ser autenticado
		$this->mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$this->mail->Username = Mailer::USERNAME;

		//Password to use for SMTP authentication
		$this->mail->Password = Mailer::PASSWORD;

		//Set who the message is to be sent from
		#Quem está mandando
		$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

		//Set an alternative reply-to address
		#Para quem responder
		//$this->mail->addReplyTo('maykon.soares@sga.pucminas.br', 'MaykonBacon');

		//Set who the message is to be sent to
		#Para quem vou enviar
		$this->mail->addAddress($toAddress, $toName);

		//Set the subject line
		#Assunto do email
		$this->mail->Subject = $subject;

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		#Conteúdo do email
		$this->mail->msgHTML($html);

		//Replace the plain text body with one created manually
		#Corpo alternativo caso não leia o html
		$this->mail->AltBody = 'Segue o email';

		//Attach an image file
		#Imagens dentro do email
		//$this->mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		#Verificar se contem erros

		//Section 2: IMAP
		//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
		//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
		//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
		//be useful if you are trying to get this working on a non-Gmail IMAP server.
		/*
		function save_mail($mail)
		{
		    //You can change 'Sent Mail' to any other folder or tag
		    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

		    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
		    $imapStream = imap_open($path, $mail->Username, $mail->Password);

		    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
		    imap_close($imapStream);

		    return $result;
		}

		*/

		}

		public function send()
		{
			return $this->mail->send();
		}
}