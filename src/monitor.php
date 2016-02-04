<?php

function cronitorMonitorTask($client, $closure, $exceptionHandler = false) {
	try {

        $client->run();
        $closure();
		$client->complete();

	} catch (Exception $e) {

		$pause = false;

        if(!$exceptionHandler){
            $msg = $e->getMessage();
		} else {
			// $exceptionHandler should return an array like the following:
			// array(
			//     'msg'   => (string) 'Some string that will act as an error message',
			//     'pause' => (int) The number of hours to pause this monitor for
			// )
            extract( $exceptionHandler($e, $client) );
        }

		$client->fail($msg);

        if($pause){
            $client->pause((int) $pause);
		}

    }
}
