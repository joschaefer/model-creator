<?php

class PositiveCurrencyType extends Type {

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

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'LOWER_THAN_ZERO' => '$this->%s < 0',
		];
	}

}
