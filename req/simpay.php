<?php //simpay.php
function simpay_install() {
	
	global $wpdb;
	
	/*
		* Tworzenie wpisów do ustawień WP
	*/
	
	add_option("simpay_key", "");
	add_option("simpay_secret", "");
	add_option("simpay-register-sms", "");
	add_option("simpay-register-numer", "");
	add_option("simpay-register-sekret", "");
	add_option("simpay-register-id", "");
	add_option("simpay-register-usluga", "");
	add_option("simpay-register-cena", "");
	add_option("simpay-hidden-sms", "");
	add_option("simpay-hidden-numer", "");
	add_option("simpay-hidden-sekret", "");
	add_option("simpay-hidden-id", "");
	add_option("simpay-hidden-usluga", "");
	add_option("simpay-hidden-cena", "");
	
	/*
		* Tworzenie tabeli w bazie danych
	*/
	
	$charset_collate = $wpdb->get_charset_collate();
	$simpay_table_name = $wpdb->prefix . 'simpay';
	$query = "CREATE TABLE " . $simpay_table_name . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        numer varchar(10) NOT NULL,
        usluga varchar(160) NOT NULL,
        prefix varchar(160) NOT NULL,
        id_uslugi varchar(160) NOT NULL,
        UNIQUE KEY id (id)
    ) " . $charset_collate . ";";
	$wpdb->query($query);
	
}

function simpay_uninstall() {
	
    global $wpdb;
	
	/*
		* Usuwanie wpisów z ustawień WP
	*/
	
	delete_option("simpay_key");
	delete_option("simpay_secret");
	delete_option("simpay-register-sms");
	delete_option("simpay-register-numer");
	delete_option("simpay-register-sekret");
	delete_option("simpay-register-id");
	delete_option("simpay-register-usluga");
	delete_option("simpay-register-cena");
	delete_option("simpay-hidden-sms");
	delete_option("simpay-hidden-numer");
	delete_option("simpay-hidden-sekret");
	delete_option("simpay-hidden-id");
	delete_option("simpay-hidden-usluga");
	delete_option("simpay-hidden-cena");
	
	/*
		* Usuwanie tabeli z bazy danych
	*/
	
    $prefix = $wpdb->prefix;
    $simpay_table_name = $prefix . "simpay";
    $query = 'DROP TABLE '. $simpay_table_name;
	$wpdb->query($query);
	
}

function simpay_menu_add() {
	add_menu_page("SimPay", "SimPay", "manage_options", "simpay-ustawienia", "simpay_admin_settings", plugins_url('simpay-wordpress/assets/img/sim.png'), null, 99);
}

function simpay_load_scripts() {
	//wp_enqueue_script('simpay', plugins_url('assets/js/simpay.js', dirname(__FILE__)), array('jquery'));
}

/*
	* Formularz płatności przy rejestracji
*/

function simpay_register_form() {
	global $simpay;
	
	$simpay_local = [];
	$simpay_local['content'] = get_option("simpay-register-sms");
	$simpay_local['number'] = get_option("simpay-register-numer");
	
	if (!empty(get_option("simpay-register-sms"))) {
		echo '<p>';
			echo '<label for="kod_sms">Wyślij SMS o treści ' . $simpay_local['content'] . ' pod numer ' . $simpay_local['number'] . ' koszt sms to ' . fnc_calculate_brutto($simpay->getSMSValue($simpay_local['number'])) . ' PLN (brutto)';
			echo '<br />';
			echo '<input type="text" name="kod_sms" id="kod_sms" class="input" size="25" required="" /></label>';
		echo '</p>';
	}
	
}

function simpay_register_validate($errors, $sanitized_user_login, $user_email) {
	global $simpay;
	
	try {

		if (empty($_POST['kod_sms']) || !empty($_POST['kod_sms']) && trim($_POST['kod_sms']) == '') {
			$errors->add('first_name_error', __('<strong>ERROR</strong>: Wprowadz kod SMS.', 'mydomain'));

			return $errors;
		}

		$codeSMS = trim($_POST['kod_sms']);

		if (strlen($codeSMS) == 0) {
			$errors->add('first_name_error', __('<strong>ERROR</strong>: Wprowadz kod SMS.', 'mydomain'));

			return $errors;
		}

		$simpay->getStatus([
			'service_id' => get_option("simpay-register-id") ,
			'number' => get_option("simpay-register-numer") ,
			'code' => $codeSMS
		]);

		if ($simpay->check()) {

			// kod poprawny

		} elseif ($simpay->error()) {

			// niepoprawny kod

			$errors->add('first_name_error', __('<strong>ERROR</strong>: Podany kod jest niepoprawny.', 'mydomain'));
		}
	}
	catch(Exception $e) {
		echo 'Error: ' . $e->getMessage();
	}

	return $errors;
}

/*
	* Blokada zawartości posta, który jest płatny.
*/

function simpay_content($content) {
	global $simpay;
	global $wpdb;
	
	/*
		* Wyświetlanie na stronie "głównej"
	*/
	
	if (is_front_page()) {
		$querystr = "SELECT * FROM " . $wpdb->prefix . "simpay";
		$uslugi = $wpdb->get_results($querystr, OBJECT);
		foreach($uslugi as & $value) {
			$content = preg_replace("%\[hidden_content_" . $value->id . "\](.*?)\[/hidden_content_" . $value->id . "\]%s", "<B>Płatność dostępna wewnątrz wpisu</B>", $content);
		}

		return $content;
	}

	if (!in_the_loop()) {
		return $content;
	}

	if (!is_singular()) {
		return $content;
	}

	if (!is_main_query()) {
		return $content;
	}
	
	if (strpos($content, '[hidden_content') > 0) {
		preg_match('/\[hidden_content_(?<id>\d+)\]/', $content, $matches);
		
		$query = "SELECT * FROM " . $wpdb->prefix . "simpay WHERE `id` = %s LIMIT 1;";
		if (!$service = $wpdb->get_row($wpdb->prepare($query, (int)$matches['id']), OBJECT)) {
			return;
		}
		
		$kod_poprawny = false;
		$alert = "";
		
		if (!empty($_POST["simpay_sprawdz"]) && !empty($_POST["simpay_kodsms"])) {
			
			try {
				
				$simpay->getStatus(array(
					'service_id' => $service->id_uslugi,
					'number' => $service->numer,
					'code' => $_POST['simpay_kodsms']
				));
				
				if ($simpay->check()) {
					$kod_poprawny = true;
				} elseif ($simpay->error()) {
					$alert = '<br /><center style="color:red"><b>Wystąpił problem podczas sprawdzania sms</b></center><br />';
				}
			}
			catch(Exception $e) {
				echo 'Error: ' . $e->getMessage();
			}
			
		}
		
		$forma = '
			<div class="col-md-12">' . $alert . '
				<form method="POST">
					<center>
						<hr>
						<h4>Wyślij SMS o treści <span class="label label-primary">' . $service->prefix . '.' . $service->usluga . '</span> na numer <span class="label label-primary">' . $service->numer . '</span> cena za sms wynosi <span class="label label-primary">' . fnc_calculate_brutto($simpay->getSMSValue($service->numer)) . '</span> (brutto) PLN</h4>
						<hr>
						<h5>Wpisz kod usługi:</h5>
						<div class="col-md-12">
							<input value="" placeholder="Kod usługi otrzymany SMSem..."  maxlength="100" cols="25" size="100" class="form-control" name="simpay_kodsms" required="" type="text"><br /><br />
							<input required type="checkbox"> Akceptuję <a href="https://simpay.pl/dokumenty/simpay_regulamin_uzytkownik_koncowy.pdf">regulamin usługi</a> SMS
						</div>
						<br />
						<button style="margin-top: 40px;" type="submit" value="1" name="simpay_sprawdz" class="btn btn-primary btn-lg">Zweryfikuj kod</button>
					</center>
				</form>
			</div>
		';
		
		if (!$kod_poprawny) {
			$content = preg_replace("%\[hidden_content_" . $service->id . "\](.*?)\[/hidden_content_" . $service->id . "\]%s", $forma, $content);
		} else {
			$content = str_replace('[hidden_content_' . $service->id . ']', "", $content);
			$content = str_replace('[/hidden_content_' . $service->id . ']', "", $content);
		}
		
	}

	remove_filter('the_content', 'se225721_the_content');
	
	return $content;
}

/*
	* Formularze i ustawienia w panelu admina
*/

function simpay_admin_settings() {
	global $wpdb;
	global $simpay;

	wp_enqueue_script('simpay', plugins_url('assets/js/simpay.js', dirname(__FILE__)), array('jquery'));

	if (!current_user_can('administrator')) {
		return;
	}

	//$api = new SimPay(get_option('simpay_key'), get_option('simpay_secret'));

	if (!empty($_POST['aktualizuj_key'])) {

		if (!check_admin_referer('simpay')) {
			return;
		}

		$apiKey = trim($_POST['api_key']);
		$apiSecret = trim($_POST['secret_key']);

		if (filter_var($apiKey, FILTER_SANITIZE_STRING) !== FALSE && filter_var($apiSecret, FILTER_SANITIZE_STRING) !== FALSE) {
			
			update_option("simpay_key", $apiKey);
			update_option("simpay_secret", $apiSecret);
			
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
		}
		
	}

	if (!empty($_POST['aktualizuj_2']) && isset($_POST['usluga2'])) {

		if (!check_admin_referer('simpay')) {
			return;
		}

		$serviceId = trim($_POST['usluga1']);
		$number = $_POST['prices1_' . $serviceId];
		
		if ($serviceId == 0) {
			return;
		}
		
		if ($number == 0) {
			return;
		}

		$services = $simpay->getServices();
		if ($services['respond']['status'] != "OK") {
			//Jakiś błąd.
		}
		$services = $services['respond']['services'];
		$service = $services[array_search($serviceId, array_column($services, 'id'))];
		
		if (is_null($service)) {
			return;
		}
		
		$number = $_POST['prices1_' . $service['id']];

		update_option("simpay-register-cena", $simpay->getSMSValue($number));
		update_option("simpay-register-numer", trim($number));
		update_option("simpay-register-sms", trim($service['prefix'] . '.' . $service['sufix']));
		update_option("simpay-register-id", trim($serviceId));
	}

	if (!empty($_POST['aktualizuj_3'])) {

		if (!check_admin_referer('simpay')) {
			return;
		}
		
		$serviceId = trim($_POST['usluga2']);
		$number = $_POST['prices2_' . $serviceId];
		
		if ($serviceId == 0) {
			return;
		}
		
		if ($number == 0) {
			return;
		}
		
		$services = $simpay->getServices();
		if ($services['respond']['status'] != "OK") {
			//Jakiś błąd.
		}
		$services = $services['respond']['services'];
		$service = $services[array_search($serviceId, array_column($services, 'id'))];
		
		if (is_null($service)) {
			return;
		}
		
		$number = $_POST['prices2_' . $service['id']];
		
		$wpdb->insert($wpdb->prefix . "simpay", array(
			'numer' => $number,
			'usluga' => $service['sufix'],
			'prefix' => $service['prefix'],
			'id_uslugi' => $service['id']
		));
	}

	$t_menu = '<ul class="subsubsub">
    <li><a href="' . wp_nonce_url( '?page=simpay-ustawienia' ) . '" class="current">Blokowanie treści</a> |</li>
    <li><a href="' . wp_nonce_url( '?page=simpay-ustawienia&register=1' ) . '">Blokowanie rejestracji</a></li>
</ul>';

	if (!empty($_GET["register"])) {
		$t_menu = '<ul class="subsubsub">
    <li><a href="' . wp_nonce_url( '?page=simpay-ustawienia' ) . '" >Blokowanie treści</a> |</li>
    <li><a href="' . wp_nonce_url( '?page=simpay-ustawienia&register=1' ) . '" class="current">Blokowanie rejestracji</a></li>
</ul>';
	}

	echo '
        <form style="margin-top: 30px;" method="post">
             <p><span style="font-size: 28px; font-weight: 600;">Klucz API:</span></p>
             <br />Tutaj możesz sprawdzić te dane:  <a href="https://simpay.pl/panel/Client/API">https://simpay.pl/panel/Client/API</a> <br /> <br />
    		<input type="text" name="api_key" placeholder="API KEY" value="' . get_option("simpay_key") . '" required/>
         	<input type="text" name="secret_key" placeholder="SECRET" value="' . get_option("simpay_secret") . '" required/>';

    wp_nonce_field( 'simpay' );

    echo '<button type="submit" value="1" name="aktualizuj_key" class="button button-primary">Aktualizuj</button>
        </form><br />
' . $t_menu . '<br /><br />';

	if (isset($_GET['register']) && $_GET['register'] == 1) {
		try {
			$json = $simpay->getServices();
			if (!isset($json['respond']['status'])) {
				return;
			}
			if ($json['respond']['status'] != "OK") {
				//Błąd podczas pobierania?
			}
			echo '<form style="margin-top: 30px;" method="post">';
				echo '<select name="usluga1" id="usluga2">';
					echo '<option value="0" disabled="disabled" selected="selected">Wybierz usługę</option>';
					foreach($json['respond']['services'] as $service) :
						if (!empty($service) && $service['status'] == "service_active") :
							echo '<option value="' . $service['id'] . '">' . $service['prefix'] . '. ' . $service["sufix"] . '</option>';
						endif;
					endforeach;
				echo '</select>';
			
			foreach ($json['respond']['services'] as $service) :
				if ($service['status'] == "service_active") :
			
				echo '<select name="prices1_' . $service['id'] . '" id="prices_' . $service['id'] . '" class="selectsms" style="display: none;">';
					echo '<option value="0" disabled="disabled" selected="selected">Wybierz cenę...</option>';
					foreach ($service['numbers'] as $number) :
					echo '<option value="' . $number . '">' . fnc_calculate_brutto($simpay->getSMSValue($number)) . ' (' . $number . ')</option>';
					endforeach;
				echo '</select>';
			
				endif;
			endforeach;

			wp_nonce_field('simpay');

			echo '</select><button type="submit" value="1" name="aktualizuj_2" class="button button-primary">Dodaj</button>
</form>';
		}
		catch(Exception $e) {
			echo 'Error: ' . $e->getMessage();
		}
	} else {
		echo 'Dodaj usługe';

		try {
			$json = $simpay->getServices();
			if (!isset($json['respond']['status'])) {
				return;
			}
			
			if ($json['respond']['status'] != "OK") {
				//Błąd podczas pobierania?
			}
			echo '<form style="margin-top: 30px;" method="post">';
				echo '<select name="usluga2" id="usluga2">';
					echo '<option value="0" disabled="disabled" selected="selected">Wybierz usługę</option>';
				
					foreach($json['respond']['services'] as $service) :
						if (!empty($service) && $service['status'] == "service_active") :
					
						echo '<option value="' .  $service['id'] . '">' . $service['prefix'] . '.' . $service["sufix"] . '</option>';
						endif;
					
					endforeach;
				
				echo '</select>';
			
			foreach ($json['respond']['services'] as $service) :
				if ($service['status'] == "service_active") :
			
				echo '<select name="prices2_' . $service['id'] . '" id="prices_' . $service['id'] . '" class="selectsms" style="display: none;">';
					echo '<option value="0" disabled="disabled" selected="selected">Wybierz cenę...</option>';
					
					foreach ($service['numbers'] as $number) :
						echo '<option value="' . $number . '">' . fnc_calculate_brutto($simpay->getSMSValue($number)) . ' (' . $number . ')</option>';
					endforeach;
					
				echo '</select>';
			
				endif;
			endforeach;

			wp_nonce_field('simpay');

			echo '<button type="submit" value="1" name="aktualizuj_3" class="button button-primary">Dodaj</button>
</form>';
		}
		catch(Exception $e) {
			echo 'Error: ' . $e->getMessage();
		}
		global $wpdb;

		$querystr = "SELECT * FROM " . $wpdb->prefix . "simpay";

		$uslugi = $wpdb->get_results($querystr, OBJECT);

		echo '<table class="wp-list-table widefat striped">
        <tr>
            <td>Usługa</td>
            <td>Kod</td>
            <td>Akcja</td>
        </tr>';

		foreach($uslugi as & $value) {
			echo '
			<tr>
			<td>' . $value->numer . ':' . $value->usluga . '</td>
			<td><textarea style="width: 100%;"> [hidden_content_' . $value->id . ']Odblokowana zawartość[/hidden_content_' . $value->id . ']</textarea></td>
			<td><form method="post"><button name="delete" type="submit" value="' . $value->id . '" class="button button-primary">Usuń</button>';

			wp_nonce_field( 'simpay' );

			echo '</form></td>
			</tr>
   ';
		}

		if (isset($_POST['delete'])) {

			$table = $wpdb->prefix . "simpay";

			$postID = intval(trim($_POST['delete']));

			$wpdb->delete($table, array(
				'id' => $postID
			));
			
			echo "<script type='text/javascript'> window.location=document.location.href;</script>";
			
		}
		
		echo ' </table>';
	}
	
	return '';
}