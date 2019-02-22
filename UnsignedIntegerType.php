<?php

class UnsignedIntegerType extends IntegerType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'IS_NEGATIVE' => '0 > $this->%s',
		];
	}

	/**
	 * @return string
	 */
	public function pdoParamType(): string {
		return 'PARAM_INT';
	}

}
