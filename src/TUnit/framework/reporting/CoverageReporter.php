<?php

	class CoverageReporter {
		
		const UNUSED       = -1;
		const DEAD         = -2;
		
		const TEMPLATE_DIR = 'template';
		
		private function __construct() {}
		
		public static function createConsoleReport(array $coverageData) {
			$coverageData = CoverageFilter::filter($coverageData);
			fwrite(STDOUT, "\nCode coverage information:\n\n");
			
			$totloc  = 0;
			$totdloc = 0;
			$totcloc = 0;
			foreach ($coverageData as $file => $data) {
				fwrite(STDOUT, $file . "\n");
				$loc  = 0;
				$dloc = 0;
				$cloc = 0;
				
				foreach ($data as $line => $unitsCovered) {
					$loc++;
					if ($unitsCovered > 0) {
						$cloc++;
					} else if ($unitsCovered === self::DEAD) {
						$dloc++;
					}
				}
				
				fwrite(STDOUT, "  Covered:    $cloc\n");
				fwrite(STDOUT, "  Dead:       $dloc\n");
				fwrite(STDOUT, "  Executable: $loc (" . round($cloc / $loc * 100, 2) . "%)\n");
				
				$totloc  += $loc;
				$totdloc += $dloc;
				$totcloc += $cloc;
			}
			
			fwrite(STDOUT, "\n\n");
			fwrite(STDOUT, "Totals:\n");
			fwrite(STDOUT, "  Covered:    $totcloc\n");
			fwrite(STDOUT, "  Dead:       $totdloc\n");
			fwrite(STDOUT, "  Executable: $totloc (" . round($totcloc / $totloc * 100, 2) . "%)\n");
		}
		
		public static function createHtmlReport($dir, array $coverageData) {
			if (!is_dir($dir)) {
				throw new TUnitException($dir . ' is not a directory');
			}
			
			$coverageData = CoverageFilter::filter($coverageData);
			
			$baseDir = array();
			foreach ($coverageData as $file => $data) {
				$dirs = explode(DIRECTORY_SEPARATOR, dirname($file));
				if (empty($baseDir)) {
					$baseDir = $dirs;
				} else {
					for ($i = 0, $len = count($dirs); $i < $len; $i++) {
						if (!isset($baseDir[$i]) || $baseDir[$i] !== $dirs[$i]) {
							break;
						}
					}
					
					$baseDir = array_slice($dirs, 0, $i);
				}
			}
			
			$baseDir = implode(DIRECTORY_SEPARATOR, $baseDir) . DIRECTORY_SEPARATOR;
			
			$totalData = array();
			foreach ($coverageData as $file => $data) {
				$totalData[$file] = array(
					'loc'  => 0,
					'dloc' => 0,
					'cloc' => 0
				);
				
				foreach ($data as $line => $unitsCovered) {
					$totalData[$file]['loc']++;
					if ($unitsCovered > 0) {
						$totalData[$file]['cloc']++;
					} else if ($unitsCovered === self::DEAD) {
						$totalData[$file]['dloc']++;
					}
				}
				
				self::writeHtmlFile($file, $baseDir, $dir, $data);
			}
			
			//copy css over
			$template = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TEMPLATE_DIR . DIRECTORY_SEPARATOR;
			copy($template . 'style.css', $dir . DIRECTORY_SEPARATOR . 'style.css');
		}
		
		private static function writeHtmlFile($sourceFile, $baseDir, $coverageDir, array $data) {
			$lines = file($sourceFile, FILE_IGNORE_NEW_LINES);
			$code = '';
			$lineNumbers = '';
			for ($i = 1, $len = count($lines); $i <= $len; $i++) {
				$lineNumbers .= '<div><a href="#line-' . $i . '">' . $i . '</a></div>';
				$code .= '<div';
				if (isset($data[$i])) {
					$code .= ' class="';
					if ($data[$i] > 0) {
						$code .= 'covered';
					} else if ($data[$i] === self::DEAD) {
						$code .= 'dead';
					} else if ($data[$i] === self::UNUSED) {
						$code .= 'uncovered';
					}
					
					$code .= '">';
				} else {
					$code .=  '>';
				}
				
				if (empty($lines[$i])) {
					$lines[$i] = ' ';
				}
				
				$code .= htmlentities(str_replace("\t", '    ', $lines[$i - 1]), ENT_QUOTES) ."</div>\n";
			}
			
			unset($lines);
			
			$fileName = str_replace(array($baseDir, DIRECTORY_SEPARATOR), array('', '_'), $sourceFile);
			$newFile = $coverageDir . DIRECTORY_SEPARATOR . $fileName . '.html';
			
			$link = '<a href="./index.html">' . $baseDir . '</a>';
			$dirs = preg_split('@\\' . DIRECTORY_SEPARATOR . '@', str_replace($baseDir, '', dirname($sourceFile) . DIRECTORY_SEPARATOR), -1, PREG_SPLIT_NO_EMPTY);
			$path = '';
			foreach ($dirs as $dir) {
				$path = ltrim($path . '_' . $dir, '_');
				$link .= '<a href="./' . $path . '.html">' . $dir . '</a>' . DIRECTORY_SEPARATOR;
			}
			
			$template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'file.html');
			$template = preg_replace(
				array(
					'/\$\{title\}/',
					'/\$\{file\.name\}/',
					'/\$\{line.numbers\}/',
					'/\$\{code\}/',
					'/\$\{timestamp\}/',
					'/\$\{product\.name\}/',	
					'/\$\{product\.version\}/',
					'/\$\{product\.website\}/',
					'/\$\{product\.author\}/'
				),
				array(
					Product::NAME . ' - Coverage Report',
					$link . basename($sourceFile),
					$lineNumbers,
					$code,
					date('Y-m-d H:i:s'),
					Product::NAME,
					Product::VERSION,
					Product::WEBSITE,
					Product::AUTHOR
				),
				$template
			);
			
			return file_put_contents($newFile, $template);
		}
		
		private static function writeHtmlDir(array $data) {
			
		}
		
	}

?>