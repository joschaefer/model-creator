<?php

class Model {

	/** @var string */
	private $name;

	/** @var string */
	private $singleName;

	/** @var bool */
	private $readOnly;

	/** @var Attribute[] */
	private $attributes;

	/** @var \ICanBoogie\Inflector */
	private $inflector;

	/**
	 * Model constructor.
	 *
	 * @param      $name
	 * @param bool $readOnly
	 * @param null $singleName
	 *
	 * @throws RuntimeException
	 */
	public function __construct( $name, $readOnly = false, $singleName = null ) {

		if( in_array( $name, ['Model', 'SimpleModel', 'Controller', 'DatabaseConnection', 'DatabaseConfiguration', 'Enum', 'Sepa', 'SepaDirectDebit', 'SepaTransaction',
				'AccountStatus', 'EventStatus', 'EventReplyVia', 'EventReplyResponse', 'Gender', 'AddressType', 'EmailAddressType', 'PhoneNumberType',
				'GeocodingAPI', 'IpLocationApi', 'UserAgentAPI', 'Token', 'Authentication', 'VCalendar', 'VCalendarException', 'VCard', 'VCardException', 'VEvent'] ) ) {
			throw new RuntimeException("Invalid model name: $name");
		}

		$this->inflector = \ICanBoogie\Inflector::get();
		$this->name = $this->inflector->singularize($name);
		$this->readOnly = $readOnly;
		$this->singleName = $singleName ? $this->inflector->singularize($singleName) : $this->inflector->camelize( $this->name, true );

	}

	/**
	 * @return string
	 */
	public function getClassName(): string {
		return $this->inflector->camelize( $this->name, false );
	}

	/**
	 * @return string
	 */
	public function getTextName(): string {
		return mb_strtolower( $this->inflector->humanize( $this->name ) );
	}

	/**
	 * @return string
	 */
	public function getTextNameTitle(): string {
		return $this->inflector->pluralize( $this->inflector->humanize( $this->name ) );
	}

	/**
	 * @return string
	 */
	public function getTableName(): string {
		return $this->inflector->pluralize( $this->inflector->underscore( $this->name ) );
	}

	/**
	 * @return string
	 */
	public function getSingleName(): string {
		return $this->singleName;
	}

	/**
	 * @return string
	 */
	public function getSingleNamePlural(): string {
		return $this->inflector->pluralize( $this->singleName );
	}

	public function isReadOnly(): bool {
		return $this->readOnly;
	}

	/**
	 * @return string
	 */
	public function getArticle(): string {

		$firstLetter = substr( $this->getTextName(), 0, 1 );

		if( in_array( $firstLetter, ['a', 'e', 'i', 'o', 'u'] ) ) {
			return 'an';
		}

		return 'a';

	}

	/**
	 * @return Attribute[]
	 */
	public function getAttributes(): array {
		return $this->attributes;
	}

	/**
	 * @param Attribute $attribute
	 */
	public function addAttribute( Attribute $attribute ) {
		$this->attributes[] = $attribute;
	}

	public function createClass( string $namespace ): string {

		$constructor = '';
		$attributeList = '';
		$defaultSettings = '';
		$methods = '';
		$uses = [];
		$use = '';

		foreach( $this->attributes as $attribute ) {
			$uses = array_merge( $uses, $attribute->getType()->uses() );
			$attributeList .= $attribute->createAttribute();
			$defaultSettings .= $attribute->createDefaultSettings();
			$methods .= $attribute->createGetterAndSetter($this);
		}

		if( sizeof($uses) > 0 ) {
			sort( $uses );
			$uses = array_unique( $uses );
			$use = "\n\nuse " . implode( ";\nuse ", $uses ) . ";";
		}

		if( trim($defaultSettings) != false ) {
			$constructor = "\t/**
	 * {@inheritDoc}
	 */
	public function __construct( int \$id = null, \DateTime \$createdAt = null, \DateTime \$updatedAt = null ) {

		parent::__construct( \$id, \$createdAt, \$updatedAt );

		// Set default values
$defaultSettings
	}

";
		}

		$methods .= $this->fromDatabaseResultMethod() . "\n\n";
		$methods .= $this->jsonSerializeMethod() . "\n\n";
		$methods .= $this->validateMethod() . "\n\n";
		$methods .= $this->databaseManipulationMethods() . "\n\n";

		return "<?php

namespace $namespace;$use

class " . $this->getClassName() . " extends " . ( $this->readOnly ? 'Simple' : '' ) . "Model {

$attributeList$constructor$methods}
";

	}

	public function createController( string $namespace ): string {

		$model = '$' . $this->getSingleName();
		$setters = '';
		$params = '';

		foreach( $this->attributes as $attribute ) {

			if( $attribute->isCollection() ) {
				continue;
			}

			if( $attribute->getType() instanceof BooleanType ) {
				$params .= "\t\t$" . $attribute->getVariableName() . " = \$this->toBool( \$request->getParsedBodyParam( '" . $attribute->getArrayName() . "', '" . $attribute->getDefault() . "' ) );\n";
			} else {
				$params .= "\t\t$" . $attribute->getVariableName() . " = \$request->getParsedBodyParam( '" . $attribute->getArrayName() . "'" . ( !$attribute->isOptional() ? ", " . ( $attribute->getDefault() ?? "''" ) : '' ) . " );\n";
			}
			$setters .= "\t\t" . $model . "->set" . $attribute->getMethodName() . "( $" . $attribute->getVariableName() . " );\n";

		}

		$controller = "<?php

namespace $namespace;

use Slim\Http\Request;
use Slim\Http\Response;

class " . $this->getClassName() . "Controller extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function get( Request \$request, Response \$response, array \$args ): Response {

		/** @var \Memberlist\\" . $this->getClassName() . " $model */
		$model = " . $this->getClassName() . "::findById( \$this->db, \$args['id'] );

		if( is_null($model) ) {
			// Resource not found
			return \$response->withStatus(404);
		}

		return \$response->withJson( $model, 200, JSON_UNESCAPED_UNICODE );

	}

	/**
	 * {@inheritDoc}
	 */
	public function getAll( Request \$request, Response \$response, array \$args ): Response {

		$" . $this->getSingleNamePlural() . " = " . $this->getClassName() . "::findAll( \$this->db );
		return \$response->withJson( $" . $this->getSingleNamePlural() . ", 200, JSON_UNESCAPED_UNICODE );

	}

";

		if( $this->readOnly ) {

			$controller .= "	/**
	 * {@inheritDoc}
	 */
	public function post( Request \$request, Response \$response, array \$args ): Response {
		// Method Not Allowed
		return \$response->withStatus(405);
	}

	/**
	 * {@inheritDoc}
	 */
	public function put( Request \$request, Response \$response, array \$args ): Response {
		// Method Not Allowed
		return \$response->withStatus(405);
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete( Request \$request, Response \$response, array \$args ): Response {
		// Method Not Allowed
		return \$response->withStatus(405);
	}

}
";

		} else {

			$controller .= "	/**
	 * {@inheritDoc}
	 */
	public function post( Request \$request, Response \$response, array \$args ): Response {

$params
		$model = new " . $this->getClassName() . "();
$setters
		if( " . $model . "->save( \$this->db ) ) {
			return \$response->withRedirect( '/" . $this->getTableName() . "/' . " . $model . "->getId(), 201 );
		}

		return \$response->withJson( ['errors' => " . $model . "->getErrors()], 400, JSON_UNESCAPED_UNICODE );

	}

	/**
	 * {@inheritDoc}
	 */
	public function put( Request \$request, Response \$response, array \$args ): Response {

$params
		/** @var \Memberlist\\" . $this->getClassName() . " $model */
		$model = " . $this->getClassName() . "::findById( \$this->db, \$args['id'] );

		if( is_null($model) ) {
			// Resource not found
			return \$response->withStatus(404);
		}

$setters
		if( " . $model . "->save( \$this->db ) ) {
			return \$response->withJson( $model, 200, JSON_UNESCAPED_UNICODE );
		}

		return \$response->withJson( ['errors' => " . $model . "->getErrors()], 400, JSON_UNESCAPED_UNICODE );

	}

	/**
	 * {@inheritDoc}
	 */
	public function delete( Request \$request, Response \$response, array \$args ): Response {

		$model = new " . $this->getClassName() . "( \$args['id'] );

		if( " . $model . "->delete( \$this->db ) ) {
			return \$response->withStatus(204);
		} else if( " . $model . "->hasErrors() ) {
			return \$response->withJson( ['errors' => " . $model . "->getErrors()], 400, JSON_UNESCAPED_UNICODE );
		}

		// If deletion failed but no errors occurred, the resource doesn't exist
		return \$response->withStatus(404);

	}

}
";

		}

		return $controller;

	}


	private function validateMethod(): string {

		$validation = "	/**
	 * {@inheritDoc}
	 */
	public function validate( \PDO \$db ): bool {\n";

		foreach( $this->attributes as $attribute ) {

			if( $attribute->getType() instanceof BooleanType ) {
				continue;
			}

			if( $attribute->isCollection() && !$attribute->isOptional() ) {

				$validation .= "\n\t\t// Validate list of " . $attribute->getTextNamePlural();
				$validation .= "\n        if( sizeof(\$this->" . $attribute->getVariableNamePlural() . ") == 0 ) {
			\$this->addError('" . $attribute->getErrorName() . "_LIST_EMPTY');
		}\n";

			} else if( $attribute->isOptional() && sizeof($attribute->getType()->validationChecks()) > 0 ) {

				$optValidation = "";

				foreach( $attribute->getType()->validationChecks() as $key => $value ) {
					$optValidation .= " else if( !is_null(\$this->" . $attribute->getVariableName() . ") && " . sprintf( $value, $attribute->getVariableName() ) . " ) {
			\$this->addError('" . $attribute->getErrorName() . "_" . $key . "');
		}";
				}

				$validation .= "\n\t\t// Validate " . $attribute->getTextName() . "\n\t\t" . substr( $optValidation, 6 ) . "\n";

			} else if( !$attribute->isOptional() ) {

				$validation .= "\n\t\t// Validate " . $attribute->getTextName();
				$validation .= "\n        if( " . sprintf( $attribute->getType()->existenceCheck(), $attribute->getVariableName() ) . " ) {
			\$this->addError('" . $attribute->getErrorName() . "_MISSING');
		}";

				if( sizeof($attribute->getType()->validationChecks()) > 0 ) {
					foreach( $attribute->getType()->validationChecks() as $key => $value ) {
						$validation .= " else if( " . sprintf( $value, $attribute->getVariableName() ) . " ) {
			\$this->addError('" . $attribute->getErrorName() . "_" . $key . "');
		}";
					}
				}

				$validation .= "\n";

			}

		}

		$validation .= "
		return !\$this->hasErrors();

	}";

		return $validation;

	}

	private function fromDatabaseResultMethod(): string {

		$model = '$' . $this->getSingleName();

		$method = "\t/**
	 * {@inheritDoc}
	 */
	public static function fromDatabaseResult( array \$result ): " . ( $this->readOnly ? 'Simple' : '' ) . "Model {

		";

		if( $this->readOnly ) {
			$method .= "$model = new self( \$result['id'] );\n";
		} else {
			$method .= "$model = new self( \$result['id'], \DateTime::createFromFormat( 'Y-m-d H:i:s', \$result['created_at'] ), \DateTime::createFromFormat( 'Y-m-d H:i:s', \$result['updated_at'] ) );\n";
		}

		foreach( $this->attributes as $attribute ) {

			if( $attribute->isCollection() ) {
				continue;
			}

			$method .= "\t\t" . $model . "->set" . $attribute->getMethodName() . "( " . str_replace( '%s', $attribute->getArrayName(), $attribute->getType()->deserialization() ) . " );\n";

		}

		$method .= "
		return $model;

	}";

		return $method;

	}

	private function jsonSerializeMethod(): string {

		$method = "\t/**
	 * {@inheritDoc}
	 */
	public function jsonSerialize(): array {

		return array_merge(parent::jsonSerialize(), [\n";

		foreach( $this->attributes as $attribute ) {

			if( $attribute->isCollection() || $attribute->getType() instanceof PasswordType || $attribute->getType() instanceof EncryptedStringType ) {
				continue;
			}

			$method .= "\t\t\t'" . $attribute->getArrayName() . "' => " . str_replace( '%s', $attribute->getMethodName(), $attribute->getType()->serialization() ) . ",\n";

		}

		$method .= "		]);

	}";

		return $method;

	}

	private function databaseManipulationMethods(): string {

		$table = $this->getTableName();
		$bindings = '';
		$insertVars = [];
		$updateVars = [];

		foreach( $this->attributes as $attribute ) {

			if( $attribute->isCollection() ) {
				continue;
			}

			$name = $attribute->getArrayName();
			if( $attribute->getType() instanceof PasswordType ) {
				$name .= '_hash';
			}

			$insertVars[] = $name;
			$updateVars[] = $name . '` = :' . $name;
			$bindings .= "		\$statement->bindValue( ':" . $name . "', " . str_replace( '%s', $attribute->getMethodName(), $attribute->getType()->serialization() ) . ", \PDO::" . $attribute->getType()->pdoParamType() . " );\n";

		}

		$method = "\t/**
	 * {@inheritDoc}
	 */
	public static function findById( \PDO \$db, int \$id ): ?" . ( $this->readOnly ? 'Simple' : '' ) . "Model {

		\$statement = \$db->prepare( \"SELECT `id`, `" . implode( '`, `', $insertVars ) . "`" . ( $this->readOnly ? '' : ", `created_at`, `updated_at`" ) . ( sizeof($insertVars) > 3 ? "\n\t\t\t" : " " ) . "FROM `$table` WHERE `id` = :id\" );
		\$statement->bindValue( ':id', \$id, \PDO::PARAM_INT );
		\$statement->execute();
		\$result = \$statement->fetch();

		if( \$result === false ) {
			// Resource not found
			return null;
		}

		return self::fromDatabaseResult( \$result );

	}

	/**
	 * {@inheritDoc}
	 */
	public static function findAll( \PDO \$db ): array {

		\$results = [];

		\$statement = \$db->prepare( \"SELECT `id`, `" . implode( '`, `', $insertVars ) . "`" . ( $this->readOnly ? '' : ", `created_at`, `updated_at`" ) . ( sizeof($insertVars) > 3 ? "\n\t\t\t" : " " ) . "FROM `$table`\" );
		\$statement->execute();

		while( \$result = \$statement->fetch() ) {
			\$results[] = self::fromDatabaseResult( \$result );
		}

		return \$results;

	}

	/**
	 * {@inheritDoc}
	 */
	public static function existsWithId( \PDO \$db, int \$id ): bool {

		\$statement = \$db->prepare( \"SELECT 1 FROM `$table` WHERE `id` = :id LIMIT 1\" );
		\$statement->bindValue( ':id', \$id, \PDO::PARAM_INT );
		\$statement->execute();

		return \$statement->fetch() !== false;

	}";

		if( !$this->readOnly ) {

			$method .= "

	/**
	 * {@inheritDoc}
	 */
	protected function insertInto( \PDO \$db ): int {

		\$statement = \$db->prepare( \"INSERT INTO `$table` (`" . implode( '`, `', $insertVars ) . "`, `created_at`, `updated_at`)" . (sizeof( $insertVars ) > 3 ? "\n\t\t\t" : " ") . "VALUES (:" . implode( ', :', $insertVars ) . ", :created_at, :updated_at)\" );
$bindings		\$statement->bindValue( ':created_at', \$this->getCreatedAt()->format( \DateTime::ATOM ), \PDO::PARAM_STR );
		\$statement->bindValue( ':updated_at', \$this->getUpdatedAt()->format( \DateTime::ATOM ), \PDO::PARAM_STR );
		\$statement->execute();

		// Return id of newly created resource
		return \$db->lastInsertId();

	}

	/**
	 * {@inheritDoc}
	 */
	protected function update( \PDO \$db ): bool {

		\$statement = \$db->prepare( \"UPDATE `$table`
			SET `" . implode( ",\n\t\t\t    `", $updateVars ) . ",
			    `updated_at` = :updated_at
			WHERE `id` = :id\" );
		\$statement->bindValue( ':id', \$this->getId(), \PDO::PARAM_INT );
$bindings		\$statement->bindValue( ':updated_at', \$this->getUpdatedAt()->format( \DateTime::ATOM ), \PDO::PARAM_STR );
		\$statement->execute();

		return \$statement->rowCount() === 1;

	}

	/**
	 * {@inheritDoc}
	 */
	protected function deleteFrom( \PDO \$db ): bool {

		\$statement = \$db->prepare( \"DELETE FROM `$table` WHERE `id` = :id LIMIT 1\" );
		\$statement->bindValue( ':id', \$this->getId(), \PDO::PARAM_INT );
		\$statement->execute();

		return \$statement->rowCount() === 1;

	}";

		}

		return $method;

	}

}
