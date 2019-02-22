<?php

class IbanType extends StringType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'INVALID' => '!Sepa::validateIban($this->%s)'
		];
	}

}
