<?php
    $developer = 'Nicolas Bevacqua';
    $color = isset($_GET['color']) ? '#' . $_GET['color'] : '#C00';

    abstract class Reader {
        abstract public function __construct($url);
        abstract public function getData(&$data);
    }
    
    class CSVReader extends Reader {
        public $csv_file;
        
        public function __construct($url) {
            $this->csv_file = fopen($url, 'r');
            return $this->csv_file;
        }
        
        public function getData(&$data) {
            $data = array();
            
            while ($line = fgets($this->csv_file)) {
                $data []= explode(',', str_replace("\r\n", '', $line));
            }
        }
    }
    
    class JSONReader extends Reader {
        public $json;
        
        public function __construct($url) {
            $this->json = file_get_contents($url);
            return $this->json;
        }
        
        public function getData(&$data) {
            $data = json_decode($this->json, true);
        }
    }
?>
<html>
    <head>
        <script src="http://code.jquery.com/jquery-1.4.2.js"></script>
        <script src="https://raw.github.com/timrwood/moment/2.0.0/min/moment.min.js"></script>
        <script src="chart.js"></script>
        <style>
            #chart {
                border-bottom: 1px #000 solid;
                border-left: 1px #000 solid;
                height: 200px;
                margin-top: 15px;
                text-align: center;
            }
            
            #chart .value {
                background-color: #f00;
                background: -webkit-gradient(linear, left top, left bottom, from(<?= $color ?>), to(#000));
                background: -moz-linear-gradient(top,  <?= $color ?>,  #000);
                color: #fff;
                display: inline-block;
                vertical-align: bottom;
            }
        </style>
    </head>

    <body>
        <div>
            Litres of coffee consumed per week by <span><?= $developer ?></span>
        </div>
        <div id="chart">
        <?php
            function endsWith($haystack, $needle){
                return substr($haystack, -strlen($needle)) === $needle;
            }

            $src = isset($_GET['src']) ? $_GET['src'] : 'http://st.deviantart.net/dt/exercise/data.csv';

            if(endsWith($src, '.csv')){
                $reader = @new CSVReader($src);
            }else{
                $reader = @new JSONReader($src); // default to JSON, if no CSV extension
            }

            $reader->getData($data);
            if (is_array($data)){
                foreach ($data as &$values) {
                    echo '<div class="value" timestamp="' . $values[0] . '" value="' . $values[1] . '"></div>' . "\r\n";
                }
            }else{
                echo '<p>Invalid data file</p>';
            }
        ?>
        </div>
    </body>
</html>
