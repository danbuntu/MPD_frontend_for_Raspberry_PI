<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dan
 * Date: 25/09/13
 * Time: 18:19
 * To change this template use File | Settings | File Templates.
 */
include('config.php');
include('mpd.class.php');
$myMpd = new mpd($mpdhost, $mpdport);
//echo 'ajax php';

//print_r($_POST);


//if ($_POST['type'] == 'getServers') {
//
//    $hostsArray = array();
//    foreach ($hosts as $key => $host) {
//        $hostsArray[] = array('name' => $key, 'hostip' => $host['hostip'], 'port' => $host['port']);
//    }
//
////    print_R($hostsArray);
//    $hostsArray = json_encode($hostsArray);
//    echo $hostsArray;
//
//}


if ($_POST['type'] == 'connectServer') {
//    include('mpd.class.php');
//    print_R($_POST);
    $myMpd = new mpd($_POST['ip'], $_POST['port']);

    echo json_encode($myMpd);
}

if ($_POST['type'] == 'volume') {
//    echo 'volup';

    $myMpd->SetVolume($_POST['vol']);
//    $myMpd->AdjustVolume(-20);


}

// reposnse the pollinng request and update the information
if ($_POST['type'] == 'poll') {

    $artist = $myMpd->playlist[$myMpd->current_track_id]['Artist'];
    $title = $myMpd->playlist[$myMpd->current_track_id]['Title'];
    $album = $myMpd->playlist[$myMpd->current_track_id]['Album'];
    $position = $myMpd->current_track_position;
    $length = $myMpd->current_track_length;
    $positionPercentage = (round(($position / $length), 2) * 100);
    $state = $myMpd->state;
    $volume = $myMpd->volume;

    $playTrackPosition = $myMpd->current_track_id + 1;
    $playTrackCount = $myMpd->playlist_count;
    $playTrackPercentage = (round((($myMpd->current_track_id + 1) / $myMpd->playlist_count), 2) * 100);

    $filePath = explode("/", $myMpd->playlist[$myMpd->current_track_id]['file']);
    array_pop($filePath);
    $filePath = implode("/", $filePath);
    $cover = $filePath . '/' . $coverImage;

    $details = array('volume' => $volume, 'state' => $state, 'artist' => $artist, 'title' => $title, 'album' => $album, 'position' => $position, 'length' => $length, 'positionPercentage' => $positionPercentage, "cover" => urlencode($cover), 'listPosition' => $playTrackPosition, 'listCount' => $playTrackCount, 'listTrack' => $playTrackPercentage);

    $details = json_encode($details);
    echo $details;
}

// player controls
if ($_POST['type'] == 'control') {

    $type = $_POST['controlType'];

    if ($type == 'play') {
        $myMpd->Play();
    } elseif ($type == 'stop') {
        $myMpd->Stop();
    } elseif ($type == 'pause') {
        $myMpd->Pause();
    } elseif ($type == 'previous') {
        $myMpd->Previous();
    } elseif ($type == 'next') {
        $myMpd->Next();
    } elseif ($type == 'cover') {
        // get current play state
        $state = $myMpd->state;
        echo 'state is; ' ,  $state;
//        MPD_STATE_PLAYING, MPD_STATE_STOPPED, MPD_STATE_PAUSED
        if ($state == 'play') {
            $myMpd->Pause();
        } elseif (($state == 'stop') || ($state == 'pause')) {
            $myMpd->Play();
        }

    }

}


if ($_POST['type'] == 'playlistget') {
//    $count = $myMpd->playlist_count;


    $lists = $myMpd->playlist;
//    print_R($list);

    // get the currenlty playing track id
    $currentTrack = $myMpd->current_track_id;
//    echo 'current track' , $currentTrack;
    $finalList = array();
    foreach ($lists as $list) {
        if ($list['Pos'] == $currentTrack) {$active = 'playingItem';} else {$active = '';};


        $finalList[] = array('Pos' => $list['Pos'],'Artist' => $list['Artist'], 'Album' => $list['Album'], 'Title' => $list['Title'], 'Active' => $active);
    }

//    print_r($finalList);

    $list = json_encode($finalList);
    echo $list;
}


if ($_POST['type'] == 'removeItem') {
//    $count = $myMpd->playlist_count;

//    print_R($_POST);
    $myMpd->PLRemove($_POST['id']);

}


if ($_POST['type'] == 'addRadio') {
//    $count = $myMpd->playlist_count;

//    print_R($_POST);
    $myMpd->PLLoad($_POST['id']);


}


if ($_POST['type'] == 'addItem') {
//    $count = $myMpd->playlist_count;

//    print_R($_POST);

    if ($_POST['bulk'] == 'no') {
        $myMpd->PLAdd($_POST['id']);
    } else {
//        echo 'bulk add';

        $items = explode(",", $_POST['id']);
        $myMpd->PLAddBulk($items);
    }

}


if ($_POST['type'] == 'addRadio') {
//    $count = $myMpd->playlist_count;

//    print_R($_POST);

        $myMpd->PLLoad($_POST['id']);

}


if ($_POST['type'] == 'lettersearch') {
//    $count = $myMpd->playlist_count;
//    MPD_SEARCH_ARTIST, MPD_SEARCH_TITLE, MPD_SEARCH_ALBUM


//    print_R($_POST);
    if ($_POST['letter'] == 'letter') {
//        echo ' letter ' , $_POST['id'];
        $results = $myMpd->Search(MPD_SEARCH_ARTIST, $_POST['id']);
//        print_r($results);
        $resultsArray = array();
        // search the results for those artists that start with the letter
        foreach ($results as $result) {
//            echo 'artist: ' , substr($result['Artist'],0,1);
            if (substr($result['Artist'], 0, 1) == $_POST['id']) {

                $resultsArray[] = array('result' => $result['Artist'] . ' - ' . $result['Album'] . ' - ' . $result['Title'], 'filename' => $result['file']);

            }
        }

    }

    $resultsArray = json_encode($resultsArray);
    echo $resultsArray;

}

if ($_POST['type'] == 'search') {
//    $count = $myMpd->playlist_count;
//    MPD_SEARCH_ARTIST, MPD_SEARCH_TITLE, MPD_SEARCH_ALBUM


//print_R($_POST['']);
    if ($_POST['letter'] == 'letter') {
        $results = $myMpd->Search(MPD_SEARCH_ARTIST, $_POST['id']);
        print_r($results);
        $resultsArray = array();
        // search the results for those artists that start with the letter
        foreach ($results as $result) {
            if (substr($result['Artist'], 0, 1) == $_POST['id']) {
                $resultsArray[] = array('result' => $result['Artist'] . ' - ' . $result['Album'] . ' - ' . $result['Title'], 'filename' => $result['file']);

            }
        }

    }

    $text = $_POST['search'];
    $searchstring = '';
    if ($_POST['artist'] == 'true') {
        $results = $myMpd->Search(MPD_SEARCH_ARTIST, $_POST['search']);
//        print_R($results);

//        $resultsArray = array();
        $artist = '';
        $html = '';
        foreach ($results as $result) {
            if ($artist != $result['Artist']) {
                $html .= '<button class=" btn btn-default searchButtonArtist">' . $result['Artist'] . '</button> ';
//            $resultsArray[] = array('result' => $result['Artist']);
                $artist = $result['Artist'];
            }
        }
        echo $html;
    }
    if ($_POST['album'] == 'true') {

        $results = $myMpd->Search(MPD_SEARCH_ALBUM, $text);
        $blank = array('Album' => 'blank');
// add a blank record to the end of the results to make it rollover
        $results[] = ($blank);
//       $results = array_merge($blank , $results);

//        print_R($results);
        $resultsArray = array();
        $album = '';
//        $cover ='';
        $count = 0;
        foreach ($results as $result) {
            if ($album == $result['Album']) {
//               echo 'exists:' , $result['file'];

                // add the filename to the files string
                $files[] = $result['file'];
//               print_r($files);
            } else {
//               print_r($files);

                if ($count == 0) {
                    $count++;
                } else {
                    $resultsArray[] = array('result' => $albumArtist, 'filename' => $files, 'multiple' => 'yes');
                }

                // add the existing array to the results
                // start a new files string and set the album
                $files = array();
                $albumArtist = $result['Artist'] . ' - ' . $result['Album'];
                $album = $result['Album'];
//               $cover =
                $files[] = $result['file'];
            }
        }
        $resultsArray = json_encode($resultsArray);
        echo $resultsArray;
    }
    if ($_POST['track'] == 'true') {
        $results = $myMpd->Search(MPD_SEARCH_TITLE, $text);

        $resultsArray = array();
        foreach ($results as $result) {
            $resultsArray[] = array('result' => $result['Artist'] . ' - ' . $result['Album'] . ' - ' . $result['Title'], 'filename' => $result['file']);
        }
        $resultsArray = json_encode($resultsArray);
        echo $resultsArray;
    }
}


if ($_POST['type'] == 'bulksearchArtists') {

    $resultsArray = $myMpd->GetArtists();

    sort($resultsArray);

    $html = '';

    for ($i = 65; $i <= 90; $i++) {
        $html .= '<br><h4><a class="btn btn-letter lightGreen" id="' . chr($i) . '">' . chr($i) . ' </a><a class="btn pull-right lightGreen" href="#top"><span class="glyphicon glyphicon-circle-arrow-up"></span> Back to top</a></h4>';
        foreach ($resultsArray as $result) {
            if ($result[0] == chr($i)) {
                $html .= '<button class=" btn btn-default searchButtonArtist">' . $result . '</button> ';
            }
        }
    }

    echo $html;
//    $resultsArray = json_encode($resultsArray);
//    echo $resultsArray;
}

if ($_POST['type'] == 'bulksearchAlbums') {

    $resultsArray = $myMpd->GetAlbums();

    sort($resultsArray);

    $html = '';

    for ($i = 65; $i <= 90; $i++) {
        $html .= '<br><h4><a class="btn  btn-letter lightGreen" "id="' . chr($i) . '">' . chr($i) . ' </a><a class="btn lightGreen pull-right" href="#top"><span class="glyphicon glyphicon-circle-arrow-up"></span> Back to top</a></h4>';
        foreach ($resultsArray as $result) {
            if ($result[0] == chr($i)) {

//                // get the dirrectory for the album
//                $dir = $mpdObject->GetDir($result);


                $html .= '<button class=" btn btn-default searchButtonAlbum">' . $dir . $result . '</button> ';
            }
        }
    }

    echo $html;
//    $resultsArray = json_encode($resultsArray);
//    echo $resultsArray;
}


if ($_POST['type'] == 'bulksearchAlbums') {

    $resultsArray = $myMpd->GetAlbums();

    sort($resultsArray);
    $resultsArray = json_encode($resultsArray);
    echo $resultsArray;
}


if ($_POST['type'] == 'bulksearchAlbumsByArtist') {


//    print_R($_POST);
    $resultsArray = $myMpd->GetAlbums($_POST['artist']);

    sort($resultsArray);
    $resultsArray = json_encode($resultsArray);
    echo $resultsArray;
}


if ($_POST['type'] == 'getAndAddAllAlbumTracks') {

    $album = $_POST['album'];
    $results = $myMpd->Find(MPD_SEARCH_ALBUM, $album);
    $blank = array('Album' => 'blank');

    //    print_R($results);
    $filesList = array();
    foreach ($results as $result) {
        $filesList[] = $result['file'];
    }

    $myMpd->PLAddBulk($filesList);
}


if ($_POST['type'] == 'listcontrol') {

    if ($_POST['controlType'] == 'clear') {
        $myMpd->PLClear();
        $message = 'Playlist Cleared';
    } elseif ($_POST['controlType'] == 'shuffle') {
        $myMpd->PLShuffle();
        $message = 'Playlist Shuffled';
    } elseif ($_POST['controlType'] == 'refresh') {
        $myMpd->DBRefresh();
        $message = 'Database Refreshing';
    } elseif ($_POST['controlType'] == 'info') {
        $info = $myMpd->RefreshInfo();
        $message = $info;
    }

    echo $message;

}