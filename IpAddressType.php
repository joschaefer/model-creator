<?php

class IpAddressType extends Type {

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
	public function serialization(): string {
		return '$this->get%s() ? inet_pton( $this->get%s() ) : null';
	}

	/**
	 * @return mixed
	 */
	public function deserialization(): string {
		return '$result[\'%s\'] ? inet_ntop( $result[\'%s\'] ) : null';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'trim($this->%s) == false';
	}

}
