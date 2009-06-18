<?php

	/**
	 * Command line interface for generating autoload manifests
	 *
	 * @package Tools
	 * @version 1.0
	 * @since   1.0
	 */

	/**
	 * Command line tools
	 */
	require_once 'cli.php';
	
	/**
	 * Prints usage
	 */
	function usage() {
		$usage = new Usage(
			'Manifest Builder 1.0',
			'php ' . basename(__FIlE__),
			'Builds a manifest file suitable for use by Autoloader',
			'Tommy Montgomery',
			'2009'
		);
		
		global $switches;
		$usage->setSwitches($switches);
		
		echo $usage;
	}
	
	if (!extension_loaded('tokenizer')) {
		throw new Exception('The "tokenizer" extension must be loaded to use this script');
	}
	
	global $switches;
	$switches = new CliSwitchCollection();
	$switches->addSwitch(new CliSwitch('directory', 'd', true,  'dir1,dir2,...',  'Comma-delimited list of directories'))
			 ->addSwitch(new CliSwitch('version',   'v', true,  'version_number', 'Version number for use in @version tag'))
			 ->addSwitch(new CliSwitch('package',   'p', true,  'package_name ',  'Name of the package for use in @package tag'))
			 ->addSwitch(new CliSwitch('output',    'o', false, 'file',           'Name of the output file, defaults to stdout if empty'))
			 ->addSwitch(new CliSwitch('quiet',     'q', false, null,             'Do not print progress messages'))
			 ->addSwitch(new CliSwitch('recursive', 'r', false, null,             'Recursively walk the directories'))
			 ->addSwitch(new CliSwitch('base-dir',  'b', true,  'dir',            'Base directory'));
	
	array_shift($argv);
	$args = Cli::parseArgs($argv, $switches);
	$args = $args['switches']; //don't care about non-switch stuff
	
	if (!isset($args['directory'], $args['version'], $args['package'], $args['base-dir'])) {
		usage();
		fwrite(STDERR, "Missing a required argument\n");
		exit(1);
	}
	
	$dirs = explode(',', $args['directory']);
	
	if (!is_dir($args['base-dir'])) {
		fwrite(STDERR, $args['base-dir'] . ' is not a directory');
		exit(1);
	}
	
	$args['recursive'] = array_key_exists('recursive', $args);
	$args['quiet']     = array_key_exists('quiet', $args);
	
	$date = date('Y-m-d H:i:s');
	$self = basename(__FILE__);
	$data = <<<ENDDATA
<?php

	/**
	 * Autoload manifest
	 *
	 * Autogenerated by $self on $date
	 *
	 * @package $args[package]
	 * @version $args[version]
	 * @since   $args[version]
	 */
	
	return array(

ENDDATA;
	
	$classes = array();
	$maxClassNameLength = 0;
	
	foreach ($dirs as $dir) {
		if (!is_dir($dir)) {
			fwrite(STDERR, $dir . ' is not a directory... skipping');
			continue;
		}
		
		$iterator = $args['recursive'] ? new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) : new DirectoryIterator($dir);
		
		foreach ($iterator as $file) {
			if ($file->isFile() && strpos($file->getPathName(), DIRECTORY_SEPARATOR . '.') === false && substr($file->getFileName(), -4) === '.php') {
				if (!$args['quiet']) {
					echo 'Processing ' . $file->getPathName() . "\n";
				}
				
				$tokens = token_get_all(file_get_contents($file->getPathName()));
				$count = count($tokens);
				for ($i = 0; $i < $count; $i++) {
					if (is_array($tokens[$i])) {
						if ($tokens[$i][0] === T_CLASS || $tokens[$i][0] === T_INTERFACE) {
							//get class name
							if (isset($tokens[$i + 2]) && is_array($tokens[$i + 2]) && $tokens[$i + 2][0] === T_STRING) {
								$className = $tokens[$i + 2][1];
								if (isset($classes[$className]) && !$args['quiet']) {
									fwrite(STDERR, '******WARNING: FOUND DUPLICATE CLASS (' . $className . ')******' . "\n");
								}
								
								$classes[$className] = ltrim(str_replace(array($args['base-dir'], DIRECTORY_SEPARATOR), array('', '/'), $file->getPathName()), '/');
								$maxClassNameLength = max($maxClassNameLength, strlen($className));
								$i += 2; //loop unroll FTW!
								
								if (!$args['quiet']) {
									echo 'Added class/interface ' . $className . "\n";
								}
							}
						}
					}
				}
				
				unset($tokens);
			}
		}
	}
	
	echo "\n" . '# of classes: ' . count($classes) . "\n\n";
	
	$func = create_function(
		'&$value, $key',
		'$value = "\t\t\'$key\'" . str_repeat(\' \', ' . $maxClassNameLength . ' - strlen($key)) . " => \'$value\'";'
	);
	
	ksort($classes);
	array_walk($classes, $func);
	
	$data .= implode(",\n", $classes) . "\n\t);\n\n?>";
	if (isset($args['output']) && !is_dir(dirname($args['output'])) && !mkdir(dirname($args['output']), 0777, true)) {
		fwrite(STDERR, 'Unable to mkdir(): ' . dirname($args['output']));
		exit(1);
	} else if (!isset($args['output'])) {
		$args['output'] = 'php://stdout';
	}
	
	if (is_dir($args['output'])) {
		$args['output'] .= DIRECTORY_SEPARATOR . 'manifest.php';
	}
		
	$fp = fopen($args['output'], 'w');
	if (!$fp) {
		fwrite(STDERR, 'Unable to open ' . $args['output'] . ' for writing');
		exit(1);
	}
	
	fwrite($fp, $data);
	fclose($fp);
	echo 'Wrote data to ' . $args['output'] . ' (' . strlen($data) . ' bytes)' . "\n";
	
	exit(0);

?>