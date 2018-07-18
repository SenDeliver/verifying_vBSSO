<?php

class CheckingVBSSO
{
    private $cURL;
    private $platform = [
        'vbulletin' => '/vbsso/vbsso.php?a=act',
        'magento' => '{sufix}magento',
        'wordpress' => '{sufix}wp',
    ];
    private $log = [];
    private $do_i_need_to_write_to_a_file = true;

    private function debug($obj)
    {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }


    private function displayAddress()
    {
        echo "Check address: " . $this->cURL . "<br>";
    }

    public function checkURI()
    {
        if (!empty($_GET['URI']) && !empty($_GET['platform'])){
            foreach ($this->platform as $key => $value){
                if ($_GET['platform'] == $key){
                    $this->log['uri'] = $this->cURL = $_GET['URI'] . $value;
                    $this->log['platform'] = $key;
                    $this->displayAddress();
                    $this->creatingNewCurlResource();
                }
            }
        }
    }

    private function entryToFile()
    {
        $year = date("Y");
        $month = date('m');
        $day = date('d');

        $path = __DIR__ . "/logs/vbsso_verify_$year$month$day.csv";
        file_put_contents($path, $this->log, FILE_APPEND);
    }

    private function mergingJSON($log, $exec)
    {
        $this->log = json_encode(array_merge(json_decode($log, true),json_decode($exec, true)));
        //$this->debug($this->log);
    }

    private function creatingNewCurlResource()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->cURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $exec = curl_exec($ch);

        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            $this->log['http_code'] = $info['http_code'];
            if ($info['http_code'] == 200){
                if ($exec == ''){
                    echo "vBSSO is installed bu probably corrupted we are unable to verify it";
                }
                echo '<h5>Successful request' . "<br>" . 'JSON answer: </h5>';

                $this->log = json_encode($this->log);
                $this->mergingJSON($this->log, $exec);

                $exec = json_decode($exec);
                $this->debug($exec);

                if ($this->do_i_need_to_write_to_a_file){
                    $this->entryToFile();
                }
            }else{
                echo "<h4>vBSSO is not installed there and unable to verify the url</h4> answ HHTP cod = " . $info['http_code'];
            }
        }else{
            echo "Error id:" . curl_errno($ch);
        }

        curl_close($ch);
    }

}
