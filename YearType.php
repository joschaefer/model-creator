<?php

class YearType extends Type {

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
		return true;
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

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'TOO_LOW' => '$this->%s < 1000',
			'TOO_HIGH' => '$this->%s > 9999',
		];
	}

}
