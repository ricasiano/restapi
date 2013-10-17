<?php
namespace RESTAPI\libraries;
class Response_generator {
    /**
     * generates a response to the requesting client based on the content type requested
     * 
     * @param array $data
     * @param string $resource
     */
    public function generate($data, $resource = '') {
        switch (RESPONSE_FORMAT) {
            case 'json':
                $this->json_format($data, $resource);
                break;
            default:
                $this->json_format($data, $resource);
                break;
        }
    }
    
    /**
     * response in json format
     * 
     * @param array $data
     * @param string $resource
     */
    private function json_format($data, $resource = '') {
        header('Content-Type: text/json');
        $ctr = 0;
        //generate a fully qualified url for reference to data
        if (count($data) > 1) {
            foreach ($data as $json_data) {
                $id = $json_data['id'];
                array_shift($data[$ctr]);
                $data[$ctr] = array('href' => SITEPATH.$resource.'/'.$id) + $data[$ctr];
                ++$ctr;
            }
        }
        else {
            unset($data[0]['id']);
        }
        $json_data = json_encode($data); 
        echo $json_data;
    }
}