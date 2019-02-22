<?php

class UriType extends Type {

	/**
	 * @return string
	 */
	public function getName(): string {
		return 'UriInterface';
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
	public function serialization(): string {
		return '$this->get%s() ? (string) $this->get%s() : null';
	}

	/**
	 * @return mixed
	 */
	public function deserialization(): string {
		return '$result[\'%s\'] ? Uri::createFromString( $result[\'%s\'] ) : null';
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
	public function uses(): array {
		return [
			'Psr\Http\Message\UriInterface',
			'Slim\Http\Uri',
		];
	}

}
