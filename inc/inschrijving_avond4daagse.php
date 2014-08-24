<?php

class AKVS_A4DInschrijving
{

	private function validateInput()
	{
		$hasError = false;
		if ( $_POST[ "email_contact" ] == "" )
		{
			echo '<br/>Geen email adres ingevuld';
			$hasError = true;
		}
		for ( $nummer = 1; $nummer <= 4; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_herh = sprintf( "herh_%02d", $nummer );
			
			if ( $_POST[ $label_naam ] != "" || $_POST[ $label_jaar ] != "" || $_POST[ $label_herh ] != "" )
			{
				if ( $_POST[ $label_naam ] == "" )
				{
					echo '<br/>Geen naam ingevuld bij nummer ' . $nummer;
					$hasError = true;
				}
				if ( $_POST[ $label_jaar ] == "" )
				{
					echo '<br/>Geen geboortejaar ingevuld bij nummer ' . $nummer;
					$hasError = true;
				}
				if ( $_POST[ $label_herh ] == "" )
				{
					echo '<br/>Geen herinnering ingevuld bij nummer ' . $nummer;
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
			<td class="form-label">E-mail:</td>
			<td><input type="text" size="40" name="email_contact"
				<?php if ($fillValues) $this->showValue('email_contact'); ?>></td>
		</tr>
	</table>
	<table class="inschrijfformulier">
		<thead>
			<tr>
				<th></th>
				<th>Naam</th>
				<th>Geb. Jaar</th>
				<th>Herhaling</th>
			</tr>
		</thead>
		<tbody>
			<?php
		for ( $nummer = 1; $nummer <= 4; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_herh = sprintf( "herh_%02d", $nummer );
			?>
			<tr>
				<td class="index"><?php echo $nummer; ?>.</td>
				<td><input type="text" name="<?php echo $label_naam; ?>" size="40"
					<?php if ($fillValues) $this->showValue($label_naam); ?>></td>
				<td><input type="text" name="<?php echo $label_jaar; ?>" size="4"
					<?php if ($fillValues) $this->showValue($label_jaar); ?>></td>
				<td><input type="text" name="<?php echo $label_herh; ?>" size="4"
					<?php if ($fillValues) $this->showValue($label_herh); ?>></td>
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
		$mail_to = "avond4daagse@akvsoesterkwartier.nl";
		$mail_cc = $_POST[ 'email_contact' ];
		$mail_from = "avond4daagse@akvsoesterkwartier.nl";
		$mail_subject = get_the_title();
		$mail_headers = "From: $mail_from\r\n" . "Cc: $mail_cc\r\n";
		$mail_body = "De volgende inschrijving voor de avond4daagse is ontvangen:\r\n" . "\r\n";
		
		$teller = 1;
		for ( $nummer = 1; $nummer <= 4; $nummer++ )
		{
			$label_naam = sprintf( "naam_%02d", $nummer );
			$label_jaar = sprintf( "jaar_%02d", $nummer );
			$label_herh = sprintf( "herh_%02d", $nummer );
			
			if ( $_POST[ $label_naam ] != "" )
			{
				$mail_body = sprintf( "%s%2d. %-40s%5d%5d\r\n", $mail_body, $teller, $_POST[ $label_naam ], $_POST[ $label_jaar ], $_POST[ $label_herh ] );
				$teller++;
			}
		}
		
		$mail_body = $mail_body . "\r\n" . "Hartelijk dank voor deze inschrijving. De kosten van 5 euro per persoon\r\n" . "kunnen bij Kees voldaan worden.\r\n" . "\r\n" . "AKV Soesterkwartier\r\n";
		
		if ( mail( $mail_to, $mail_subject, $mail_body, $mail_headers ) )
		{
			echo ( 'De inschrijving is verstuurd. U ontvangt een kopie van de inschrijving op het opgegeven e-mail adres.' );
			echo ( 'Klik <a href="' . $formURL . '">hier</a> om nog meer mensen op te geven.' );
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