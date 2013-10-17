<?php
namespace RESTAPI\libraries;
/**
 * Error Handling Class
 * 
 * Contains error codes and configurations as well as implementation of a standard error format
 * 
 */
class Errorhandling {
    public function __construct() {
        $codes = array();
        $http_error_codes = array();
        require(BASEPATH.'configs/error_codes.php');
        $this->codes = $codes;
        $this->http_error_codes = $http_error_codes;
                
    }
    //defines all error messages, documentation links and http error code
    private function error_codes($code) {
        $error_code = $this->codes[$code];
        return $error_code;
    }
    
    //generate the headers, depends if client supports it
    private function http_codes($code) {
        header("HTTP/1.0 ".$code." ".$this->http_error_codes[$code]['message']);
    }

    //generates an error in JSON format
    public function generate_error($code) {
        $error_detail = $this->error_codes($code);
        $error[] = array ('Error' =>
            array(
                'code' => $code,
                'message' => $error_detail['message'],
                'documentation' => $error_detail['documentation']
            )
        );
        if (SUPPRESS_RESPONSE_CODES == false)
        $this->http_codes($error_detail['http']);
        $this->generate_output($error);
    }
    //custom hack if you don't want to use the error codes
    public function custom_error($message, $documentation) {
        $error[] = array ('Error' =>
                array(
                    'message' => $message,
                    'documentation' => $documentation
                )
            );
        $this->generate_output($error);
    }

    //this includes selection of error format, json for now
    private function generate_output($error) {
        switch (RESPONSE_FORMAT) {
            case 'json':
                $response_error = json_encode($error);
                break;
            default:
                $response_error = json_encode($error);
                break;
        }
        echo $response_error;
        exit;
    }
}
