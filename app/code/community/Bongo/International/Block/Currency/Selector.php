<?php

class Bongo_International_Block_Currency_Selector extends Mage_Directory_Block_Currency {
	var $countries = array (), $currencies = array ();
	
	public function __construct() {
		$this->countries ["AF"] = array ("name" => "Afghanistan", "currency" => "USD" );
		$this->countries ["AL"] = array ("name" => "Albania", "currency" => "EUR" );
		$this->countries ["DZ"] = array ("name" => "Algeria", "currency" => "DZD" );
		$this->countries ["AS"] = array ("name" => "American Samoa", "currency" => "USD" );
		$this->countries ["AD"] = array ("name" => "Andorra", "currency" => "EUR" );
		$this->countries ["AO"] = array ("name" => "Angola", "currency" => "USD" );
		$this->countries ["AI"] = array ("name" => "Anguilla", "currency" => "XCD" );
		$this->countries ["AG"] = array ("name" => "Antigua", "currency" => "XCD" );
		$this->countries ["AR"] = array ("name" => "Argentina", "currency" => "ARS" );
		$this->countries ["AM"] = array ("name" => "Armenia", "currency" => "AMD" );
		$this->countries ["AW"] = array ("name" => "Aruba", "currency" => "AWG" );
		$this->countries ["AU"] = array ("name" => "Australia", "currency" => "AUD" );
		$this->countries ["AT"] = array ("name" => "Austria", "currency" => "EUR" );
		$this->countries ["AZ"] = array ("name" => "Azerbaijan", "currency" => "AZN" );
		$this->countries ["1F"] = array ("name" => "Azores (Portugal) ", "currency" => "EUR" );
		$this->countries ["BS"] = array ("name" => "Bahamas", "currency" => "BSD" );
		$this->countries ["BH"] = array ("name" => "Bahrain", "currency" => "BHD" );
		$this->countries ["BD"] = array ("name" => "Bangladesh", "currency" => "BDT" );
		$this->countries ["BB"] = array ("name" => "Barbados", "currency" => "BBD" );
		$this->countries ["1A"] = array ("name" => "Barbuda", "currency" => "XCD" );
		$this->countries ["BY"] = array ("name" => "Belarus", "currency" => "BYR" );
		$this->countries ["BE"] = array ("name" => "Belgium", "currency" => "EUR" );
		$this->countries ["BZ"] = array ("name" => "Belize", "currency" => "BZD" );
		$this->countries ["BJ"] = array ("name" => "Benin", "currency" => "XOF" );
		$this->countries ["BM"] = array ("name" => "Bermuda", "currency" => "BMD" );
		$this->countries ["BT"] = array ("name" => "Bhutan", "currency" => "INR" );
		$this->countries ["BO"] = array ("name" => "Bolivia", "currency" => "BOB" );
		$this->countries ["X1"] = array ("name" => "Bonaire", "currency" => "USD" );
		$this->countries ["BA"] = array ("name" => "Bosnia and Herzegovina", "currency" => "EUR" );
		$this->countries ["BW"] = array ("name" => "Botswana", "currency" => "BWP" );
		$this->countries ["BR"] = array ("name" => "Brazil", "currency" => "BRL" );
		$this->countries ["BN"] = array ("name" => "Brunei", "currency" => "BND" );
		$this->countries ["BG"] = array ("name" => "Bulgaria", "currency" => "BGN" );
		$this->countries ["BF"] = array ("name" => "Burkina Faso", "currency" => "XOF" );
		$this->countries ["BI"] = array ("name" => "Burundi", "currency" => "EUR" );
		$this->countries ["KH"] = array ("name" => "Cambodia", "currency" => "KHR" );
		$this->countries ["CM"] = array ("name" => "Cameroon", "currency" => "XAF" );
		$this->countries ["CA"] = array ("name" => "Canada", "currency" => "CAD" );
		$this->countries ["X2"] = array ("name" => "Canary Islands", "currency" => "EUR" );
		$this->countries ["CV"] = array ("name" => "Cape Verde", "currency" => "CVE" );
		$this->countries ["KY"] = array ("name" => "Cayman Islands", "currency" => "KYD" );
		$this->countries ["CF"] = array ("name" => "Central African Republic", "currency" => "XAF" );
		$this->countries ["TD"] = array ("name" => "Chad", "currency" => "XAF" );
		$this->countries ["CL"] = array ("name" => "Chile", "currency" => "CLP" );
		$this->countries ["CN"] = array ("name" => "China", "currency" => "CNY" );
		$this->countries ["CO"] = array ("name" => "Colombia", "currency" => "COP" );
		$this->countries ["MP"] = array ("name" => "Commonwealth No. Mariana Islands", "currency" => "USD" );
		$this->countries ["KM"] = array ("name" => "Comoros Islands", "currency" => "KMF" );
		$this->countries ["CG"] = array ("name" => "Congo", "currency" => "XAF" );
		$this->countries ["CD"] = array ("name" => "Congo, Democratic Republic", "currency" => "EUR" );
		$this->countries ["CK"] = array ("name" => "Cook Islands", "currency" => "NZD" );
		$this->countries ["CR"] = array ("name" => "Costa Rica", "currency" => "CRC" );
		$this->countries ["HR"] = array ("name" => "Croatia", "currency" => "EUR" );
		$this->countries ["CW"] = array ("name" => "Curacao", "currency" => "ANG" );
		$this->countries ["CY"] = array ("name" => "Cyprus", "currency" => "EUR" );
		$this->countries ["CZ"] = array ("name" => "Czech Republic", "currency" => "CZK" );
		$this->countries ["DK"] = array ("name" => "Denmark", "currency" => "DKK" );
		$this->countries ["DJ"] = array ("name" => "Djibouti", "currency" => "DJF" );
		$this->countries ["DM"] = array ("name" => "Dominica", "currency" => "XCD" );
		$this->countries ["DO"] = array ("name" => "Dominican Republic", "currency" => "DOP" );
		$this->countries ["TP"] = array ("name" => "East Timor", "currency" => "USD" );
		$this->countries ["EC"] = array ("name" => "Ecuador", "currency" => "USD" );
		$this->countries ["EG"] = array ("name" => "Egypt", "currency" => "EGP" );
		$this->countries ["SV"] = array ("name" => "El Salvador", "currency" => "USD" );
		$this->countries ["1D"] = array ("name" => "England (U.K)", "currency" => "GBP" );
		$this->countries ["GQ"] = array ("name" => "Equatorial Guinea", "currency" => "XAF" );
		$this->countries ["ER"] = array ("name" => "Eritrea", "currency" => "EUR" );
		$this->countries ["EE"] = array ("name" => "Estonia", "currency" => "EUR" );
		$this->countries ["ET"] = array ("name" => "Ethiopia", "currency" => "ETB" );
		$this->countries ["FK"] = array ("name" => "Falkland Islands", "currency" => "FKP" );
		$this->countries ["FO"] = array ("name" => "Faroe Islands", "currency" => "DKK" );
		$this->countries ["FJ"] = array ("name" => "Fiji Islands", "currency" => "FJD" );
		$this->countries ["FI"] = array ("name" => "Finland", "currency" => "EUR" );
		$this->countries ["FR"] = array ("name" => "France", "currency" => "EUR" );
		$this->countries ["GF"] = array ("name" => "French Guiana", "currency" => "EUR" );
		$this->countries ["PF"] = array ("name" => "French Polynesia", "currency" => "XPF" );
		$this->countries ["GA"] = array ("name" => "Gabon", "currency" => "XAF" );
		$this->countries ["GM"] = array ("name" => "Gambia", "currency" => "GMD" );
		$this->countries ["GE"] = array ("name" => "Georgia", "currency" => "GEL" );
		$this->countries ["DE"] = array ("name" => "Germany", "currency" => "EUR" );
		$this->countries ["GH"] = array ("name" => "Ghana", "currency" => "USD" );
		$this->countries ["GI"] = array ("name" => "Gibraltar", "currency" => "GIP" );
		$this->countries ["GR"] = array ("name" => "Greece", "currency" => "EUR" );
		$this->countries ["GL"] = array ("name" => "Greenland", "currency" => "DKK" );
		$this->countries ["GD"] = array ("name" => "Grenada", "currency" => "XCD" );
		$this->countries ["GP"] = array ("name" => "Guadeloupe", "currency" => "EUR" );
		$this->countries ["GU"] = array ("name" => "Guam", "currency" => "USD" );
		$this->countries ["GT"] = array ("name" => "Guatemala", "currency" => "GTQ" );
		$this->countries ["GG"] = array ("name" => "Guernsey", "currency" => "GBP" );
		$this->countries ["GN"] = array ("name" => "Guinea", "currency" => "GNF" );
		$this->countries ["GW"] = array ("name" => "Guinea-Bissau", "currency" => "XOF" );
		$this->countries ["GY"] = array ("name" => "Guyana", "currency" => "GYD" );
		$this->countries ["HT"] = array ("name" => "Haiti", "currency" => "USD" );
		$this->countries ["HN"] = array ("name" => "Honduras", "currency" => "HNL" );
		$this->countries ["HK"] = array ("name" => "Hong Kong", "currency" => "HKD" );
		$this->countries ["HU"] = array ("name" => "Hungary", "currency" => "HUF" );
		$this->countries ["IS"] = array ("name" => "Iceland", "currency" => "ISK" );
		$this->countries ["IN"] = array ("name" => "India", "currency" => "INR" );
		$this->countries ["ID"] = array ("name" => "Indonesia", "currency" => "IDR" );
		$this->countries ["IR"] = array ("name" => "Iran", "currency" => "EUR" );
		$this->countries ["IQ"] = array ("name" => "Iraq", "currency" => "USD" );
		$this->countries ["1E"] = array ("name" => "Ireland, Northern (U.K.) ", "currency" => "GBP" );
		$this->countries ["IE"] = array ("name" => "Ireland, Republic of ", "currency" => "EUR" );
		$this->countries ["IL"] = array ("name" => "Israel", "currency" => "ILS" );
		$this->countries ["IT"] = array ("name" => "Italy", "currency" => "EUR" );
		$this->countries ["CI"] = array ("name" => "Ivory Coast", "currency" => "XOF" );
		$this->countries ["JM"] = array ("name" => "Jamaica", "currency" => "JMD" );
		$this->countries ["JP"] = array ("name" => "Japan", "currency" => "JPY" );
		$this->countries ["JE"] = array ("name" => "Jersey", "currency" => "GBP" );
		$this->countries ["JO"] = array ("name" => "Jordan", "currency" => "JOD" );
		$this->countries ["KZ"] = array ("name" => "Kazakhstan", "currency" => "KZT" );
		$this->countries ["KE"] = array ("name" => "Kenya", "currency" => "KES" );
		$this->countries ["KI"] = array ("name" => "Kiribati", "currency" => "AUD" );
		$this->countries ["KR"] = array ("name" => "Korea, Republic of", "currency" => "KRW" );
		$this->countries ["KP"] = array ("name" => "Korea, The D.P.R of (North K.)", "currency" => "EUR" );
		$this->countries ["KV"] = array ("name" => "Kosovo", "currency" => "EUR" );
		$this->countries ["KW"] = array ("name" => "Kuwait", "currency" => "KWD" );
		$this->countries ["KG"] = array ("name" => "Kyrgyzstan", "currency" => "KGS" );
		$this->countries ["LA"] = array ("name" => "Laos", "currency" => "LAK" );
		$this->countries ["LV"] = array ("name" => "Latvia", "currency" => "EUR" );
		$this->countries ["LB"] = array ("name" => "Lebanon", "currency" => "LBP" );
		$this->countries ["LS"] = array ("name" => "Lesotho", "currency" => "ZAR" );
		$this->countries ["LR"] = array ("name" => "Liberia", "currency" => "USD" );
		$this->countries ["LY"] = array ("name" => "Libya", "currency" => "LYD" );
		$this->countries ["LI"] = array ("name" => "Liechtenstein", "currency" => "CHF" );
		$this->countries ["LT"] = array ("name" => "Lithuania", "currency" => "LTL" );
		$this->countries ["LU"] = array ("name" => "Luxembourg", "currency" => "EUR" );
		$this->countries ["MO"] = array ("name" => "Macau", "currency" => "MOP" );
		$this->countries ["MK"] = array ("name" => "Macedonia", "currency" => "USD" );
		$this->countries ["MG"] = array ("name" => "Madagascar", "currency" => "EUR" );
		$this->countries ["1G"] = array ("name" => "Madeira (Portugal)", "currency" => "EUR" );
		$this->countries ["MW"] = array ("name" => "Malawi", "currency" => "MWK" );
		$this->countries ["MY"] = array ("name" => "Malaysia", "currency" => "MYR" );
		$this->countries ["MV"] = array ("name" => "Maldives", "currency" => "MVR" );
		$this->countries ["ML"] = array ("name" => "Mali", "currency" => "XOF" );
		$this->countries ["MT"] = array ("name" => "Malta", "currency" => "EUR" );
		$this->countries ["MH"] = array ("name" => "Marshall Islands", "currency" => "USD" );
		$this->countries ["MQ"] = array ("name" => "Martinique", "currency" => "EUR" );
		$this->countries ["MR"] = array ("name" => "Mauritania", "currency" => "MRO" );
		$this->countries ["MU"] = array ("name" => "Mauritius", "currency" => "MUR" );
		$this->countries ["YT"] = array ("name" => "Mayotte", "currency" => "EUR" );
		$this->countries ["MX"] = array ("name" => "Mexico", "currency" => "MXN" );
		$this->countries ["FM"] = array ("name" => "Micronesia", "currency" => "USD" );
		$this->countries ["MD"] = array ("name" => "Moldova", "currency" => "MDL" );
		$this->countries ["MC"] = array ("name" => "Monaco", "currency" => "EUR" );
		$this->countries ["MN"] = array ("name" => "Mongolia", "currency" => "MNT" );
		$this->countries ["ME"] = array ("name" => "Montenegro, Republica of", "currency" => "EUR" );
		$this->countries ["MS"] = array ("name" => "Montserrat", "currency" => "XCD" );
		$this->countries ["MA"] = array ("name" => "Morocco", "currency" => "MAD" );
		$this->countries ["MZ"] = array ("name" => "Mozambique", "currency" => "USD" );
		$this->countries ["MM"] = array ("name" => "Myanmar (Burma)", "currency" => "USD" );
		$this->countries ["NA"] = array ("name" => "Namibia", "currency" => "ZAR" );
		$this->countries ["NR"] = array ("name" => "Nauru, Republic of", "currency" => "AUD" );
		$this->countries ["NP"] = array ("name" => "Nepal", "currency" => "NPR" );
		$this->countries ["NL"] = array ("name" => "Netherlands, The", "currency" => "EUR" );
		$this->countries ["NK"] = array ("name" => "Nevis", "currency" => "XCD" );
		$this->countries ["NC"] = array ("name" => "New Caledonia", "currency" => "XPF" );
		$this->countries ["NZ"] = array ("name" => "New Zealand", "currency" => "NZD" );
		$this->countries ["NI"] = array ("name" => "Nicaragua", "currency" => "NIO" );
		$this->countries ["NE"] = array ("name" => "Niger", "currency" => "XOF" );
		$this->countries ["NG"] = array ("name" => "Nigeria", "currency" => "NGN" );
		$this->countries ["NU"] = array ("name" => "Niue Island", "currency" => "NZD" );
		$this->countries ["NO"] = array ("name" => "Norway", "currency" => "NOK" );
		$this->countries ["OM"] = array ("name" => "Oman", "currency" => "OMR" );
		$this->countries ["PK"] = array ("name" => "Pakistan", "currency" => "PKR" );
		$this->countries ["PW"] = array ("name" => "Palau", "currency" => "USD" );
		$this->countries ["PS"] = array ("name" => "Palestine", "currency" => "JOD" );
		$this->countries ["PA"] = array ("name" => "Panama", "currency" => "USD" );
		$this->countries ["PG"] = array ("name" => "Papua New Guinea", "currency" => "PGK" );
		$this->countries ["PY"] = array ("name" => "Paraguay", "currency" => "PYG" );
		$this->countries ["PE"] = array ("name" => "Peru", "currency" => "PEN" );
		$this->countries ["PH"] = array ("name" => "Philippines", "currency" => "PHP" );
		$this->countries ["PL"] = array ("name" => "Poland", "currency" => "PLN" );
		$this->countries ["PT"] = array ("name" => "Portugal", "currency" => "EUR" );
		$this->countries ["PR"] = array ("name" => "Puerto Rico", "currency" => "USD" );
		$this->countries ["QA"] = array ("name" => "Qatar", "currency" => "QAR" );
		$this->countries ["RE"] = array ("name" => "Reunion Island", "currency" => "EUR" );
		$this->countries ["RO"] = array ("name" => "Romania", "currency" => "RON" );
		$this->countries ["RU"] = array ("name" => "Russia", "currency" => "RUB" );
		$this->countries ["RW"] = array ("name" => "Rwanda", "currency" => "RWF" );
		$this->countries ["AN"] = array ("name" => "Saba", "currency" => "USD" );
		$this->countries ["X8"] = array ("name" => "Saipan", "currency" => "USD" );
		$this->countries ["1M"] = array ("name" => "Samoa", "currency" => "WST" );
		$this->countries ["SM"] = array ("name" => "San Marino", "currency" => "EUR" );
		$this->countries ["ST"] = array ("name" => "Sao Tome and Principe", "currency" => "STD" );
		$this->countries ["SA"] = array ("name" => "Saudi Arabia", "currency" => "SAR" );
		$this->countries ["1C"] = array ("name" => "Scotland (U.K)", "currency" => "GBP" );
		$this->countries ["SN"] = array ("name" => "Senegal", "currency" => "XOF" );
		$this->countries ["RS"] = array ("name" => "Serbia, Republic of", "currency" => "EUR" );
		$this->countries ["SC"] = array ("name" => "Seychelles", "currency" => "SCR" );
		$this->countries ["SL"] = array ("name" => "Sierra Leone", "currency" => "SLL" );
		$this->countries ["SG"] = array ("name" => "Singapore", "currency" => "SGD" );
		$this->countries ["SK"] = array ("name" => "Slovakia", "currency" => "EUR" );
		$this->countries ["SI"] = array ("name" => "Slovenia", "currency" => "EUR" );
		$this->countries ["SB"] = array ("name" => "Solomon Islands", "currency" => "SBD" );
		$this->countries ["SO"] = array ("name" => "Somalia", "currency" => "SOS" );
		$this->countries ["X9"] = array ("name" => "Somaliland", "currency" => "USD" );
		$this->countries ["ZA"] = array ("name" => "South Africa", "currency" => "ZAR" );
		$this->countries ["ES"] = array ("name" => "Spain", "currency" => "EUR" );
		$this->countries ["LK"] = array ("name" => "Sri Lanka", "currency" => "LKR" );
		$this->countries ["BL"] = array ("name" => "St. Barthelemy", "currency" => "EUR" );
		$this->countries ["1L"] = array ("name" => "St. Croix", "currency" => "USD" );
		$this->countries ["XB"] = array ("name" => "St. Eustatius", "currency" => "USD" );
		$this->countries ["1J"] = array ("name" => "St. John", "currency" => "USD" );
		$this->countries ["KN"] = array ("name" => "St. Kitts", "currency" => "XCD" );
		$this->countries ["LC"] = array ("name" => "St. Lucia", "currency" => "XCD" );
		$this->countries ["MF"] = array ("name" => "St. Maarten", "currency" => "ANG" );
		$this->countries ["1K"] = array ("name" => "St. Thomas", "currency" => "USD" );
		$this->countries ["VC"] = array ("name" => "St. Vincent", "currency" => "XCD" );
		$this->countries ["SR"] = array ("name" => "Suriname", "currency" => "EUR" );
		$this->countries ["SZ"] = array ("name" => "Swaziland", "currency" => "SZL" );
		$this->countries ["SE"] = array ("name" => "Sweden", "currency" => "SEK" );
		$this->countries ["CH"] = array ("name" => "Switzerland", "currency" => "CHF" );
		$this->countries ["SY"] = array ("name" => "Syria", "currency" => "EUR" );
		$this->countries ["XG"] = array ("name" => "Tahiti", "currency" => "EUR" );
		$this->countries ["TW"] = array ("name" => "Taiwan", "currency" => "TWD" );
		$this->countries ["TJ"] = array ("name" => "Tajikistan", "currency" => "USD" );
		$this->countries ["TZ"] = array ("name" => "Tanzania", "currency" => "TZS" );
		$this->countries ["TH"] = array ("name" => "Thailand", "currency" => "THB" );
		$this->countries ["TG"] = array ("name" => "Togo", "currency" => "XOF" );
		$this->countries ["TO"] = array ("name" => "Tonga", "currency" => "TOP" );
		$this->countries ["TT"] = array ("name" => "Trinidad and Tobago", "currency" => "TTD" );
		$this->countries ["TN"] = array ("name" => "Tunisia", "currency" => "TND" );
		$this->countries ["TR"] = array ("name" => "Turkey", "currency" => "TRY" );
		$this->countries ["TM"] = array ("name" => "Turkmenistan", "currency" => "USD" );
		$this->countries ["TC"] = array ("name" => "Turks and Caicos Islands", "currency" => "USD" );
		$this->countries ["TV"] = array ("name" => "Tuvalu", "currency" => "AUD" );
		$this->countries ["UG"] = array ("name" => "Uganda", "currency" => "UGX" );
		$this->countries ["UA"] = array ("name" => "Ukraine", "currency" => "UAH" );
		$this->countries ["AE"] = array ("name" => "United Arab Emirates", "currency" => "AED" );
		$this->countries ["GB"] = array ("name" => "United Kingdom", "currency" => "GBP" );
		$this->countries ["US"] = array ("name" => "United States", "currency" => "USD" );
		$this->countries ["AK"] = array ("name" => "United States - Alaska", "currency" => "USD" );
		$this->countries ["HI"] = array ("name" => "United States - Hawaii", "currency" => "USD" );
		$this->countries ["UY"] = array ("name" => "Uruguay", "currency" => "UYU" );
		$this->countries ["UZ"] = array ("name" => "Uzbekistan", "currency" => "UZS" );
		$this->countries ["VU"] = array ("name" => "Vanuatu", "currency" => "VUV" );
		$this->countries ["VE"] = array ("name" => "Venezuela", "currency" => "EUR" );
		$this->countries ["VN"] = array ("name" => "Vietnam", "currency" => "VND" );
		$this->countries ["VG"] = array ("name" => "Virgin Islands (BR)", "currency" => "USD" );
		$this->countries ["VI"] = array ("name" => "Virgin Islands (US)", "currency" => "USD" );
		$this->countries ["1B"] = array ("name" => "Wales (U.K.) ", "currency" => "GBP" );
		$this->countries ["WF"] = array ("name" => "Wallis and Futuna ", "currency" => "XPF" );
		$this->countries ["WS"] = array ("name" => "Western Samoa", "currency" => "WST" );
		$this->countries ["YE"] = array ("name" => "Yemen", "currency" => "YER" );
		$this->countries ["YU"] = array ("name" => "Yugoslavia", "currency" => "EUR" );
		$this->countries ["ZM"] = array ("name" => "Zambia", "currency" => "USD" );
		$this->countries ["ZW"] = array ("name" => "Zimbabwe", "currency" => "USD" );
		
		foreach ( $this->getCurrencies () as $_code => $_name ) {
			$this->currencies [] = $_code;
		}
		
		parent::__construct ();
	}
	
	public function getIsEnabled() {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2" && Mage::getStoreConfig ( 'bongointernational_config/currency/currency_conversion' ) && $this->getCurrencyCount () > 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getIsGeoEnabled() {
		return Mage::getStoreConfig ( 'bongointernational_config/currency/ip_geolocation' );
	}
	
	public function getIsLightboxEnabled() {
		return Mage::getStoreConfig ( 'bongointernational_config/currency/welcome_lightbox' );
	}
	
	public function getCountries() {
		return $this->countries;
	}
	
	public function getCurrentCountry() {
		$current_country = $this->getRequest ()->getParam ( '__country' );
		
		if (! empty ( $current_country ) && array_key_exists ( $current_country, $this->countries )) {
			Mage::getSingleton ( 'core/session' )->setBongoCustomerCountry ( $current_country );
		} else {
			$current_country = Mage::getSingleton ( 'core/session' )->getBongoCustomerCountry ();
		}
		
		return $current_country;
	}
	
	public function getDefaultCountry() {
		return Mage::getStoreConfig ( 'general/country/default' );
	}
	
	public function getGeoCountry() {
		$geo_country = '';
		
		if ($this->getIsGeoEnabled ()) {
			if (! defined ( 'PHP_VERSION_ID' )) {
				$version = explode ( '.', PHP_VERSION );
				
				define ( 'PHP_VERSION_ID', ($version [0] * 10000 + $version [1] * 100 + $version [2]) );
			}
			
			$remote_ip = $this->helper ( 'core/http' )->getRemoteAddr ();
			
			/*if (PHP_VERSION_ID >= 50300) {
				require_once (Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'geoip2.phar');
				eval ( 'use GeoIp2\Database\Reader;' );
				
				$reader = new Reader ( Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'GeoLite2-Country.mmdb' );
				$record = $reader->country ( $remote_ip );
				$geo_country = $record->country->isoCode;
			} else {*/
				require_once (Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'IP2Location.php');
				
				$loc = new IP2Location ( Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'IP2LOCATION-LITE-DB1.BIN', IP2Location::FILE_IO );
				//$loc = new IP2Location(Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'IP2LOCATION-LITE-DB1.BIN', IP2Location::SHARED_MEMORY);
				//$loc = new IP2Location(Mage::getModuleDir ( '', 'Bongo_International' ) . DS . 'lib' . DS . 'IP2LOCATION-LITE-DB1.BIN', IP2Location::MEMORY_CACHE);
				

				$geo_country = $loc->lookup ( $remote_ip, IP2Location::COUNTRY_CODE );
			/*}*/
		}
		
		if (empty ( $geo_country )) {
			$geo_country = $this->getDefaultCountry ();
		}
		
		return $geo_country;
	}
	
	public function getCountrySwitchUrl($_code, $_country) {
		return $this->getUrl ( 'directory/currency/switch', array ('currency' => in_array ( $_country ['currency'], $this->currencies ) ? $_country ['currency'] : Mage::app ()->getStore ()->getBaseCurrencyCode (), Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper ( 'core' )->urlEncode ( Mage::getUrl ( '*/*/*', array ('_current' => true, '_query' => array ('__country' => $_code ) ) ) ) ) );
	}
}