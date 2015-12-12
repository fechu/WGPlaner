<?php

namespace SMCommon\Mail;

use Zend\Log\LoggerAwareInterface;

class Mailer implements LoggerAwareInterface {
	
	protected $apiHost;
	protected $apiKey;

	protected $logger;

	public function __construct($config)
	{
		$this->apiHost = isset($config['api-host']) ? $config['api-host'] : null;
		$this->apiKey = isset($config['api-key']) ? $config['api-key'] : null;

		if ($this->apiHost === null || $this->apiKey === null) {
			throw new \InvalidArgumentException(
				'api-host or api-key missing in mailer configuration!'
			);
		}
	}

	/**
	 * Sends a mail synchronously to the mailer application.
	 * 
	 * @param \SMCommon\Mail\Mail $mail The mail to send.
	 */
	public function send(Mail $mail)
	{
		$uri = $this->apiHost . 'mail/';

		$client = new \Zend\Http\Client($uri, [
			'adapter' => 'Zend\Http\Client\Adapter\Curl'
		]);
		$client->getAdapter()->setCurlOption(CURLOPT_SSL_VERIFYHOST, 0);
		$client->setMethod('POST');
		$client->setHeaders([
			'Content-Type' => 'application/json',
			'X-API-KEY' => $this->apiKey,
		]);
		$client->setRawBody($mail->getJson());
		
		$response = $client->send();

		$extra = [
			'response' => $response, 
			'mail' => $mail->getJson(),
		];
		if ($response->getStatusCode() === 201) {
			$rec = join(', ', $mail->getRecipients());
			$this->logger->info('Sent email to ' . $rec, $extra);
			return true;
		} else {
			$this->logger->warn('Failed to send email to ' . $mail->getSubject(), $extra);
			return false;
		}
	}

	public function setLogger(\Zend\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}