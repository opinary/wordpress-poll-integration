<?php
/*
Plugin Name:  Opinary Poll Integration
Plugin URI:   https://developer.wordpress.org/plugins/opinary-poll-integration/
Description:  Allows to integrate Opinary.com polls directly within content of posts and pages (This requires an account at Opinary.com)
Version:      0.1.1
Author:	      Opinary.com
Author URI:   https://opinary.com/

License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

PHP Version:  5.6 or later

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

namespace Opinary\PollIntegration;


defined( 'ABSPATH' ) or die( 'This is a plugin. Direct execution not allowed!' );
define( 'OPINARY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

require_once OPINARY_PLUGIN_PATH . 'includes/class-content-filter.php';

$content_filter = new Content_Filter();
$content_filter->register();
