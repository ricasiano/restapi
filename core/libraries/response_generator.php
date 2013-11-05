<?php
namespace RESTAPI\libraries;
class Response_generator {
    /**
     * generates a response to the requesting client based on the content type requested
     * 
     * @param array $data
     * @param string $resource
     */
    public function generate($data, $resource = '', $id = null) {
        switch (RESPONSE_FORMAT) {
            case 'json':
                $this->json_format($data, $resource, $id);
                break;
            default:
                $this->json_format($data, $resource, $id);
                break;
        }
    }
    
    /**
     * response in json format
     * 
     * @param array $data
     * @param string $resource
     */
    private function json_format($data, $resource = '', $id = null) {
        header('Content-Type: text/json');
        $ctr = 0;
        //generate a fully qualified url for reference to data
        if ($id == null) {
            foreach ($data as $json_data) {
                $id = $json_data['id'];
                array_shift($data[$ctr]);
                $data[$ctr] = array('href' => SITEPATH.$resource.'/'.$id) + $data[$ctr];
                ++$ctr;
            }
        }
        else {
        }
        echo json_encode($this->rename_keys($data)); 
    }
    
    /**
     * transforms keys from underscore to camel case
     * 
     * @param array $data
     * @return array
     */
    private function rename_keys($data) {
        $cleaned = array();
        foreach ($data as $keydata => $valdata) {
            $func = create_function('$c', 'return strtoupper($c[1]);');
            $validate = preg_replace_callback('/_([a-z])/', $func, $keydata);
            $rename = strtolower(substr($validate, 0, 1)) . substr($validate, 1);
            $cleaned[$rename] = is_array($valdata) ? $this->rename_keys($valdata) : $valdata;
        }
        return $cleaned;
    }
}