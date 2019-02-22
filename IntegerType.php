<?php

class IntegerType extends Type {

	/**
	 * @return string
	 */
	public function getName(): string {
		return 'int';
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
	public function pdoParamType(): string {
		return 'PARAM_INT';
	}

	/**
	 * @return string
	 */
	public function existenceCheck(): string {
		return 'is_null($this->%s)';
	}

}
