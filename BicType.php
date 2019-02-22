<?php

class BicType extends StringType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'INVALID' => '!Sepa::validateBic($this->%s)'
		];
	}

}
