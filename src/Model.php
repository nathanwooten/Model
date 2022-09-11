<?php

namespace nathanwooten;

use ArrayObject;
use Exception;

class Model extends ArrayObject
{

	public array $args = [];
	public array $get = [];
	public array $config = [];
	public string $filepath;
	public string $function;
	public $getSet = true;
	public $nullValue = null;
	public $property;

	public function __construct( array $config = null )
	{

		if ( is_array( $config ) ) {
			$this->config( $config );
		}

	}

	public function config( array $config = null )
	{

		$config = $this->get( __FUNCTION__, [ 'orDefault' => $config ] );

		$hasnt = [];

		foreach ( $config as $property => $parameters ) {

			$hasnt[ $property ] = [];

			$rParameters = $this->parameters( $property );
			foreach ( $rParameters as $parameter ) {
				$paramName = $parameter->getName();
				if ( ! $this->has( $paramName, $value ) ) {
					$hasnt[ $property ][] = $paramName;
				} else {
					$has[ $property ][ $paramName ] = $parameters[ $paramName ];
				}
			}

			if ( ! empty( $hasnt[ $property ] ) ) {
				continue;
			}

			$result = $this->run( $property, $has );
			if ( $this->isError( $result ) ) {
				throw new Exception( (string) $result );
			}
		}

		try {
			while ( $hasnt ) {
				$this->config( $hasnt );
			}
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );

			$debug = $this->get( 'debug' );
			if ( $debug ) {
				throw $e;
			}
			die();
		}

	}

	public function set( $property, $value )
	{

		$this->{$property} = $value;

	}

	public function get( $property, $get = [] )
	{

		if ( ! empty( $get ) ) {
			$process = $this->process( $get );
			if ( ! is_array( $process[1] ) ) {
				$process[1] = [ $process[1] ];
			}

			if ( $this->getSet ) {
				$this->set( $property, ...$process[1] );
			}

			return $this->{$process[0]}( $property, ...$process[1] );
		}

		return $this->{$property};

	}

	public function has( $property, $value = null )
	{

		if ( ! isset( $this->$property ) ) {
			return $this->nullValue === $value;
		}

		return true;

	}

	public function args( $args = null )
	{

		return $this->property( __FUNCTION__, $args );

	}

	public function process( $args )
	{

		$process = [];

		$process[0] = key( $args );
		$process[1] = $args[ $process[0] ];

		return $process;

	}

	public function run( $function = null, $args = null )
	{

		$function = $this->get( 'function', [ 'orDefault' => $function ] );
		$callback = [ $this, $function ]

		$this->result[ $function ][] = [ $args, $callback( ...array_values( $args ) ) ];

	}

	public function property( $property = null, $value = null )
	{

		$this->set( $property, $value );
		$property = $this->get( __FUNCTION__, [ 'orDefault' => $value ] );

		$result = $this->get( $property );
		return $result;

	}

	public function parameters( $function = null )
	{

		if ( ! isset( $this->reflection[ $function ] ) ) {
			$function = $this->get( 'function', $function );

			$this->reflection[ $function ] = ( new ReflectionMethod( $this, $function ) )->getParameters();
		}

		return $this->reflection[ $function ];

	}


	public function getDefault( $property )
	{

		return $this->{$property};

	}

	public function orDefault( $property, $value = null )
	{

		if ( $this->nullValue === $value ) {
			return $this->getDefault( $property );
		}

		return $value;

	}

	public function __set( $property, $value )
	{

		return $this->set( $property, $value );

	}

	public function __get( $property )
	{

		$args = $this->get( 'args' );
		return $this->get( $property, $args );

	}

}
