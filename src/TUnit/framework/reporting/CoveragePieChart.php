<?php

	class CoveragePalette extends ezcGraphPalette {
		protected $elementBorderWidth = 0;
		protected $fontName           = 'Verdana';
		protected $fontColor          = '#000000';
		protected $chartBackground    = '#FFFFFF';
	    protected $dataSetSymbol      = array(ezcGraph::BULLET);
	    protected $padding            = 1;
	    protected $margin             = 1;
		protected $dataSetColor       = array(
			'#009900',  //covered
			'#990000',  //uncovered
			'#999999'   //dead
		);
	}

	class CoverageDriver extends ezcGraphGdDriver {
		
		public function getDataUri() {
			ob_start();
			$this->render(null);
			$data = ob_get_clean();
			
			return 'data:image/png;base64,' . base64_encode($data);
		}
		
	}
	
	class CoverageRenderer extends ezcGraphRenderer3d {
		
		public function renderToDataUri() {
			$this->finish();
			$data = $this->driver->getDataUri();
			$this->resetRenderer();
			return $data;
		}
		
	}

	class CoveragePieChart extends ezcGraphPieChart {
		
		public function __construct() {
			parent::__construct();
			
			$this->palette = new CoveragePalette();
			
			$this->options->font = dirname(__FILE__) . '/data/consolas.ttf';
			$this->options->font->maxFontSize = 10;
			$this->options->label             = '%2$s (%3$.1f%%)';
			$this->legend->position           = ezcGraph::BOTTOM;
			
			$this->renderer = new CoverageRenderer();
			
			$this->renderer->options->pieChartRotation = .6;
			$this->renderer->options->pieChartShadowTransparency = .7;
			$this->renderer->options->pieChartShadowSize = 10;
			
			$this->renderer->options->pieChartShadowColor = '#666666';
			
			$this->renderer->options->moveOut = .2;
			$this->renderer->options->pieChartOffset = 45;
			$this->renderer->options->pieChartGleam = .1;
			$this->renderer->options->pieChartGleamColor = '#000000';
			$this->renderer->options->pieChartGleamBorder = 1;
			
			$this->renderer->options->legendSymbolGleam = .4;
			$this->renderer->options->legendSymbolGleamSize = .9;
			$this->renderer->options->legendSymbolGleamColor = '#FFFFFF';
			
			$this->renderer->options->symbolSize = 3.0;
			$this->renderer->options->showSymbol = true;
			$this->renderer->options->pieChartSymbolColor = '#66666666';
			//$this->options->labelCallback = 'label_callback';
			
			$this->driver = new CoverageDriver();
			$this->driver->options->supersampling = 1;
			$this->driver->options->imageFormat = IMG_PNG;
			
			$this->title = 'Code Coverage';
			$this->title->font->maxFontSize = 15;
			$this->title->font->textShadow = true;
			$this->title->font->textShadowOffset = 1;
			$this->title->font->textShadowColor = '#CCCCCC';
		}
		
		public function renderToDataUri($width, $height, $uloc, $dloc, $cloc) {
			
			$this->data['Code Coverage'] = new ezcGraphArrayDataSet(
				array(
					'Covered'   => $cloc,
					'Uncovered' => $uloc,
					'Dead'      => $dloc
				)
			);
			
			$this->data['Code Coverage']->highlight['Covered'] = true;
			
			$this->renderElements($width, $height);
			return $this->renderer->renderToDataUri();
		}
		
	}

?>