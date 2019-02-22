<?php

class ObjectType extends Type {

	/** @var string */
	private $name;

	public function __construct( string $name ) {
		$this->name = ucfirst($name);
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function isObject(): bool {
		return true;
	}

	/**
	 * @return string
	 */
	public function serialization(): string {
		return '$this->get%s() ? $this->get%s()->getId() : null';
	}

	/**
	 * @return mixed
	 */
	public function deserialization(): string {
		return 'new ' . $this->getName() . '( $result[\'%s\'] )';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'is_null($this->%s)';
	}

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'NOT_FOUND' => '!' . $this->getName() . '::existsWithId( $db, $this->%s->getId() )',
		];
	}

}
