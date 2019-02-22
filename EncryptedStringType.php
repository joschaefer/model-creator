<?php

class EncryptedStringType extends StringType {

	/**
	 * @return string
	 */
	public function serialization(): string {
		return '$this->%s ? Crypto::encrypt( $this->%s,  $key ) : null';
	}

	/**
	 * @return string
	 */
	public function deserialization(): string {
		return '$result[\'%s\'] ? Crypto::decrypt( $result[\'%s\'],  $key ) : null';
	}

}
