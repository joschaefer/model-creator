<?php

class CharType extends Type {

	private $length;

	public function __construct( $length ) {
		$this->length = $length;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return 'string';
	}

	/**
	 * @return bool
	 */
	public function isObject(): bool {
		return false;
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'trim($this->%s) == false';
	}

	/**
	 * @return array
	 */
	public function validationChecks(): array {

		return [
			'TOO_LONG' => 'mb_strlen($this->%s) > ' . strval($this->length),
			'TOO_SHORT' => 'mb_strlen($this->%s) < ' . strval($this->length),
		];
	}

}
