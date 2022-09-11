<?php

namespace nathanwooten;

class UriFactory extends FactoryModel
{

	public $modelName = UriModel::class;

	public function __construct( UriInterface $uri = null )
	{

		if ( ! isset( $uri ) ) {
			global $uri;

			if ( ! isset( $uri ) ) {
				$this->property( 'uri', $uri );
			}
		}


		$prep = new {$this->factory};
		$prep->variables( [ 'uri' => $uri ] );

	}

}
