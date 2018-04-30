<?php
/*
Plugin Name: SimPay for WordPress
Plugin URI: https://simpay.pl/
Description:  Wtyczka pozwalająca na integrację systemu WordPress z płatnościami SimPay.
Version: 0.0.1
Author: SimPay.pl
Author URI: https://simpay.pl/
License: GPL2
*/
register_activation_hook(__FILE__, 'simpay_create_db'); //create table
add_action("admin_menu", "add_to_menu");

// install
$cennik = Array(
		'7055' => '0.50',
		'7136' => '1',
 		'7255' => '2',
		'7355' => '3',
		'7436' => '4',
		'7536' => '5',
		'7636' => '6',
		'7736' => '7',
		'7836' => '8',
		'7936' => '9',
		'91055' => '10',
		'91155' => '11',
		'91455' => '14',
		'91664' => '16',
		'91955' => '19',
		'92055' => '20',
		'92555' => '25',
    
	);
function simpay_create_db()
	{
	add_option("simpay_key", "");
	add_option("simpay_secret", "");
    add_option("simpay_type", "");
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
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'simpay';
	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        numer varchar(10) NOT NULL,
        usluga varchar(160) NOT NULL,
        id_uslugi varchar(160) NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	}

// end
// admin menu

function add_to_menu()
	{
	add_menu_page("SimPay", "SimPay", "manage_options", "simpay-ustawienia", "simpay_ustawienia_func", 'http://s6.ifotos.pl/img/smallsimp_qnawshn.png' , null, 99);
	}

// end

add_action('register_form', 'simpay_register');
add_filter('registration_errors', 'simpay_register_validate', 10, 3);

function simpay_register()
	{
    global $cennik;
	if (!empty(get_option("simpay-register-sms")))
		{
		echo '       
        <p>
            <label for="kod_sms">Wyślij SMS o treści ' . get_option("simpay-register-sms") . ' pod numer ' . get_option("simpay-register-numer") . ' koszt sms to '.round($cennik[ get_option("simpay-register-numer")]*1.23,2,PHP_ROUND_HALF_UP).' PLN (brutto), '.$cennik[ get_option("simpay-register-numer")].' PLN (netto)<br />
                <input type="text" name="kod_sms" id="kod_sms" class="input" size="25" required /></label>
        </p>
        ';
		}
	}

function simpay_register_validate($errors, $sanitized_user_login, $user_email)
	{
	define('API_KEY', get_option('simpay_key'));
	define('API_SECRET', get_option('simpay_secret'));
	define('API_VERSION', 1);
	try
		{
		require_once (plugin_dir_path(__FILE__) . 'Simpay.php');

		if (empty($_POST['kod_sms']) || !empty($_POST['kod_sms']) && trim($_POST['kod_sms']) == '')
			{
			$errors->add('first_name_error', __('<strong>ERROR</strong>: Wprowadz kod SMS.', 'mydomain'));
			}
		  else
			{
			$api = new SimPay(API_KEY, API_SECRET, API_VERSION);
			$api->getStatus(array(
				'service_id' => get_option("simpay-register-id") ,
				'number' => get_option("simpay-register-numer") ,
				'code' => $_POST['kod_sms']
			));
			if ($api->check())
				{

				// kod poprawny

				}
			  else
			if ($api->error())
				{

				// niepoprawny kod

				$errors->add('first_name_error', __('<strong>ERROR</strong>: Podany kod jest niepoprawny.', 'mydomain'));

				// print_r($api->showError());

				}
			}
		}

	catch(Exception $e)
		{
		echo 'Error: ' . $e->getMessage();
		}

	return $errors;
	}

// admin functions

function simpay_ustawienia_func()
	{
	global $wpdb;
	global $cennik;

	require_once (plugin_dir_path(__FILE__) . 'Simpay.php');

	require_once (plugin_dir_path(__FILE__) . 'jshandler.php');

	define('API_KEY', get_option('simpay_key'));
	define('API_SECRET', get_option('simpay_secret'));
	define('API_VERSION', 2);
	if (!empty($_POST["aktualizuj_key"]))
		{
		update_option("simpay_key", trim($_POST["api_key"]));
		update_option("simpay_secret", trim($_POST["secret_key"]));
        update_option("simpay_type", trim($_POST["typ"]));
		}

	if (!empty($_POST["aktualizuj_2"]))
		{
		$id_tresc = explode("|", $_POST["usluga"]);
		$numer_cena = explode("|", $_POST["cena"]);
		update_option("simpay-register-cena", $numer_cena[1]);
		update_option("simpay-register-numer", trim($numer_cena[0]));
		update_option("simpay-register-sms", trim('SIM.' . $id_tresc[1]));
		update_option("simpay-register-id", trim($id_tresc[0]));
		}

	if (!empty($_POST["aktualizuj_3"]))
		{
		global $wpdb;
		$id_tresc = explode("|", $_POST["usluga"]);
		$numer_cena = explode("|", $_POST["cena"]);
		$wpdb->insert($wpdb->prefix . "simpay", array(
			'numer' => $numer_cena[0],
			'usluga' => $id_tresc[1],
			'id_uslugi' => $id_tresc[0],
		));
		}

	$t_menu = '<ul class="subsubsub">
    <li><a href="?page=simpay-ustawienia" class="current">Blokowanie treści</a> |</li>
    <li><a href="?page=simpay-ustawienia&register=1">Blokowanie rejestracji</a></li>

</ul>';
	if (!empty($_GET["register"]))
		{
		$t_menu = '<ul class="subsubsub">
    <li><a href="?page=simpay-ustawienia" >Blokowanie treści</a> |</li>
    <li><a href="?page=simpay-ustawienia&register=1" class="current">Blokowanie rejestracji</a></li>
</ul>';
		}

	echo '
        <form style="margin-top: 30px;" method="post">
             <p><span style="font-size: 28px; font-weight: 600;">Klucz API:</span></p>
             <br>Tutaj możesz sprawdzić te dane:  <a href="https://simpay.pl/panel/Client/API">https://simpay.pl/panel/Client/API</a> <br> <br>
        <input type="text" name="api_key" placeholder="API KEY" value="' . get_option("simpay_key") . '" required/>
         <input type="text" name="secret_key" placeholder="SECRET" value="' . get_option("simpay_secret") . '" required/>

             <button type="submit" value="1" name="aktualizuj_key" class="button button-primary">Aktualizuj</button>
        </form><br />
' . $t_menu . '


<br /><br />
';
	
	if ($_GET['register'] == 1)
		{
		try
			{
			$api = new SimPay(API_KEY, API_SECRET, API_VERSION);
			$string = $api->getServices();
			$result = JsonHandler::decode($string);
			$json = json_decode($result, true);
			echo '
<form style="margin-top: 30px;" method="post">';
			echo '<select name="usluga">';
			echo '<option value="' . get_option("simpay-register-id") . '|' . get_option("simpay-register-sms") . '">---' . get_option("simpay-register-sms") . '---</option>';
			echo 'Usługa:';
			foreach($json["respond"] as $obj)
				{
				if (!empty($obj))
					{
					echo '<option value="' . $obj["id"] . '|' . $obj["sufix"] . '">SIM.' . $obj["sufix"] . '</option>';
					}
				}

			echo '</select>';
			echo '<select name="cena">';
			echo '<option value="' . get_option("simpay-register-numer") . '|' . get_option("simpay-register-cena") . '">---Numer: ' . get_option("simpay-register-numer") . ' || Cena:' . round(get_option("simpay-register-cena"), 2, PHP_ROUND_HALF_UP) . 'PLN (brutto), '.get_option("simpay-register-cena")/1.23.' PLN (netto) ---</option>';
			global $cennik;
			foreach($cennik as $num => $cena)
				{
				echo '<option value="' . $num . '|' . $cena . '">Numer: ' . $num . ' || Cena:' . round($cena*1.23, 2, PHP_ROUND_HALF_UP) . 'PLN (brutto), '.$cena.' PLN (netto)</option>';
				}

			echo '</select><button type="submit" value="1" name="aktualizuj_2" class="button button-primary">Aktualizuj</button>
</form>';
			}

		catch(Exception $e)
			{
			echo 'Error: ' . $e->getMessage();
			}
		}
	  else
		{
		echo 'Dodaj usługe';
		try
			{
			$api = new SimPay(API_KEY, API_SECRET, API_VERSION);
			$string = $api->getServices();
			$result = JsonHandler::decode($string);
			$json = json_decode($result, true);
			echo '
<form style="margin-top: 30px;" method="post">';
			echo '<select name="usluga">';
			echo 'Usługa:';
			foreach($json["respond"] as $obj)
				{
				if (!empty($obj))
					{
					echo '<option value="' . $obj["id"] . '|' . $obj["sufix"] . '">SIM.' . $obj["sufix"] . '</option>';
					}
				}

			echo '</select>';
			echo '<select name="cena">';
			foreach($cennik as $num => $cena)
				{
				echo '<option value="' . $num . '|' . $cena . '">Numer: ' . $num . ' || Cena:' . round($cena*1.23, 2, PHP_ROUND_HALF_UP) . 'PLN (brutto), '.$cena.' PLN (netto)</option>';
				}

			echo '</select><button type="submit" value="1" name="aktualizuj_3" class="button button-primary">Dodaj</button>
</form>';
			}

		catch(Exception $e)
			{
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
		foreach($uslugi as & $value)
			{
			echo '
<tr>
<td>' . $value->numer . ':' . $value->usluga . '</td>
<td><textarea style="width: 100%;"> [hidden_content_' . $value->id . ']Odblokowana zawartość[/hidden_content_' . $value->id . ']</textarea></td>
<td><form method="post"><button name="delete" type="submit" value="' . $value->id . '" class="button button-primary">Usuń</button></form></td>
</tr>
   ';
			}
        if(isset($_POST['delete'])){
             $table = $wpdb->prefix . "simpay";
            $wpdb->delete( $table, array( 'id' => $_POST['delete'] ) ); 
            header("Refresh:0");

        }
		echo ' </table>';
          
		}
	}

add_filter('the_content', 'podmien_zawartosc');

function podmien_zawartosc($content)
	{
    global $cennik;
	if (is_front_page())
		{
		global $wpdb;
		$querystr = "SELECT * FROM " . $wpdb->prefix . "simpay";
		$uslugi = $wpdb->get_results($querystr, OBJECT);
		foreach($uslugi as & $value)
			{
			$content = preg_replace("%\[hidden_content_" . $value->id . "\](.*?)\[/hidden_content_" . $value->id . "\]%s", "<B>Płatność dostępna wewnątrz wpisu</B>", $content);
			}

		return $content;
		}

	if (!in_the_loop())
		{
		return $content;
		}

	if (!is_singular())
		{
		return $content;
		}

	if (!is_main_query())
		{
		return $content;
		}

	if (strpos($content, '[hidden_content') > 0)
		{
		global $wpdb;
		$querystr = "SELECT * FROM " . $wpdb->prefix . "simpay";
		$uslugi = $wpdb->get_results($querystr, OBJECT);
		foreach($uslugi as & $value)
			{
			$kod_poprawny = false;
			$alert = "";
			if (!empty($_POST["simpay_sprawdz"]) && !empty($_POST["simpay_kodsms"]))
				{
				if ($_POST["simpay_sprawdz"] == $value->id)
					{
					define('API_KEY', get_option('simpay_key'));
					define('API_SECRET', get_option('simpay_secret'));
					define('API_VERSION', 1);
					try
						{
						require_once (plugin_dir_path(__FILE__) . 'Simpay.php');

						$api = new SimPay(API_KEY, API_SECRET, API_VERSION);
						$api->getStatus(array(
							'service_id' => $value->id_uslugi,
							'number' => $value->numer,
							'code' => $_POST['simpay_kodsms']
						));
						if ($api->check())
							{
							$kod_poprawny = true;
							}
						  else
						if ($api->error())
							{

							// niepoprawny kod

							$alert = '<br /><center style="color:red"><b>Wystąpił problem podczas sprawdzania sms</b></center><br />';

							// print_r($api->showError());

							}
						}

					catch(Exception $e)
						{
						echo 'Error: ' . $e->getMessage();
						}
					}
				}

			$forma = '
						<div class="col-md-12">' . $alert . '
						<form method="POST">
					<center
								<hr>
								<h4>Wyślij SMS o treści <span class="label label-primary">SIM.' . $value->usluga . '</span> na numer <span class="label label-primary">' . $value->numer . '</span> cena za sms wynosi <span class="label label-primary">'.round($cennik[$value->numer]*1.23, 2, PHP_ROUND_HALF_UP).'</span> PLN</h4>
								<hr>
								<h5>Wpisz kod usługi:</h5>
							
								<div class="col-md-12">
									<input value="" placeholder="Kod usługi otrzymany SMSem..."  maxlength="100" cols="25" size="100" class="form-control" name="simpay_kodsms"
									 required="" type="text"><br /><br />
									<input required type="checkbox"> Akceptuję <a href="https://simpay.pl/">regulamin usługi</a> SMS
								</div>
								<br />
								<button style="margin-top: 40px;" type="submit" value="' . $value->id . '" name="simpay_sprawdz" class="btn btn-primary btn-lg">Zweryfikuj kod</button>
</center>
								</form>
							</div>
		';
			if (!$kod_poprawny)
				{
				$content = preg_replace("%\[hidden_content_" . $value->id . "\](.*?)\[/hidden_content_" . $value->id . "\]%s", $forma, $content);
				}
			  else
				{
				$content = str_replace('[hidden_content_' . $value->id . ']', "", $content);
				$content = str_replace('[/hidden_content_' . $value->id . ']', "", $content);
				}
			}
		}

	remove_filter('the_content', 'se225721_the_content');
	return $content;
	}
?>