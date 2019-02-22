<?php

class MandateReferenceType extends StringType {

	/**
	 * @return array
	 */
	public function validationChecks(): array {
		return [
			'INVALID' => '!Sepa::validateMandateReference($this->%s)'
		];
	}

}
