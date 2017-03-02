<?php 
	require 'aws-autoloader.php';
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	};
	$caller = generateRandomString(16);
	$cloudFront = new Aws\CloudFront\CloudFrontClient([
	    'version'     => 'latest',
	    'region'      => 'us-east-1',
	    'credentials' => [
	        'key'    => 'AKIAJ46JNSRJRVNLDOKA',
	        'secret' => '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'
	    ]
	]);
	$result = $cloudFront->createInvalidation([
	    'DistributionId' => 'E106VWIPUZ2Z49', // REQUIRED
	    'InvalidationBatch' => [ // REQUIRED
	        'CallerReference' => $caller, // REQUIRED
	        'Paths' => [ // REQUIRED
	            'Items' => ['/'], // items or paths to invalidate
	            'Quantity' => 1 // REQUIRED (must be equal to the number of 'Items' in the previus line)
	        ]
	    ]
	]);
	//echo $result; if you want to print the output of the request
?>
