<?php 

    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/auth/token
     * Method: GET
     */
    public function index_post()
    { 
        $input = (array) json_decode($this->input->raw_input_stream);
        if(!empty($input['email']) && !empty($input['password']))
        {
            $responseData = $this->checkLogin($input['email'],$input['password']);

            if(!empty($responseData['success']))
            {
                $tokenData = array();
                $tokenData['id'] = $responseData['details']['user_id'];
                //$tokenData['email'] = $responseData['details']['user_email']; 
                $tokenData['timestamp'] = now();
                $responseData['token'] = AUTHORIZATION::generateToken($tokenData);
            } 
        }
        else
        {
            $responseData["success"] =  0;
            $responseData["status"] =  "error";
            $responseData["msg"] =  "Please enter email and password.";
        }
        
        /*$tokenData = array();
        $tokenData['id'] = 4; //TODO: Replace with data for token
        $output['token'] = AUTHORIZATION::generateToken($tokenData);
        */
        $this->set_response($responseData, REST_Controller::HTTP_OK);
    }

	/**
     * URL: http://localhost/CodeIgniter-JWT-Sample/auth/token
     * Method: POST
     * Header Key: Authorization
     * Value: Auth token generated in GET call
    */
    
    public function token_post()
    {
        $headers = $this->input->request_headers();
        print_r( $headers);

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                $this->set_response($decodedToken, REST_Controller::HTTP_OK);
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
?>