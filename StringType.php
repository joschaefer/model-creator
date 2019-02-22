<?php

class StringType extends Type {

	private $maxLength;

	public function __construct( $maxLength = 0 ) {
		$this->maxLength = $maxLength;
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

		if( $this->maxLength == 0 ) {
			return [];
		}

		return [
			'TOO_LONG' => 'mb_strlen($this->%s) > ' . strval($this->maxLength),
		];
	}

}
