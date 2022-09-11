<?php

namespace nathanwooten;

use nathanwooten\{
  Model,
  UriInterface
}

class UriModel extends Model
{

	public $root;
	public UriInterface $uri;

	public function root( $root = null)
	{

		$this->property( __FUNCTION__, $root );

	}

	public function uri( $uri = null )
	{

		$this->property( __FUNCTION__, $uri );

	}

	public function settings()
	{

		$uri = $this->uri();
		if ( $uri ) {

			$path = $uri->getComponent( PHP_URL_PATH );
			$basename = basename( $path );

			$filepath = $path . $basename . '.config';
			if ( file_exists( $filepath ) && is_readable( $filepath ) ) {
				return file_get_contents( $filepath );
			}

			return file_get_contents( $this->root . 'settings.php' );
		}

	}

	public function __construct( UriInterface $uri = null, array $config = null )
	{

		if ( isset( $uri ) ) {
			$config[ 'uri' ] = [ 'uri' => $uri ];
		}

		parent::__construct( $config );

	}

}
