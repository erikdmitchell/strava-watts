<?php

class STWATT_Example_Request extends WP_Async_Request {

    /**
     * @var string
     */
    protected $action = 'stwatt_example_request';

    /**
     * Handle
     *
     * Override this method to perform any actions required during the async request.
     */
    protected function handle() {
        stwatt_log('stwatt_example_request');
        stwatt_log($_POST);
    }

}
