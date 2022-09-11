<?php

namespace nathanwooten;

class PrepModel extends Model
{

	public $modelName;
	public $variables;

	public function modelName( $modelName )
	{

		return $this->property( __FUNCTION__, $modelName );

	}

	public function variables( array $variables = null )
	{

		return $this->property( __FUNCTION__, $variables );

	}

	public function create()
	{

		return new {$this->get( 'modelName' )}( $this->variables );

	}

}
