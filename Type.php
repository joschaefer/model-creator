<?php

abstract class Type {

	/**
	 * @return string
	 */
	public abstract function getName(): string;

	/**
	 * @return bool
	 */
	public abstract function isObject(): bool;

	/**
	 * @return string
	 */
	public function serialization(): string {
		return '$this->get%s()';
	}

	/**
	 * @return mixed
	 */
	public function deserialization(): string {
		return '$result[\'%s\']';
	}

	/**
	 * @return string
	 */
	public function pdoParamType(): string {
		return 'PARAM_STR';
	}

	/**
	 * @return string
	 */
	public abstract function existenceCheck(): string;

	/**
	 * @return array
	 */
	public function validationChecks() {
		return [];
	}

	/**
	 * @return array
	 */
	public function uses(): array {
		return [];
	}

}
