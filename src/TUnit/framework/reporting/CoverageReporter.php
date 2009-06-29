<?php

	class CoverageReporter {
		
		const UNUSED       = -1;
		const DEAD         = -2;
		
		const TEMPLATE_DIR = 'template';
		
		private function __construct() {}
		
		private static function parseCoverageData(array $coverageData) {
			$newData = array();
			foreach ($coverageData as $file => $data) {
				$classes = Util::getClassNamesFromFile($file);
				//print_r($classes);
				$refClasses = array();
				
				foreach ($classes as $class) {
					$refClasses[] = new ReflectionClass($class);
				}
				
				$newData[$file] = array();
				foreach ($data as $line => $unitsCovered) {
					$loc  = 1;
					$dloc = ($unitsCovered === self::DEAD) ? 1 : 0;
					$cloc = ($unitsCovered > 0)            ? 1 : 0;
					
					//find the class this line resides in, if any
					$class = null;
					foreach ($refClasses as $refClass) {
						if ($line > $refClass->getStartLine() && $line <= $refClass->getEndLine()) {
							$class = $refClass;
							//find the method this line resides in
							foreach ($class->getMethods() as $refMethod) {
								if ($line >= $refMethod->getStartLine() && $line <= $refMethod->getEndLine()) {
									
									if (isset($newData[$file]['classes'][$class->getName()][$refMethod->getName()])) {
										$newData[$file]['classes'][$class->getName()][$refMethod->getName()]['loc']  += $loc;
										$newData[$file]['classes'][$class->getName()][$refMethod->getName()]['dloc'] += $dloc;
										$newData[$file]['classes'][$class->getName()][$refMethod->getName()]['cloc'] += $cloc;
									} else {
										$newData[$file]['classes'][$class->getName()][$refMethod->getName()] = array(
											'loc'  => $loc,
											'dloc' => $dloc,
											'cloc' => $cloc
										);
									}
									break;
								}
							}
							break;
						}
					}
					
					//procedural code
					if ($class === null) {
						if (isset($newData[$file]['procedural'])) {
							$newData[$file]['procedural']['loc']  += $loc;
							$newData[$file]['procedural']['dloc'] += $dloc;
							$newData[$file]['procedural']['cloc'] += $cloc;
						} else {
							$newData[$file]['procedural'] = array(
								'loc'  => $loc,
								'dloc' => $dloc,
								'cloc' => $cloc
							);
						}
					}
				}
			}
			
			return $newData;
		}
		
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
				fwrite(STDOUT, "  Executable: " . ($loc - $dloc) . " (" . round($cloc / ($loc - $dloc) * 100, 2) . "%)\n");
				
				$totloc  += $loc;
				$totdloc += $dloc;
				$totcloc += $cloc;
			}
			
			fwrite(STDOUT, "\n\n");
			fwrite(STDOUT, "Totals:\n");
			fwrite(STDOUT, "  Covered:    $totcloc\n");
			fwrite(STDOUT, "  Dead:       $totdloc\n");
			fwrite(STDOUT, "  Executable: " . ($totloc - $totdloc) . " (" . round($totcloc / ($totloc - $dloc) * 100, 2) . "%)\n");
		}
		
		public static function createHtmlReport($coverageDir, array $coverageData) {
			$coverageData = CoverageFilter::filter($coverageData);
			
			$baseDir = array();
			$dirData = array();
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
			
			$classData = self::parseCoverageData($coverageData);
			
			foreach ($coverageData as $file => $data) {
				self::writeHtmlFile($file, $baseDir, $coverageDir, $classData[$file], $data);
			}
			
			self::writeHtmlDirectories($coverageDir, $baseDir, $coverageData);
			
			//copy css over
			$template = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TEMPLATE_DIR . DIRECTORY_SEPARATOR;
			copy($template . 'style.css', $coverageDir . DIRECTORY_SEPARATOR . 'style.css');
		}
		
		private static function writeHtmlFile($sourceFile, $baseDir, $coverageDir, array $classData, array $coverageData) {
			//summary view
			$fileCoverage = '';
			$classCoverage = '';
			
			$tloc  = 0;
			$tdloc = 0;
			$tcloc = 0;
			foreach ($classData['classes'] as $class => $methods) {
				$classCoverage = '';
				$classLoc  = 0;
				$classDloc = 0;
				$classCloc = 0;
				$methodCoverage = '';
				$refClass = new ReflectionClass($class);
				foreach ($methods as $method => $methodData) {
					$tloc  += $methodData['loc'];
					$tdloc += $methodData['dloc'];
					$tcloc += $methodData['cloc'];
					
					$methodStartLine = $refClass->getMethod($method)->getStartLine();
					$methodCoverage .= "<tr class=\"method-coverage\"><th>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"#line-$methodStartLine\">$method</a></th>";
					$methodCoverage .= "<td>$methodData[cloc] / " . ($methodData['loc'] - $methodData['dloc']) . "</td>";
					$methodCoverage .= "<td>" . round($methodData['cloc'] / ($methodData['loc'] - $methodData['dloc']) * 100, 2) . "%</td></tr>\n";
					
					$classLoc  += $methodData['loc'];
					$classDloc += $methodData['dloc'];
					$classCloc += $methodData['cloc'];
				}
				
				$classStartLine = $refClass->getStartLine();
				$classCoverage .= "<tr class=\"class-coverage\"><th>&nbsp;&nbsp;<a href=\"#line-$classStartLine\">$class</a></th><td>$classCloc / " . ($classLoc - $classDloc) . "</td>";
				$classCoverage .= "<td>" . round($classCloc / ($classLoc - $classDloc) * 100, 2) . "%</td></tr>\n";
				$classCoverage .= $methodCoverage;
			}
			
			$tloc += $classData['procedural']['loc'];
			$tdloc += $classData['procedural']['dloc'];
			$tcloc += $classData['procedural']['cloc'];
			
			$fileCoveragePercent = round($tcloc / max($tloc - $tdloc, 1) * 100, 2);
			$fileCoverage = "<tr class=\"file-coverage\"><th>$sourceFile</th><td>$tcloc / " . ($tloc - $tdloc) . "</td><td>$fileCoveragePercent%</td></tr>\n";
			$fileCoverage .= $classCoverage;
			unset($classCoverage, $methodCoverage, $classData, $refClass);
			
			//code view
			$lines       = file($sourceFile, FILE_IGNORE_NEW_LINES);
			$code        = '';
			$lineNumbers = '';
			
			for ($i = 1, $len = count($lines); $i <= $len; $i++) {
				$lineNumbers .= '<div><a name="#line-' . $i . '" href="#line-' . $i . '">' . $i . '</a></div>';
				$code .= '<div';
				if (isset($coverageData[$i])) {
					$code .= ' class="';
					if ($coverageData[$i] > 0) {
						$code .= 'covered';
					} else if ($coverageData[$i] === self::DEAD) {
						$code .= 'dead';
					} else if ($coverageData[$i] === self::UNUSED) {
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
			
			$fileName = '-' . str_replace(array($baseDir, DIRECTORY_SEPARATOR), array('', '-'), $sourceFile);
			$newFile = $coverageDir . DIRECTORY_SEPARATOR . $fileName . '.html';
			
			$link = self::buildLink($baseDir, $sourceFile);
			
			$template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'file.html');
			$template = str_replace(
				array(
					'${title}',
					'${file.link}',
					'${file.coverage}',
					'${line.numbers}',
					'${code}',
					'${timestamp}',
					'${product.name}',	
					'${product.version}',
					'${product.website}',
					'${product.author}'
				),
				array(
					Product::NAME . ' - Coverage Report',
					$link . basename($sourceFile),
					$fileCoverage,
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
		
		private static function writeHtmlDirectories($coverageDir, $baseDir, array $coverageData) {
			$dirData = array();
			foreach ($coverageData as $file => $data) {
				$dirs = preg_split('@\\' . DIRECTORY_SEPARATOR . '@', str_replace($baseDir, '', dirname($file) . DIRECTORY_SEPARATOR), -1, PREG_SPLIT_NO_EMPTY);
				if (empty($dirs)) {
					continue;
				}
				
				$loc  = count($data);
				$dloc = array_reduce($data, 'CoverageReporter::getDeadLoc',    0);
				$cloc = array_reduce($data, 'CoverageReporter::getCoveredLoc', 0);
				
				$index = '';
				foreach ($dirs as $dir) {
					$index .= DIRECTORY_SEPARATOR . $dir;
					if (!isset($dirData[$index])) {
						$dirData[$index] = array(
							'loc'  => $loc,
							'dloc' => $dloc,
							'cloc' => $cloc,
							'files' => array()
						);
					} else {
						$dirData[$index]['loc']  += $loc;
						$dirData[$index]['dloc'] += $dloc;
						$dirData[$index]['cloc'] += $cloc;
					}
				}
				
				$dirData[DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $dirs)]['files'][$file] = array(
					'loc'  => $loc,
					'dloc' => $dloc,
					'cloc' => $cloc
				);
			}
			
			$template = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'directory.html';
			$template = file_get_contents($template);
			
			foreach ($dirData as $dir => $data) {
				$info = '';
				$link = '';
				$subdirs = array();
				foreach ($dirData as $dir2 => $data2) {
					if (substr($dir2, 0, strrpos($dir2, DIRECTORY_SEPARATOR)) === $dir) {
						//this is a direct subdirectory
						$subdirs[] = $dir2;
					}
				}
				
				sort($subdirs);
				
				//create directory info
				foreach ($subdirs as $subdir) {
					$subdata = $dirData[$subdir];
					
					$info .= '<tr><th><a href="' . self::buildLink($baseDir, $subdir . DIRECTORY_SEPARATOR . 'foo', true) . '.html">' . basename($subdir) . '</a></th>';
					$info .= '<td>' . $subdata['cloc'] . ' / ' . ($subdata['loc'] - $subdata['dloc']) . '</td>';
					$info .= '<td>' . round($subdata['cloc'] / ($subdata['loc'] - $subdata['dloc']) * 100, 2) . '%</td>';
					$info .= "</tr>\n";
				}
				
				//regular files in current directory
				foreach ($data['files'] as $file => $fileData) {
					$info .= '<tr><th><a href="' . self::buildLink($baseDir, $file, true) . '-' . basename($file) . '.html">' . basename($file) . '</a></th>';
					$info .= '<td>' . $fileData['cloc'] . ' / ' . ($fileData['loc'] - $fileData['dloc']) . '</td>';
					$info .= '<td>' . round($fileData['cloc'] / ($fileData['loc'] - $fileData['dloc']) * 100, 2) . '%</td>';
					$info .= "</tr>\n";
				}
				
				$temp = str_replace(
					array(
						'${title}',
						'${file.link}',
						'${directory.coverage}',
						'${timestamp}',
						'${product.name}',	
						'${product.version}',
						'${product.website}',
						'${product.author}'
					),
					array(
						Product::NAME . ' - Coverage Report',
						self::buildLink($baseDir, $dir . DIRECTORY_SEPARATOR . 'foo'),
						$info,
						date('Y-m-d H:i:s'),
						Product::NAME,
						Product::VERSION,
						Product::WEBSITE,
						Product::AUTHOR
					),
					$template
				);
				
				$fileName = str_replace(DIRECTORY_SEPARATOR, '-', $dir) . '.html';
				file_put_contents($coverageDir . DIRECTORY_SEPARATOR . $fileName, $temp);
			}
		}
		
		private static function buildLink($baseDir, $path, $oneLink = false) {
			if ($oneLink) {
				$link = './';
			} else {
				$link = '<a href="./index.html">' . $baseDir . '</a>';
			}
			$dirs = preg_split('@\\' . DIRECTORY_SEPARATOR . '@', str_replace($baseDir, '', dirname($path) . DIRECTORY_SEPARATOR), -1, PREG_SPLIT_NO_EMPTY);
			$path = '';
			foreach ($dirs as $dir) {
				$path = $path . '-' . $dir;
				if ($oneLink) {
					$link = $path;
				} else {
					$link .= '<a href="./' . $path . '.html">' . $dir . DIRECTORY_SEPARATOR . '</a>';
				}
			}
			
			// print_r($dirs);
			// echo 'asdf: ' . $link . "\n\n";
			
			return $link;
		}
		
		private static function getDeadLoc($old, $new) {
			return $old + (($new === self::DEAD) ? 1 : 0);
		}
		
		private static function getCoveredLoc($old, $new) {
			return $old + (($new > 0) ? 1 : 0);
		}
		
	}

?>