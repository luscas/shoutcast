<?php
/**
 * Shoutcast Class
 *
 * @category  Streaming data
 * @package   Shoutcast
 * @author    Lucas Paz <lucaoxita@gmail.com>
 * @copyright Copyright (c) 2012-2018
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/luscas/shoutcast
 * @version   1.0.0
 */
class Shoutcast {
	/**
	 * instance of shoutcast data
	 * @var void
	 */
	protected $html = null;

	/**
	 * instance of shoutcast link
	 * @var string
	 */
	protected $link = null;

	/**
	 * @param string $link
	 */
	public function __construct(string $link)
	{
		$this->link = $link;
		$this->connect();
	}

	/**
	 * A method for connecting to shoutcast
	 *
	 * @return void
	 */
	protected function connect()
	{
		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL            => $this->link,
			CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0',
			CURLOPT_RETURNTRANSFER => 1,
		]);

		$this->html = curl_exec( $ch );

		curl_close($ch);
	}

	/**
	 * A method to get shoutcast data
	 *
	 * @param string $param
	 * @return void
	 */
	public function get($param)
	{
		if( !$this->html ) return 'Offline';

		$types = [
			'broadcaster' => 'Stream Title',
			'program'     => 'Stream Genre',
			'music'       => 'Current Song',
			'url'         => 'Stream URL',
			'quality'     => 'Content Type',
			'online_time' => 'Average Listen Time'
		];

		switch($param):
			case 'listeners':
				// Get listeners
				$listeners = explode("<b>Stream is up at ", $this->html);
				$listeners = explode("kbps with <B>", $listeners[1]);
				return explode(" of", $listeners[1])[0];
			break;

			case 'uniques':
				// Get Uniques
				$uniques = explode('listeners (', $this->html);
				return explode(' unique)', $uniques[1])[0];
			break;

			default:
				// Get broadcaster, program, music, url, quality and online_time
				$other = explode( $types[$param] . ': </font></td><td><font class=default><b>', $this->html );
				return preg_replace('#<a href=\"(.*)\">(https://|http://)(.*)</a>#', '$3', explode( '</b>', $other[1] )[0]);
			break;
		endswitch;
	}

	/**
	 * A method to show all shoutcast data
	 *
	 * @return void
	 */
	public function all()
	{
		return json_encode([
			'broadcaster' => $this->get('broadcaster'),
			'program'     => $this->get('program'),
			'music'       => $this->get('music'),
			'url'         => $this->get('url'),
			'quality'     => $this->get('quality'),
			'online_time' => $this->get('online_time'),
			'listeners'   => $this->get('listeners'),
			'uniques'     => $this->get('uniques')
		]);
	}
}

$shoutcast = new Shoutcast('http://shoutcast.radio.com'); // Example: http://127.0.0.1:1234
echo $shoutcast->all();