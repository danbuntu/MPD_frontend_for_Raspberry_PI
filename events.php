<?php
	error_reporting(E_ALL && !E_NOTICE);

	require_once '../private/lib/mpd.class.php';
	require_once '../private/lib/mpdEventListener.class.php';
	
	$mpd = new mpd('lamandragora',6600);	
	
	function myNext($prev, $now) {
		$song = $now['file'];
		var_dump("ahora suena: $song");
	}
	
	function myStop($prev, $now) {
		global $mpd;
		
		var_dump("stop");
		$mpd->Play();
	}
	
	function myRepeat($prev, $now) {
		global $mpd;
		
		var_dump("cambiando repeticion: $prev -> $now");
		$mpd->SetRepeat(0);
	}
	
	function myShuffle($prev, $now) {
		global $mpd;
		
		var_dump("cambiando shuffle: $prev -> $now");
		$mpd->SetRandom(0);
	}
	
	
	function myCrossfade($prev, $now) {
		global $mpd;
		
		var_dump("cambiando crossfade: $prev -> $now");
		$mpd->SetCrossfade(5);
	}	
	
	function myList($prev, $now) {
		var_dump("cambiando lista:",$prev,$now);
	}
	
	function myOutput($prev, $now) {
		global $mpd;
		
		$str = "cambiando output ".$prev['outputname'].": ".$prev['outputenabled']." -> ".$now['outputenabled'];
		var_dump($str);
		if ($now['outputid'] == 3)
			$mpd->EnableOutput(3);
	}
	
	$listen = new mpdEventListener($mpd);
	$listen->bind(MPDEVENTLISTENER_ONSONGCHANGE,'myNext');
	$listen->bind(MPDEVENTLISTENER_ONSTOP,'myStop');
	$listen->bind(MPDEVENTLISTENER_ONREPEATCHANGE,'myRepeat');
	$listen->bind(MPDEVENTLISTENER_ONTIMECHANGE,'myTime');
	$listen->bind(MPDEVENTLISTENER_ONSHUFFLECHANGE,'myShuffle');
	$listen->bind(MPDEVENTLISTENER_ONVOLUMECHANGE,'myVolume');
	$listen->bind(MPDEVENTLISTENER_ONCROSSFADECHANGE,'myCrossfade');
	$listen->bind(MPDEVENTLISTENER_ONPLAYLISTCHANGE,'myList');
	$listen->bind(MPDEVENTLISTENER_ONOUTPUTCHANGE,'myOutput');
	$listen->startListening();
	
?>