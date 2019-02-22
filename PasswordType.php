<?php

class PasswordType extends Type {

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
		return '$this->get%sHash()';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'trim($this->%s) == false';
	}

}
