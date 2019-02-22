<?php

class BooleanType extends Type {

	/**
	 * @return string
	 */
	public function getName(): string {
		return 'bool';
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
		return '$this->is%s()';
	}

	/**
	 * @return string
	 */
	public function pdoParamType(): string {
		return 'PARAM_BOOL';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return '';
	}

}
