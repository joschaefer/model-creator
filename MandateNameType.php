<?php

class MandateNameType extends StringType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'TOO_LONG' => 'mb_strlen($this->%s) > 70',
			'INVALID' => '!Sepa::validateName($this->%s)'
		];
	}

}
