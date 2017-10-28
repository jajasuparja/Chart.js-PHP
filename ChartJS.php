<?php

class ChartJS
{
    /**
     * @var array chart data
     */
    protected $_datasets = array();

    /**
     * @var array chart labels
     */
    protected $_labels = array();

    /**
     * The chart type
     * @var string
     */
    protected $_type = '';

    /**
     * @var array Specific options for chart
     */
    protected $_options = array();

    /**
     * @var string Chartjs canvas' ID
     */
    protected $_id;

    /**
     * @var string Canvas width
     */
    protected $_width;

    /**
     * @var string Canvas height
     */
    protected $_height;

    /**
     * @var array Canvas attributes (class,
     */
    protected $_attributes = array();

    /**
     * Add a set of data
     * @param array $data
     * @param array $colors
     * @param string $legend
     * @param array $advanced
     * @param null $name Name can be use to change data later
     */
    public function addDataset($data = array(), $colors = array(), $legend = '', $advanced = array(), $name = null)
    {
        if (!$name) {
            $name = count($this->_datasets);
        }
        $this->_datasets[$name]['data'] = $data;
        $this->_datasets[$name]['colors'] = $colors;
        $this->_datasets[$name]['legend'] = $legend;
        $this->_datasets[$name]['advanced'] = $advanced;
    }

    public function __construct($type = 'line', $id = null, $width = '', $height = '', $labels = array(), $options = array(), $otherAttributes = array())
    {
        if (!$id) {
            $id = uniqid('chartjs_', true);
        }

	$this->_type = $type;
        $this->_id = $id;
        $this->_width = $width;
        $this->_height = $height;
        $this->_labels = $labels;
        $this->_options = $options;

        // Always save otherAttributes as array
        if ($otherAttributes && !is_array($otherAttributes)) {
            $otherAttributes = array($otherAttributes);
        }

        $this->_attributes = $otherAttributes;
    }

    /**
     * This method allows to echo ChartJS object and directly renders canvas (instead of using ChartJS->render())
     */
    public function __toString()
    {
        return $this->renderCanvas();
    }

    public function renderCanvas()
    {
        $data = $this->_renderData();
        $options = $this->_renderOptions();
        $height = $this->_renderHeight();
        $width = $this->_renderWidth();

        $attributes = $this->_renderAttributes();

        $canvas = '<canvas id="' . $this->_id . '" data-chartjs="' . $this->_type . '"' . $height . $width . $attributes . $data . $options . '></canvas>';

        return $canvas;
    }

    /**
     * Prepare canvas' attributes
     * @return string
     */
    protected function _renderAttributes()
    {
        $attributes = '';

        foreach ($this->_attributes as $attribute => $value) {
            $attributes .= ' ' . $attribute . '="' . $value . '"';
        }

        return $attributes;
    }

    /**
     * Prepare width attribute for canvas
     * @return string
     */
    protected function _renderWidth()
    {
        $width = '';

        if ($this->_width) {
            $width = ' width="' . $this->_width . '"';
        }

        return $width;
    }

    /**
     * Prepare height attribute for canvas
     * @return string
     */
    protected function _renderHeight()
    {
        $height = '';

        if ($this->_height) {
            $height = ' height="' . $this->_height . '"';
        }

        return $height;
    }

    /**
     * Render custom options for the chart
     * @return string
     */
    protected function _renderOptions()
    {
        if (empty($this->_options)) {
            return ' data-options=\'null\'';
        }
        return ' data-options=\'' . json_encode($this->_options) . '\'';
    }

    /**
     * Prepare data (labels and dataset) for the chart
     * @return string
     */
    protected function _renderData()
    {
        $array_data = array('labels' => $this->_labels, 'datasets' => array());
	$i = 0;
        foreach ($this->_datasets as $line) {
            $array_data['datasets'][] = array('label' => $line['legend']) +
					array('data' => $line['data']) +
					$line['colors'] +
					$line['advanced'];
	    $i++;
        }

        return ' data-data=\'' . json_encode($array_data) . '\'';
    }
}