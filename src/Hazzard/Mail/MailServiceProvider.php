<?php 

namespace Hazzard\Mail;

use Swift_Mailer;
use InvalidArgumentException;
use GuzzleHttp\Client as HttpClient;
use Hazzard\Support\ServiceProvider;
use Swift_SmtpTransport as SmtpTransport;
use Swift_MailTransport as MailTransport;
use Hazzard\Mail\Transport\MailgunTransport;
use Hazzard\Mail\Transport\MandrillTransport;
use Hazzard\Mail\Transport\SparkPostTransport;
use Swift_SendmailTransport as SendmailTransport;

class MailServiceProvider extends ServiceProvider 
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$me = $this;

		$this->app->bindShared('mailer', function($app) use($me) {
			$me->registerSwiftMailer();

			$mailer = new Mailer($app['view'], $app['swift.mailer'], $app['events']);

			$from = $app['config']['mail.from'];

			if (is_array($from) && isset($from['address'])) {
				$mailer->alwaysFrom($from['address'], $from['name']);
			}

			return $mailer;
		});
	}

	/**
	 * Register the Swift Mailer instance.
	 *
	 * @return void
	 */
	public function registerSwiftMailer()
	{
		$this->app['swift.mailer'] = new Swift_Mailer(
			$this->createDriver($this->app['config']['mail'])
		);
	}

	/**
	 * Create a Swift Transport driver.
	 * 
	 * @param  array  $config
	 * @return \Swift_Transport
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createDriver($config)
	{
		switch ($config['driver']) {
			case 'smtp':
				$transport = SmtpTransport::newInstance(
					$config['host'], $config['port']
				);

				if (! empty($config['encryption'])) {
					$transport->setEncryption($config['encryption']);
				}

				if (! empty($config['username'])) {
					$transport->setUsername($config['username']);
					$transport->setPassword($config['password']);
				}

				return $transport;

			case 'mail':
				return MailTransport::newInstance();

			case 'sendmail':
				return SendmailTransport::newInstance($config['sendmail']);

			case 'mailgun':
				$config = $this->app['config']['services.mailgun'];

				return new MailgunTransport(
					new HttpClient, $config['secret'], $config['domain']
				);

			case 'mandrill':
				$config = $this->app['config']['services.mandrill'];

				return new MandrillTransport(new HttpClient, $config['secret']);

			case 'sparkpost': 
				$config = $this->app['config']['services.sparkpost'];
		        
		        return new SparkPostTransport(new HttpClient, $config['secret']);
		}
		
		throw new InvalidArgumentException('Invalid mail driver.');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('mailer');
	}	
}
