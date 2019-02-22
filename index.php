<?php

require __DIR__ . '/vendor/autoload.php';

$namespace = 'Memberlist';

/** @var Model[] $models */
$models = [];

// Define models

$model = new Model('academic_title', true );
$model->addAttribute( new Attribute( 'address_name', new StringType(50) ) );
$model->addAttribute( new Attribute( 'salutation_name', new StringType(50) ) );
$models[] = $model;

$model = new Model('accounting_period' );
$model->addAttribute( new Attribute( 'description', new StringType(200) ) );
$model->addAttribute( new Attribute( 'begin', new DateType() ) );
$model->addAttribute( new Attribute( 'end', new DateType(), true ) );
$models[] = $model;

$model = new Model('account' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member'), true ) );
$model->addAttribute( new Attribute( 'balance', new CurrencyType(), false, false, '0.00' ) );
$model->addAttribute( new Attribute( 'status', new EnumType('AccountStatus'), false, false,'new AccountStatus(\'open\')' ) );
$model->addAttribute( new Attribute( 'pin', new PasswordType(), true ) );
$models[] = $model;

$model = new Model('address' );
$model->addAttribute( new Attribute( 'additional_line_1', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'additional_line_2', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'street', new StringType(100) ) );
$model->addAttribute( new Attribute( 'zipCode', new StringType(50) ) );
$model->addAttribute( new Attribute( 'city', new StringType(100) ) );
$model->addAttribute( new Attribute( 'country', new ObjectType('Country') ) );
$model->addAttribute( new Attribute( 'latitude', new FloatType(), true ) );
$model->addAttribute( new Attribute( 'longitude', new FloatType(), true ) );
$models[] = $model;

$model = new Model('association' );
$model->addAttribute( new Attribute( 'name', new StringType(200) ) );
$model->addAttribute( new Attribute( 'abbreviation', new StringType(10), true ) );
$models[] = $model;

$model = new Model('association_charge', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100), true ) );
$models[] = $model;

$model = new Model('association_charge_occupation' );
$model->addAttribute( new Attribute( 'charge', new ObjectType('AssociationCharge') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'begin', new YearType() ) );
$model->addAttribute( new Attribute( 'end', new YearType(), true ) );
$models[] = $model;

$model = new Model('beverage' );
$model->addAttribute( new Attribute( 'description', new StringType(200) ) );
$model->addAttribute( new Attribute( 'purchase_price', new PositiveCurrencyType() ) );
$model->addAttribute( new Attribute( 'sales_price', new PositiveCurrencyType() ) );
$models[] = $model;

$model = new Model('committee', true );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$models[] = $model;

$model = new Model('committee_occupation' );
$model->addAttribute( new Attribute( 'committee', new ObjectType('Committee') ) );
$model->addAttribute( new Attribute( 'position', new ObjectType('CommitteePosition') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'begin', new YearType() ) );
$model->addAttribute( new Attribute( 'end', new YearType(), true ) );
$models[] = $model;

$model = new Model('committee_position', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100), true ) );
$models[] = $model;

$model = new Model('consumption' );
$model->addAttribute( new Attribute( 'account', new ObjectType('Account') ) );
$model->addAttribute( new Attribute( 'beverage', new ObjectType('Beverage') ) );
$model->addAttribute( new Attribute( 'amount', new UnsignedIntegerType() ) );
$model->addAttribute( new Attribute( 'sales_price', new PositiveCurrencyType() ) );
$model->addAttribute( new Attribute( 'billing_transaction', new ObjectType('Transaction'), true ) );
$model->addAttribute( new Attribute( 'consumed_at', new DateType() ) );
$models[] = $model;

$model = new Model('cotery' );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'receives_mailings', new BooleanType(), false, false, 'false' ) );
$model->addAttribute( new Attribute( 'address', new ObjectType('Address'), true ) );
$model->addAttribute( new Attribute( 'email_address', new ObjectType('EmailAddress'), true ) );
$models[] = $model;

$model = new Model('country', true );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'dialing_code', new StringType(5) ) );
$models[] = $model;

$model = new Model('denomination', true );
$model->addAttribute( new Attribute( 'name', new StringType(50) ) );
$models[] = $model;

$model = new Model('distinction', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$models[] = $model;

$model = new Model('distinction_awarding' );
$model->addAttribute( new Attribute( 'distinction', new ObjectType('Distinction') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'date_of_awarding', new DateType(), true ) );
$models[] = $model;

$model = new Model('email_address' );
$model->addAttribute( new Attribute( 'email_address', new EmailAddressType() ) );
$models[] = $model;

$model = new Model('event' );
$model->addAttribute( new Attribute( 'title', new StringType(200) ) );
$model->addAttribute( new Attribute( 'description', new StringType(), true ) );
$model->addAttribute( new Attribute( 'period', new ObjectType('Period') ) );
$model->addAttribute( new Attribute( 'information_url', new UriType(), true ) );
$model->addAttribute( new Attribute( 'report_url', new UriType(), true ) );
$model->addAttribute( new Attribute( 'begin', new DateTimeType() ) );
$model->addAttribute( new Attribute( 'end', new DateTimeType() ) );
$model->addAttribute( new Attribute( 'location', new ObjectType('EventLocation') ) );
$model->addAttribute( new Attribute( 'type', new ObjectType('EventType') ) );
$model->addAttribute( new Attribute( 'status', new EnumType('EventStatus'), false, false, 'new EventStatus(\'public\')' ) );
$models[] = $model;

$model = new Model('event_location' );
$model->addAttribute( new Attribute( 'name', new StringType(200) ) );
$model->addAttribute( new Attribute( 'address', new ObjectType('Address'), true ) );
$models[] = $model;

$model = new Model('event_reply' );
$model->addAttribute( new Attribute( 'event', new ObjectType('Event') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'via', new EnumType('EventReplyVia') ) );
$model->addAttribute( new Attribute( 'response', new EnumType('EventReplyResponse') ) );
$model->addAttribute( new Attribute( 'additional_guests', new UnsignedIntegerType(), false, false, '0' ) );
$models[] = $model;

$model = new Model('event_type', true );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'abbreviation', new StringType(10) ) );
$models[] = $model;

$model = new Model('expiration' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User'), true ) );
$model->addAttribute( new Attribute( 'description', new StringType(200) ) );
$model->addAttribute( new Attribute( 'expires_at', new DateType() ) );
$models[] = $model;

$model = new Model('inventory' );
$model->addAttribute( new Attribute( 'description', new StringType(200) ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'conducted_at', new DateType() ) );
$models[] = $model;

$model = new Model('inventory_record' );
$model->addAttribute( new Attribute( 'inventory', new ObjectType('Inventory') ) );
$model->addAttribute( new Attribute( 'beverage', new ObjectType('Beverage') ) );
$model->addAttribute( new Attribute( 'amount', new UnsignedIntegerType() ) );
$model->addAttribute( new Attribute( 'purchase_price', new PositiveCurrencyType() ) );
$models[] = $model;

$model = new Model('list' );
$model->addAttribute( new Attribute( 'title', new StringType(200) ) );
$model->addAttribute( new Attribute( 'description', new StringType(), true ) );
$model->addAttribute( new Attribute( 'deadline_at', new DateTimeType(), true ) );
$models[] = $model;

$model = new Model('list_enrollment' );
$model->addAttribute( new Attribute( 'list', new ObjectType('List') ) );
$model->addAttribute( new Attribute( 'option', new ObjectType('ListOption') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'enrolled_at', new DateTimeType() ) );
$models[] = $model;

$model = new Model('list_option' );
$model->addAttribute( new Attribute( 'list', new ObjectType('List') ) );
$model->addAttribute( new Attribute( 'title', new StringType(200) ) );
$model->addAttribute( new Attribute( 'enrollments_min', new UnsignedIntegerType() ) );
$model->addAttribute( new Attribute( 'enrollments_max', new UnsignedIntegerType(), true ) );
$models[] = $model;

$model = new Model('login' );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'ip_address', new IpAddressType(), true ) );
$model->addAttribute( new Attribute( 'city', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'country', new ObjectType('Country'), true ) );
$model->addAttribute( new Attribute( 'browser', new StringType(200), true ) );
$model->addAttribute( new Attribute( 'operating_system', new StringType(200), true ) );
$model->addAttribute( new Attribute( 'successful', new BooleanType(), false, false, 'false' ) );
$models[] = $model;

$model = new Model('member' );
$model->addAttribute( new Attribute( 'first_name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'middle_name', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'last_name_prefix', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'last_name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'birth_name', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'religious_name', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'vulgo_name', new StringType(100), true ) );
$model->addAttribute( new Attribute( 'gender', new EnumType('Gender') ) );
$model->addAttribute( new Attribute( 'date_of_birth', new DateType(), true ) );
$model->addAttribute( new Attribute( 'location_of_birth', new StringType(200), true ) );
$model->addAttribute( new Attribute( 'date_of_studies', new DateType(), true ) );
$model->addAttribute( new Attribute( 'date_of_ordination', new DateType(), true ) );
$model->addAttribute( new Attribute( 'date_of_death', new DateType(), true ) );
$model->addAttribute( new Attribute( 'profession', new StringType(200), true ) );
$model->addAttribute( new Attribute( 'denomination', new ObjectType('Denomination'), true ) );
$model->addAttribute( new Attribute( 'cotery', new ObjectType('Cotery'), true ) );
$model->addAttribute( new Attribute( 'type', new ObjectType('MemberType'), true ) );
$model->addAttribute( new Attribute( 'status', new ObjectType('MemberStatus'), true ) );
$model->addAttribute( new Attribute( 'receives_mailings', new BooleanType(), false, false, 'false' ) );
$model->addAttribute( new Attribute( 'receives_calls', new BooleanType(), false, false, 'false' ) );
$model->addAttribute( new Attribute( 'receives_emails', new BooleanType(), false, false, 'false' ) );
$models[] = $model;

$model = new Model('membership' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'society', new ObjectType('Society') ) );
$model->addAttribute( new Attribute( 'type', new ObjectType('MembershipType') ) );
$model->addAttribute( new Attribute( 'priority', new UnsignedIntegerType() ) );
$model->addAttribute( new Attribute( 'admitted_at', new DateType() ) );
$models[] = $model;

$model = new Model('membership_type', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$models[] = $model;

// TODO: members xrefs: EnumType('AddressType'), EnumType('EmailAddressType'), EnumType('PhoneNumberType')

$model = new Model('member_status', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$models[] = $model;

$model = new Model('member_type', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$models[] = $model;

$model = new Model('note' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'text', new StringType() ) );
$models[] = $model;

$model = new Model('online_banking_access' );
$model->addAttribute( new Attribute( 'account', new ObjectType('Account') ) );
$model->addAttribute( new Attribute( 'hbci_url', new UriType() ) );
$model->addAttribute( new Attribute( 'hbci_port', new UnsignedIntegerType() ) );
$model->addAttribute( new Attribute( 'bic', new BicType() ) );
$model->addAttribute( new Attribute( 'username', new StringType(255) ) );
$model->addAttribute( new Attribute( 'customer_id', new StringType(255), true ) );
$model->addAttribute( new Attribute( 'pin', new EncryptedStringType(), true ) );
$models[] = $model;

$model = new Model('period', true );
$model->addAttribute( new Attribute( 'description', new StringType(200) ) );
$model->addAttribute( new Attribute( 'begin', new DateType() ) );
$model->addAttribute( new Attribute( 'end', new DateType(), true ) );
$models[] = $model;

$model = new Model('pgp_public_key' );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'fingerprint', new CharType(40) ) );
$model->addAttribute( new Attribute( 'key', new StringType() ) );
$model->addAttribute( new Attribute( 'key_created_at', new DateType() ) );
$model->addAttribute( new Attribute( 'expires_at', new DateType(), true ) );
$model->addAttribute( new Attribute( 'is_revoked', new BooleanType(), false, false, 'false' ) );
$model->addAttribute( new Attribute( 'is_visible', new BooleanType(), false, false, 'false' ) );
$models[] = $model;

$model = new Model('phone_number' );
$model->addAttribute( new Attribute( 'country', new ObjectType('Country') ) );
$model->addAttribute( new Attribute( 'dialing_code', new StringType(10) ) );
$model->addAttribute( new Attribute( 'number', new StringType(20) ) );
$models[] = $model;

$model = new Model('photo' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'url', new UriType() ) );
$models[] = $model;

// TODO: relationships

$model = new Model('relationship_type', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$model->addAttribute( new Attribute( 'transitive_counterpart', new ObjectType('RelationshipType'), true ) );
$models[] = $model;

$model = new Model('retention_call' );
$model->addAttribute( new Attribute( 'title', new StringType(200) ) );
$model->addAttribute( new Attribute( 'description', new StringType(), true ) );
$model->addAttribute( new Attribute( 'deadline_at', new DateTimeType(), true ) );
$models[] = $model;

/*$model = new Model('retention_call_assignment' );
$model->addAttribute( new Attribute( 'retention_call', new ObjectType('RetentionCall') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$models[] = $model;

$model = new Model('retention_call_contact' );
$model->addAttribute( new Attribute( 'retention_call', new ObjectType('RetentionCall') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'user', new ObjectType('User') ) );
$model->addAttribute( new Attribute( 'successful', new BooleanType() ) );
$model->addAttribute( new Attribute( 'contacted_at', new DateTimeType() ) );
$models[] = $model;*/

$model = new Model('sepa_mandate' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'name', new MandateNameType() ) );
$model->addAttribute( new Attribute( 'iban', new IbanType() ) ); // TODO: Encrypted IBAN
$model->addAttribute( new Attribute( 'bic', new BicType() ) ); // TODO: Encrypted BIC
$model->addAttribute( new Attribute( 'mandate_reference', new MandateReferenceType() ) );
$model->addAttribute( new Attribute( 'signed_at', new DateType() ) );
$models[] = $model;

$model = new Model('social_media_platform', true );
$model->addAttribute( new Attribute( 'name', new StringType(200) ) );
$model->addAttribute( new Attribute( 'profile_url', new UriType() ) ); // TODO: Uri length?
$models[] = $model;

$model = new Model('social_media_profile' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'platform', new ObjectType('SocialMediaPlatform') ) );
$model->addAttribute( new Attribute( 'profile_name', new StringType(100) ) );
$models[] = $model;

$model = new Model('society' );
$model->addAttribute( new Attribute( 'association', new ObjectType('Association'), true ) );
$model->addAttribute( new Attribute( 'name', new StringType(200) ) );
$model->addAttribute( new Attribute( 'short_name', new StringType(100) ) );
$model->addAttribute( new Attribute( 'abbreviation', new StringType(10), true ) );
$model->addAttribute( new Attribute( 'founded_at', new DateType(), true ) );
$model->addAttribute( new Attribute( 'active_status', new ObjectType('SocietyStatus'), true ) );
$model->addAttribute( new Attribute( 'alumni_status', new ObjectType('SocietyStatus'), true ) );
$model->addAttribute( new Attribute( 'receives_mailings', new BooleanType(), false, false, 'false' ) );
$model->addAttribute( new Attribute( 'address', new ObjectType('Address'), true ) );
$model->addAttribute( new Attribute( 'email_address', new ObjectType('EmailAddress'), true ) );
$model->addAttribute( new Attribute( 'phone_number', new ObjectType('PhoneNumber'), true ) );
$model->addAttribute( new Attribute( 'salutation', new ObjectType('SocietySalutation') ) );
$models[] = $model;

$model = new Model('society_charge', true );
$model->addAttribute( new Attribute( 'name_female', new StringType(100) ) );
$model->addAttribute( new Attribute( 'name_male', new StringType(100) ) );
$model->addAttribute( new Attribute( 'abbreviation', new StringType(10) ) );
$models[] = $model;

$model = new Model('society_charge_occupation' );
$model->addAttribute( new Attribute( 'charge', new ObjectType('SocietyCharge') ) );
$model->addAttribute( new Attribute( 'society', new ObjectType('Society') ) );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'period', new ObjectType('Period') ) );
$models[] = $model;

$model = new Model('society_salutation', true );
$model->addAttribute( new Attribute( 'salutation', new StringType(200) ) );
$models[] = $model;

$model = new Model('society_status', true );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$models[] = $model;

$model = new Model('student_grade', true );
$model->addAttribute( new Attribute( 'name', new StringType(50) ) );
$models[] = $model;

$model = new Model('transaction' );
$model->addAttribute( new Attribute( 'reference', new StringType(200) ) );
$model->addAttribute( new Attribute( 'accounting_period', new ObjectType('AccountingPeriod') ) );
$model->addAttribute( new Attribute( 'date_of_valuta', new DateType() ) );
$model->addAttribute( new Attribute( 'record', new ObjectType('TransactionRecord'), false, true ) );
$models[] = $model;

/*$model = new Model('transaction_record' );
$model->addAttribute( new Attribute( 'transaction', new ObjectType('Transaction') ) );
$model->addAttribute( new Attribute( 'account', new ObjectType('Account') ) );
$model->addAttribute( new Attribute( 'amount', new CurrencyType() ) );
$models[] = $model;*/

$model = new Model('user' );
$model->addAttribute( new Attribute( 'username', new StringType(100) ) );
$model->addAttribute( new Attribute( 'password', new PasswordType() ) );
$model->addAttribute( new Attribute( 'calendar_secret', new CharType(32) ) );
$model->addAttribute( new Attribute( 'totp_secret', new CharType(16) ) );
$models[] = $model;

$model = new Model('withdrawal' );
$model->addAttribute( new Attribute( 'member', new ObjectType('Member') ) );
$model->addAttribute( new Attribute( 'society', new ObjectType('Society') ) );
$model->addAttribute( new Attribute( 'type', new ObjectType('WithdrawalType') ) );
$model->addAttribute( new Attribute( 'withdrawn_at', new DateType() ) );
$models[] = $model;

$model = new Model('withdrawal_type', true );
$model->addAttribute( new Attribute( 'name', new StringType(100) ) );
$models[] = $model;

##################################################################

$routesFile = fopen( 'output/routes.php', "w" );

fputs( $routesFile, "<?php\n" );

foreach( $models as $model ) {

	$classFile = fopen( 'output/' . $model->getClassName() . '.php', "w" );
	$controllerFile = fopen( 'output/' . $model->getClassName() . 'Controller.php', "w" );

	fputs( $classFile, $model->createClass($namespace) );
	fputs( $controllerFile, $model->createController($namespace) );

	fputs( $routesFile, "\n/**
 * " . $model->getTextNameTitle() . "
 */
\$app->get( '/" . $model->getTableName() . "', \Memberlist\\" . $model->getClassName() . "Controller::class . ':getAll' );
\$app->get( '/" . $model->getTableName() . "/{id}', \Memberlist\\" . $model->getClassName() . "Controller::class . ':get' );\n" );

	if( !$model->isReadOnly() ) {
		fputs( $routesFile, "\$app->post( '/" . $model->getTableName() . "', \Memberlist\\" . $model->getClassName() . "Controller::class . ':post' );
\$app->put( '/" . $model->getTableName() . "/{id}', \Memberlist\\" . $model->getClassName() . "Controller::class . ':put' );
\$app->delete( '/" . $model->getTableName() . "/{id}', \Memberlist\\" . $model->getClassName() . "Controller::class . ':delete' );\n" );
	}

	fclose($classFile);
	fclose($controllerFile);

}

fclose($routesFile);
