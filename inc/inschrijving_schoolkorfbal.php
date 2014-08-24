<?php

class AKVS_SchoolkorfbalInschrijving
{

	private function validateInput()
	{
		$hasError = false;
		if ( $_POST[ 'naam_school' ] == '' )
		{
			echo '<br/>Er is geen naam van de school ingevuld';
			$hasError = true;
		}
		if ( $_POST[ 'naam_contact' ] == '' )
		{
			echo '<br/>Er is geen contactpersoon van de school ingevuld';
			$hasError = true;
		}
		if ( $_POST[ 'email_contact' ] == '' )
		{
			echo '<br/>Er is geen e-mail adres van de contactpersoon ingevuld';
			$hasError = true;
		}
		if ( $_POST[ 'groep' ] == '' )
		{
			echo '<br/>Er is geen groep geselecteerd';
			$hasError = true;
		}
		for ( $nummer = 1; $nummer <= 12; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_sexe = sprintf( "sexe_%02d", $nummer );
			
			if ( $_POST[ $label_naam ] != "" || $_POST[ $label_jaar ] != "" || $_POST[ $label_sexe ] != "" )
			{
				if ( $_POST[ $label_naam ] == "" )
				{
					echo '<br/>Geen naam ingevult bij nummer ' . $nummer;
					$hasError = true;
				}
				if ( $_POST[ $label_jaar ] == "" )
				{
					echo '<br/>Geen leeftijd ingevult bij nummer ' . $nummer;
					$hasError = true;
				}
				if ( $_POST[ $label_sexe ] == "" )
				{
					echo '<br/>Geen jongen of meisje geselecteerd bij nummer ' . $nummer;
					$hasError = true;
				}
			}
		}
		return $hasError;
	}

	private function getPostValue( $id )
	{
		return htmlspecialchars( $_POST[ $id ] );
	}

	private function showValue( $id )
	{
		if ( $_POST[ $id ] != "" )
		{
			echo ' value="' . $this->getPostValue( $id ) . '"';
		}
	}

	private function showRadioValue( $id, $value )
	{
		if ( $_POST[ $id ] == $value )
		{
			echo ' checked';
		}
	}

	private function showForm( $formURL, $fillValues )
	{
		?>
<form action="<?php echo $formURL;?>" method="POST">
	<table class="inschrijfformulier">
		<tr>
			<td class="form-label">School:</td>
			<td><input type="text" size="40" name="naam_school"
				<?php if ($fillValues) $this->showValue('naam_school'); ?>></td>
		</tr>
		<tr>
			<td class="form-label">Contactpersoon:</td>
			<td><input type="text" size="40" name="naam_contact"
				<?php if ($fillValues) $this->showValue('naam_contact'); ?>></td>
		</tr>
		<tr>
			<td class="form-label">E-mail:</td>
			<td><input type="text" size="40" name="email_contact"
				<?php if ($fillValues) $this->showValue('email_contact'); ?>></td>
		</tr>
		<tr>
			<td class="form-label">Groep:</td>
			<td>
<?php
		for ( $groep = 1; $groep <= 7; $groep += 2 )
		{
			?>
					<input type="radio" name="groep" value="<?php echo $groep; ?>"
				<?php if ($fillValues) $this->showRadioValue('groep', $groep); ?>>&nbsp;<?php echo $groep . '/' . ($groep + 1); ?>&nbsp;
<?php	} ?>
				</td>
		</tr>
		<tr>
			<td class="form-label">Begeleider:</td>
			<td><input type="text" size="40" name="naam_begeleider"
				<?php if ($fillValues) $this->showValue('naam_begeleider'); ?>></td>
		</tr>
	</table>

	<table class="inschrijfformulier">
		<thead>
			<tr>
				<th></th>
				<th>Naam</th>
				<th>Geb. Jaar</th>
				<th>J / M</th>
			</tr>
		</thead>
		<tbody>
<?php
		for ( $nummer = 1; $nummer <= 12; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_sexe = sprintf( "sexe_%02d", $nummer );
			?>
				<tr>
				<td class="index"><?php echo $nummer; ?>.</td>
				<td><input type="text" name="<?php echo $label_naam; ?>" size="40"
					<?php if ($fillValues) $this->showValue($label_naam); ?>></td>
				<td><input type="text" name="<?php echo $label_jaar; ?>" size="4"
					<?php if ($fillValues) $this->showValue($label_jaar); ?>></td>
				<td><input type="radio" name="<?php echo $label_sexe; ?>" value="J"
					<?php if ($fillValues) $this->showRadioValue($label_sexe, 'J'); ?>>&nbsp;J&nbsp;
					<input type="radio" name="<?php echo $label_sexe; ?>" value="M"
					<?php if ($fillValues) $this->showRadioValue($label_sexe, 'M'); ?>>&nbsp;M&nbsp;
				</td>
			</tr>
<?php	} ?>
			</tbody>
	</table>

	<input type="submit" name="submit" value="Verzenden"> <input
		type="reset" value="Wissen">
</form>
<?php
	}

	private function sendEmail( $formURL )
	{
		$mail_to = "schoolkorfbal@akvsoesterkwartier.nl";
		$mail_cc = $_POST[ 'email_contact' ];
		$mail_from = "schoolkorfbal@akvsoesterkwartier.nl";
		$mail_subject = get_the_title();
		$mail_headers = "From: $mail_from\r\n" . "Cc: $mail_cc\r\n";
		$mail_body = "De volgende inschrijving voor het Schoolkorfbaltoernooi\r\n" . "is ontvangen:\r\n" . "\r\n" . "School:         " . $_POST[ 'naam_school' ] . "\r\n" . "Contactpersoon: " . $_POST[ 'naam_contact' ] . "\r\n" . "E-mail adres:   " . $_POST[ 'email_contact' ] . "\r\n" . "Groep:          " . $_POST[ 'groep' ] . "\r\n" . "Begeleider:     " . $_POST[ 'naam_begeleider' ] . "\r\n" . "\r\n";
		
		$teller = 1;
		for ( $nummer = 1; $nummer <= 12; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_sexe = sprintf( "sexe_%02d", $nummer );
			
			if ( $_POST[ $label_naam ] != "" )
			{
				$mail_body = sprintf( "%s%2d. %-40s%5d%5s\r\n", $mail_body, $teller, $_POST[ $label_naam ], $_POST[ $label_jaar ], $_POST[ $label_sexe ] );
				$teller++;
			}
		}
		
		$mail_body = $mail_body . "\r\n" . "Hartelijk dank voor deze inschrijving. Er zal zo snel\r\n" . "mogelijk contact worden opgenomen wanneer de data voor\r\n" . "de trainingen bekend zijn. Dit is alleen van toepassing\r\n" . "voor groep 3 tot en met 8. Voor groep 1 en 2 zijn er\r\n" . "geen trainingen.\r\n" . "\r\n" . "AKV Soesterkwartier\r\n";
		
		if ( mail( $mail_to, $mail_subject, $mail_body, $mail_headers ) )
		{
			echo ( 'De inschrijving is verstuurd. U ontvangt een kopie van de inschrijving op het opgegeven e-mail adres.' );
			echo ( 'Klik <a href="' . $formURL . '">hier</a> om nog een ploeg op te geven.' );
		}
		else
		{
			echo ( 'De inschrijving is <strong>NIET</strong> verstuurd. Controleer alle gegevens en probeer het dan nogmaals.' );
			$this->showForm( true );
		}
	}

	public function handleInschrijving( $formURL )
	{
		if ( $_POST[ 'submit' ] == 'Verzenden' )
		{
			$hasErrors = $this->validateInput();
			if ( $hasErrors )
			{
				$this->showForm( $formURL, true );
			}
			else
			{
				$this->sendEmail( $formURL );
			}
		}
		else
		{
			$this->showForm( $formURL, false );
		}
	}
}