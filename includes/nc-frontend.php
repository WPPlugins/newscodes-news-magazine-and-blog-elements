<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class NC_Frontend {

		public static function init() {

			add_action( 'wp_enqueue_scripts', __CLASS__ . '::scripts' );
			add_action( 'wp_footer', __CLASS__ . '::localize_scripts' );

		}

		public static function localize_scripts() {

			$load_fonts = array();

			if ( isset( NC_Shortcodes::$styles ) ) {

				$styles = array_unique( NC_Shortcodes::$styles );

				if ( is_array( $styles ) ) {

					$arrange_styles = array();

					$option = get_option( 'newscodes_settings', array( 'styles' => array() ) );
					$known_styles = apply_filters( 'nc_supported_styles', $option['styles'] );
					$fonts_array = self::font_families( '', 'nc-settings' );

					foreach( $known_styles as $group ) {
						$arrange_styles = array_merge( $arrange_styles, $group['styles'] );
					}

					foreach( $styles as $style ) {

						if ( isset( $arrange_styles[$style] ) ) {

							$fonts = array();
							$pass_fonts = array();
							$settings = $arrange_styles[$style];

							foreach ( $settings as $k => $v ) {
								if ( isset( $v['font-family'] ) && isset( $fonts_array[$v['font-family']] ) ) {
									$fonts[$v['font-family']]['name'] = $fonts_array[$v['font-family']];
									$fonts[$v['font-family']]['slug'] = substr( $v['font-family'], 4 );
									$fonts[$v['font-family']]['type'] = substr( $v['font-family'], 0, 3 );
									
								}
							}

							if ( !empty( $fonts ) ) {

								$src = NC()->plugin_url() . '/lib';
								$protocol = is_ssl() ? 'https' : 'http';

								foreach( $fonts as $k => $v ) {
									if ( !isset( $v['type'] ) || isset( $pass_fonts[$v['slug']] ) ) {
										continue;
									}

									if ( $v['type'] == 'inc' ) {
										$pass_fonts[$v['slug']] = $src . '/fonts/' . $v['slug'] . '/style.css';
									}
									else if ( $v['type'] == 'ggl' ) {
										$slug = str_replace( ' ', '+', ucwords( str_replace( '-', ' ', $v['slug'] ) ) );
										$pass_fonts[$v['slug']] = $protocol . '://fonts.googleapis.com/css?family=' . $slug . '%3A100%2C200%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C700%2C700italic%2C800&amp;subset=all"';
									}
								}
							}


							if ( !empty( $fonts ) ) {
								$load_fonts = array_merge( $load_fonts, $pass_fonts );
							}
						}
					}

				}

			}

			$args = array(
				'ajax' => admin_url( 'admin-ajax.php' ),
				'instances' => NC_Shortcodes::$instances,
				'fonts' => $load_fonts
				
			);

			wp_localize_script( 'newscodes', 'nc', $args );

		}

		public static function scripts() {

			wp_register_style( 'newscodes', NC()->plugin_url() . '/lib/css/newscodes.css', false, NC()->version() );
			wp_enqueue_style( 'newscodes' );

			wp_register_script( 'newscodes', NC()->plugin_url() . '/lib/js/newscodes.js', array( 'jquery' ), NC()->version(), true );
			wp_enqueue_script( 'newscodes' );

			$nc_styles = apply_filters( 'nc_load_less_styles', get_option( '_nc_less_styles', array() ) );

			if ( !empty( $nc_styles ) && is_array( $nc_styles ) ) {

				$css_styles = get_option( '_nc_less_styles_exclude', array() );

				$upload = wp_upload_dir();

				foreach( $nc_styles as $group => $settings ) {

					if ( in_array( $group, $css_styles ) ) {
						continue;
					}
					if ( $settings['id'] == 'default' ) {
						wp_register_style( 'newscodes-' . $group . '-styles', $settings['url'], false, NC()->version() );
						wp_enqueue_style( 'newscodes-' . $group . '-styles' );
					}
					else {
						$style = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $settings['id'] . '.css';

						if ( file_exists( $style ) ) {

							wp_register_style( 'newscodes-' . $settings['id'], $settings['url'], false, $settings['id'] );
							wp_enqueue_style( 'newscodes-' . $settings['id'] );

						}
						else if ( $settings['last_known'] !== '' ) {

							$style_cached = untrailingslashit( $upload['basedir'] ) . '/nc-' . $group . '-' . $settings['last_known'] . '.css';
							$style_cached_url = untrailingslashit( $upload['baseurl'] ) . '/nc-' . $group .'-' . $settings['last_known'] . '.css';

							if ( file_exists( $style_cached ) ) {
								wp_register_style( 'newscodes-' . $settings['last_known'], $style_cached_url, false, $settings['last_known'] );
								wp_enqueue_style( 'newscodes-' . $style_cached_url );
							}

						}
					}

				}

			}

		}

		public static function font_families( $id = '' ) {

			$fonts = array(
				'inc-opensans' => '"Open Sans", sans-serif',
				'inc-raleway' => '"Raleway", sans-serif',
				'inc-lato' => '"Lato", sans-serif',
				'inc-ptsans' => '"PT Sans", sans-serif',
				'inc-ubuntu' => '"Ubuntu", sans-serif',
				'sys-arial' => 'Arial, Helvetica, sans-serif',
				'sys-black' => '"Arial Black", Gadget, sans-serif',
				'sys-georgia' => 'Georgia, serif',
				'sys-impact' => 'Impact, Charcoal, sans-serif',
				'sys-lucida' => '"Lucida Sans Unicode", "Lucida Grande", sans-serif',
				'sys-palatino' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
				'sys-tahoma' => 'Tahoma, Geneva, sans-serif',
				'sys-times' => '"Times New Roman", Times, serif',
				'sys-trebuchet' => '"Trebuchet MS", Helvetica, sans-serif',
				'sys-verdana' => 'Verdana, Geneva, sans-serif',
			);

			$google_fonts = array( 'ggl-abel' => '"Abel", sans-serif', 'ggl-abril-fatface' => '"Abril Fatface", cursive', 'ggl-aclonica' => '"Aclonica", sans-serif', 'ggl-actor' => '"Actor", sans-serif', 'ggl-adamina' => '"Adamina", serif', 'ggl-aguafina-script' => '"Aguafina Script", cursive', 'ggl-aladin' => '"Aladin", cursive', 'ggl-aldrich' => '"Aldrich", sans-serif', 'ggl-alice' => '"Alice", serif', 'ggl-alike-angular' => '"Alike Angular", serif', 'ggl-alike' => '"Alike", serif', 'ggl-allan' => '"Allan", cursive', 'ggl-allerta-stencil' => '"Allerta Stencil", sans-serif', 'ggl-allerta' => '"Allerta", sans-serif', 'ggl-amaranth' => '"Amaranth", sans-serif', 'ggl-amatic-sc' => '"Amatic SC", cursive', 'ggl-andada' => '"Andada", serif', 'ggl-andika' => '"Andika", sans-serif', 'ggl-annie-use-your-telescope' => '"Annie Use Your Telescope", cursive', 'ggl-anonymous-pro' => '"Anonymous Pro", sans-serif', 'ggl-antic' => '"Antic", sans-serif', 'ggl-anton' => '"Anton", sans-serif', 'ggl-arapey' => '"Arapey", serif', 'ggl-architects-daughter' => '"Architects Daughter", cursive', 'ggl-arimo' => '"Arimo", sans-serif', 'ggl-artifika' => '"Artifika", serif', 'ggl-arvo' => '"Arvo", serif', 'ggl-asset' => '"Asset", cursive', 'ggl-astloch' => '"Astloch", cursive', 'ggl-atomic-age' => '"Atomic Age", cursive', 'ggl-aubrey' => '"Aubrey", cursive', 'ggl-bangers' => '"Bangers", cursive', 'ggl-bentham' => '"Bentham", serif', 'ggl-bevan' => '"Bevan", serif', 'ggl-bigshot-one' => '"Bigshot One", cursive', 'ggl-bitter' => '"Bitter", serif', 'ggl-black-ops-one' => '"Black Ops One", cursive', 'ggl-bowlby-one-sc' => '"Bowlby One SC", sans-serif', 'ggl-bowlby-one' => '"Bowlby One", sans-serif', 'ggl-brawler' => '"Brawler", serif', 'ggl-bubblegum-sans' => '"Bubblegum Sans", cursive', 'ggl-buda' => '"Buda", sans-serif', 'ggl-butcherman-caps' => '"Butcherman Caps", cursive', 'ggl-cabin-condensed' => '"Cabin Condensed", sans-serif', 'ggl-cabin-sketch' => '"Cabin Sketch", cursive', 'ggl-cabin' => '"Cabin", sans-serif', 'ggl-cagliostro' => '"Cagliostro", sans-serif', 'ggl-calligraffitti' => '"Calligraffitti", cursive', 'ggl-candal' => '"Candal", sans-serif', 'ggl-cantarell' => '"Cantarell", sans-serif', 'ggl-cardo' => '"Cardo", serif', 'ggl-carme' => '"Carme", sans-serif', 'ggl-carter-one' => '"Carter One", sans-serif', 'ggl-caudex' => '"Caudex", serif', 'ggl-cedarville-cursive' => '"Cedarville Cursive", cursive', 'ggl-changa-one' => '"Changa One", cursive', 'ggl-cherry-cream-soda' => '"Cherry Cream Soda", cursive', 'ggl-chewy' => '"Chewy", cursive', 'ggl-chicle' => '"Chicle", cursive', 'ggl-chivo' => '"Chivo", sans-serif', 'ggl-coda-caption' => '"Coda Caption", sans-serif', 'ggl-coda' => '"Coda", cursive', 'ggl-comfortaa' => '"Comfortaa", cursive', 'ggl-coming-soon' => '"Coming Soon", cursive', 'ggl-contrail-one' => '"Contrail One", cursive', 'ggl-convergence' => '"Convergence", sans-serif', 'ggl-cookie' => '"Cookie", cursive', 'ggl-copse' => '"Copse", serif', 'ggl-corben' => '"Corben", cursive', 'ggl-cousine' => '"Cousine", sans-serif', 'ggl-coustard' => '"Coustard", serif', 'ggl-covered-by-your-grace' => '"Covered By Your Grace", cursive', 'ggl-crafty-girls' => '"Crafty Girls", cursive', 'ggl-creepster-caps' => '"Creepster Caps", cursive', 'ggl-crimson-text' => '"Crimson Text", serif', 'ggl-crushed' => '"Crushed", cursive', 'ggl-cuprum' => '"Cuprum", sans-serif', 'ggl-damion' => '"Damion", cursive', 'ggl-dancing-script' => '"Dancing Script", cursive', 'ggl-dawning-of-a-new-day' => '"Dawning of a New Day", cursive', 'ggl-days-one' => '"Days One", sans-serif', 'ggl-delius-swash-caps' => '"Delius Swash Caps", cursive', 'ggl-delius-unicase' => '"Delius Unicase", cursive', 'ggl-delius' => '"Delius", cursive', 'ggl-devonshire' => '"Devonshire", cursive', 'ggl-didact-gothic' => '"Didact Gothic", sans-serif', 'ggl-dorsa' => '"Dorsa", sans-serif', 'ggl-dr-sugiyama' => '"Dr Sugiyama", cursive', 'ggl-droid-sans-mono' => '"Droid Sans Mono", sans-serif', 'ggl-droid-sans' => '"Droid Sans", sans-serif', 'ggl-droid-serif' => '"Droid Serif", serif', 'ggl-eb-garamond' => '"EB Garamond", serif', 'ggl-eater-caps' => '"Eater Caps", cursive', 'ggl-expletus-sans' => '"Expletus Sans", cursive', 'ggl-fanwood-text' => '"Fanwood Text", serif', 'ggl-federant' => '"Federant", cursive', 'ggl-federo' => '"Federo", sans-serif', 'ggl-fjord-one' => '"Fjord One", serif', 'ggl-fondamento' => '"Fondamento", cursive', 'ggl-fontdiner-swanky' => '"Fontdiner Swanky", cursive', 'ggl-forum' => '"Forum", cursive', 'ggl-francois-one' => '"Francois One", sans-serif', 'ggl-gentium-basic' => '"Gentium Basic", serif', 'ggl-gentium-book-basic' => '"Gentium Book Basic", serif', 'ggl-geo' => '"Geo", sans-serif', 'ggl-geostar-fill' => '"Geostar Fill", cursive', 'ggl-geostar' => '"Geostar", cursive', 'ggl-give-you-glory' => '"Give You Glory", cursive', 'ggl-gloria-hallelujah' => '"Gloria Hallelujah", cursive', 'ggl-goblin-one' => '"Goblin One", cursive', 'ggl-gochi-hand' => '"Gochi Hand", cursive', 'ggl-goudy-bookletter-1911' => '"Goudy Bookletter 1911", serif', 'ggl-gravitas-one' => '"Gravitas One", cursive', 'ggl-gruppo' => '"Gruppo", sans-serif', 'ggl-hammersmith-one' => '"Hammersmith One", sans-serif', 'ggl-herr-von-muellerhoff' => '"Herr Von Muellerhoff", cursive', 'ggl-holtwood-one-sc' => '"Holtwood One SC", serif', 'ggl-homemade-apple' => '"Homemade Apple", cursive', 'ggl-iM-fell-dw-pica-sc' => '"IM Fell DW Pica SC", serif', 'ggl-iM-fell-dw-pica' => '"IM Fell DW Pica", serif', 'ggl-iM-fell-double-pica-sc' => '"IM Fell Double Pica SC", serif', 'ggl-iM-fell-double-pica' => '"IM Fell Double Pica", serif', 'ggl-iM-fell-english-sc' => '"IM Fell English SC", serif', 'ggl-iM-fell-english' => '"IM Fell English", serif', 'ggl-iM-fell-french-canon-sc' => '"IM Fell French Canon SC", serif', 'ggl-iM-fell-french-canon' => '"IM Fell French Canon", serif', 'ggl-iM-fell-great-primer-sc' => '"IM Fell Great Primer SC", serif', 'ggl-iM-fell-great-primer' => '"IM Fell Great Primer", serif', 'ggl-iceland' => '"Iceland", cursive', 'ggl-inconsolata' => '"Inconsolata", sans-serif', 'ggl-indie-flower' => '"Indie Flower", cursive', 'ggl-irish-grover' => '"Irish Grover", cursive', 'ggl-istok-web' => '"Istok Web", sans-serif', 'ggl-jockey-one' => '"Jockey One", sans-serif', 'ggl-josefin-sans' => '"Josefin Sans", sans-serif', 'ggl-josefin-slab' => '"Josefin Slab", serif', 'ggl-judson' => '"Judson", serif', 'ggl-julee' => '"Julee", cursive', 'ggl-jura' => '"Jura", sans-serif', 'ggl-just-another-hand' => '"Just Another Hand", cursive', 'ggl-just-me-again-down-here' => '"Just Me Again Down Here", cursive', 'ggl-kameron' => '"Kameron", serif', 'ggl-kelly-slab' => '"Kelly Slab", cursive', 'ggl-kenia' => '"Kenia", sans-serif', 'ggl-knewave' => '"Knewave", cursive', 'ggl-kranky' => '"Kranky", cursive', 'ggl-kreon' => '"Kreon", serif', 'ggl-kristi' => '"Kristi", cursive', 'ggl-la-belle-aurore' => '"La Belle Aurore", cursive', 'ggl-lancelot' => '"Lancelot", cursive', 'ggl-lato' => '"Lato", sans-serif', 'ggl-league-script' => '"League Script", cursive', 'ggl-leckerli-one' => '"Leckerli One", cursive', 'ggl-lekton' => '"Lekton", sans-serif', 'ggl-lemon' => '"Lemon", cursive', 'ggl-limelight' => '"Limelight", cursive', 'ggl-linden-hill' => '"Linden Hill", serif', 'ggl-lobster-two' => '"Lobster Two", cursive', 'ggl-lobster' => '"Lobster", cursive', 'ggl-lora' => '"Lora", serif', 'ggl-love-ya-like-a-sister' => '"Love Ya Like A Sister", cursive', 'ggl-loved-by-the-king' => '"Loved by the King", cursive', 'ggl-luckiest-guy' => '"Luckiest Guy", cursive', 'ggl-maiden-orange' => '"Maiden Orange", cursive', 'ggl-mako' => '"Mako", sans-serif', 'ggl-marck-script' => '"Marck Script", cursive', 'ggl-marvel' => '"Marvel", sans-serif', 'ggl-mate-sc' => '"Mate SC", serif', 'ggl-mate' => '"Mate", serif', 'ggl-maven-pro' => '"Maven Pro", sans-serif', 'ggl-meddon' => '"Meddon", cursive', 'ggl-medievalsharp' => '"MedievalSharp", cursive', 'ggl-megrim' => '"Megrim", cursive', 'ggl-merienda-one' => '"Merienda One", cursive', 'ggl-merriweather' => '"Merriweather", serif', 'ggl-metrophobic' => '"Metrophobic", sans-serif', 'ggl-michroma' => '"Michroma", sans-serif', 'ggl-miltonian-tattoo' => '"Miltonian Tattoo", cursive', 'ggl-miltonian' => '"Miltonian", cursive', 'ggl-miss-fajardose' => '"Miss Fajardose", cursive', 'ggl-miss-saint-delafield' => '"Miss Saint Delafield", cursive', 'ggl-modern-antiqua' => '"Modern Antiqua", cursive', 'ggl-molengo' => '"Molengo", sans-serif', 'ggl-monofett' => '"Monofett", cursive', 'ggl-monoton' => '"Monoton", cursive', 'ggl-monsieur-la-doulaise' => '"Monsieur La Doulaise", cursive', 'ggl-montez' => '"Montez", cursive', 'ggl-mountains-of-christmas' => '"Mountains of Christmas", cursive', 'ggl-mr-bedford' => '"Mr Bedford", cursive', 'ggl-mr-dafoe' => '"Mr Dafoe", cursive', 'ggl-mr-de-haviland' => '"Mr De Haviland", cursive', 'ggl-mrs-sheppards' => '"Mrs Sheppards", cursive', 'ggl-muli' => '"Muli", sans-serif', 'ggl-neucha' => '"Neucha", cursive', 'ggl-neuton' => '"Neuton", serif', 'ggl-news-cycle' => '"News Cycle", sans-serif', 'ggl-niconne' => '"Niconne", cursive', 'ggl-nixie-one' => '"Nixie One", cursive', 'ggl-nobile' => '"Nobile", sans-serif', 'ggl-nosifer-caps' => '"Nosifer Caps", cursive', 'ggl-nothing-you-could-do' => '"Nothing You Could Do", cursive', 'ggl-nova-cut' => '"Nova Cut", cursive', 'ggl-nova-flat' => '"Nova Flat", cursive', 'ggl-nova-mono' => '"Nova Mono", cursive', 'ggl-nova-oval' => '"Nova Oval", cursive', 'ggl-nova-round' => '"Nova Round", cursive', 'ggl-nova-script' => '"Nova Script", cursive', 'ggl-nova-slim' => '"Nova Slim", cursive', 'ggl-nova-square' => '"Nova Square", cursive', 'ggl-numans' => '"Numans", sans-serif', 'ggl-nunito' => '"Nunito", sans-serif', 'ggl-old-standard-tt' => '"Old Standard TT", serif', 'ggl-open-sans-condensed' => '"Open Sans Condensed", sans-serif', 'ggl-open-sans' => '"Open Sans", sans-serif', 'ggl-orbitron' => '"Orbitron", sans-serif', 'ggl-oswald' => '"Oswald", sans-serif', 'ggl-over-the-rainbow' => '"Over the Rainbow", cursive', 'ggl-ovo' => '"Ovo", serif', 'ggl-pT-sans-caption' => '"PT Sans Caption", sans-serif', 'ggl-pT-sans-narrow' => '"PT Sans Narrow", sans-serif', 'ggl-pT-sans' => '"PT Sans", sans-serif', 'ggl-pT-serif-caption' => '"PT Serif Caption", serif', 'ggl-pT-serif' => '"PT Serif", serif', 'ggl-pacifico' => '"Pacifico", cursive', 'ggl-passero-one' => '"Passero One", cursive', 'ggl-patrick-hand' => '"Patrick Hand", cursive', 'ggl-paytone-one' => '"Paytone One", sans-serif', 'ggl-permanent-marker' => '"Permanent Marker", cursive', 'ggl-petrona' => '"Petrona", serif', 'ggl-philosopher' => '"Philosopher", sans-serif', 'ggl-piedra' => '"Piedra", cursive', 'ggl-pinyon-script' => '"Pinyon Script", cursive', 'ggl-play' => '"Play", sans-serif', 'ggl-playfair-display' => '"Playfair Display", serif', 'ggl-podkova' => '"Podkova", serif', 'ggl-poller-one' => '"Poller One", cursive', 'ggl-poly' => '"Poly", serif', 'ggl-pompiere' => '"Pompiere", cursive', 'ggl-prata' => '"Prata", serif', 'ggl-prociono' => '"Prociono", serif', 'ggl-puritan' => '"Puritan", sans-serif', 'ggl-quattrocento-sans' => '"Quattrocento Sans", sans-serif', 'ggl-quattrocento' => '"Quattrocento", serif', 'ggl-questrial' => '"Questrial", sans-serif', 'ggl-quicksand' => '"Quicksand", sans-serif', 'ggl-radley' => '"Radley", serif', 'ggl-raleway' => '"Raleway", cursive', 'ggl-rammetto-one' => '"Rammetto One", cursive', 'ggl-rancho' => '"Rancho", cursive', 'ggl-rationale' => '"Rationale", sans-serif', 'ggl-redressed' => '"Redressed", cursive', 'ggl-reenie-beanie' => '"Reenie Beanie", cursive', 'ggl-ribeye-marrow' => '"Ribeye Marrow", cursive', 'ggl-ribeye' => '"Ribeye", cursive', 'ggl-righteous' => '"Righteous", cursive', 'ggl-rochester' => '"Rochester", cursive', 'ggl-rock-salt' => '"Rock Salt", cursive', 'ggl-rokkitt' => '"Rokkitt", serif', 'ggl-rosario' => '"Rosario", sans-serif', 'ggl-ruslan-display' => '"Ruslan Display", cursive', 'ggl-salsa' => '"Salsa", cursive', 'ggl-sancreek' => '"Sancreek", cursive', 'ggl-sansita-one' => '"Sansita One", cursive', 'ggl-satisfy' => '"Satisfy", cursive', 'ggl-schoolbell' => '"Schoolbell", cursive', 'ggl-shadows-into-light' => '"Shadows Into Light", cursive', 'ggl-shanti' => '"Shanti", sans-serif', 'ggl-short-stack' => '"Short Stack", cursive', 'ggl-sigmar-one' => '"Sigmar One", sans-serif', 'ggl-signika-negative' => '"Signika Negative", sans-serif', 'ggl-signika' => '"Signika", sans-serif', 'ggl-six-caps' => '"Six Caps", sans-serif', 'ggl-slackey' => '"Slackey", cursive', 'ggl-smokum' => '"Smokum", cursive', 'ggl-smythe' => '"Smythe", cursive', 'ggl-sniglet' => '"Sniglet", cursive', 'ggl-snippet' => '"Snippet", sans-serif', 'ggl-sorts-mill-goudy' => '"Sorts Mill Goudy", serif', 'ggl-special-elite' => '"Special Elite", cursive', 'ggl-spinnaker' => '"Spinnaker", sans-serif', 'ggl-spirax' => '"Spirax", cursive', 'ggl-stardos-stencil' => '"Stardos Stencil", cursive', 'ggl-sue-ellen-francisco' => '"Sue Ellen Francisco", cursive', 'ggl-sunshiney' => '"Sunshiney", cursive', 'ggl-supermercado-one' => '"Supermercado One", cursive', 'ggl-swanky-and-moo-moo' => '"Swanky and Moo Moo", cursive', 'ggl-syncopate' => '"Syncopate", sans-serif', 'ggl-tangerine' => '"Tangerine", cursive', 'ggl-tenor-sans' => '"Tenor Sans", sans-serif', 'ggl-terminal-dosis' => '"Terminal Dosis", sans-serif', 'ggl-the-girl-next-door' => '"The Girl Next Door", cursive', 'ggl-tienne' => '"Tienne", serif', 'ggl-tinos' => '"Tinos", serif', 'ggl-tulpen-one' => '"Tulpen One", cursive', 'ggl-ubuntu-condensed' => '"Ubuntu Condensed", sans-serif', 'ggl-ubuntu-mono' => '"Ubuntu Mono", sans-serif', 'ggl-ubuntu' => '"Ubuntu", sans-serif', 'ggl-ultra' => '"Ultra", serif', 'ggl-unifrakturcook' => '"UnifrakturCook", cursive', 'ggl-unifrakturmaguntia' => '"UnifrakturMaguntia", cursive', 'ggl-unkempt' => '"Unkempt", cursive', 'ggl-unlock' => '"Unlock", cursive', 'ggl-unna' => '"Unna", serif', 'ggl-vt323' => '"VT323", cursive', 'ggl-varela-round' => '"Varela Round", sans-serif', 'ggl-varela' => '"Varela", sans-serif', 'ggl-vast-shadow' => '"Vast Shadow", cursive', 'ggl-vibur' => '"Vibur", cursive', 'ggl-vidaloka' => '"Vidaloka", serif', 'ggl-volkhov' => '"Volkhov", serif', 'ggl-vollkorn' => '"Vollkorn", serif', 'ggl-voltaire' => '"Voltaire", sans-serif', 'ggl-waiting-for-the-sunrise' => '"Waiting for the Sunrise", cursive', 'ggl-wallpoet' => '"Wallpoet", cursive', 'ggl-walter-turncoat' => '"Walter Turncoat", cursive', 'ggl-wire-one' => '"Wire One", sans-serif', 'ggl-yanone-kaffeesatz' => '"Yanone Kaffeesatz", sans-serif', 'ggl-yellowtail' => '"Yellowtail", cursive', 'ggl-yeseva-one' => '"Yeseva One", serif', 'ggl-zeyada' => '"Zeyada", cursive' );

			$fonts = $fonts + $google_fonts;

			if ( $id == 'nc-settings' ) {
				array_unshift( $fonts, array( '', 'false' ) );
			}

			return $fonts;

		}

	}

	NC_Frontend::init();

?>