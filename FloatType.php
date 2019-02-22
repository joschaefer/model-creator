<?php

class FloatType extends Type {

	/**
	 * @return string
	 */
	public function getName(): string {
		return 'float';
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
		return 'is_null($this->%s)';
	}

}
