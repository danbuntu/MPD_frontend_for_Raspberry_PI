<?php //if ($_POST['type'] == 'connectServer') {
//    include('mpd.class.php');
////    print_R($_POST);
//$myMpd = new mpd($_POST['ip'], $_POST['port']);
//
//echo json_encode($myMpd);
//}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/styles.min.css">
    <script src="js/jquery-2.0.3.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/fastclick.min.js" type="text/javascript"></script>
    <script src="js/script.js" type="text/javascript"></script>

    <link rel="shortcut icon" href="music.png"/>
</head>
<body class="defaultText">
<?php
include('config.php');
include('mpd.class.php');
$myMpd = new mpd($mpdhost, $mpdport);
//$myMpd->SetVolume(50);
?>
<!--<div class="container">-->

<div class="row connected">
    <?php
    if ($myMpd->connected == FALSE) {
        echo "Error Connecting: " . $myMpd->errStr;
    } else {
        ?>
        <B>Connected to MPD Version <? echo $myMpd->mpd_version ?> at <? echo $myMpd->host ?>
            :<? echo $myMpd->port ?></B>
    <?php } ?>
</div>

<div id="player" class=" player well">
    <div class="row playerRow">
        <div class="col-xs-12 col-sm-6  text-center">
            <b>Current Track:</b> <span id="artist"></span> - <span id="title"></span><br>
            <b>Album:</b> <span id="album"></span>
            <br>
            <b>Track Position:</b> <span id="trackPosition"></span>/<span id="trackLength"></span>(<span
                id="trackPercentage"></span>%)
            <br>
            <b>Playlist Position:</b> <span id="listPosition"></span>/<span id="listCount"></span> (<span
                id="listPercentage"></span>%)

        </div>
        <div class="col-md-6 col-sm-6 center-block">

            <span id="albumCover" class="center-block"></span>

        </div>

    </div>

    <div id="controlsDiv" class="row">
        <div class="col-xs-12 col-sm-6 ">
            <div class="btn-group-wrap topPad">
                <div class="btn-group">
                    <button data-control="previous"
                            class='controls btn-lg btn-default glyphicon glyphicon-step-backward'></button>
                    <button data-control="stop"
                            class='controls btn-lg btn-default glyphicon glyphicon-stop'></button>
                    <button data-control="pause"
                            class='controls btn-lg btn-default glyphicon glyphicon-pause'></button>
                    <button data-control="play"
                            class='controls btn-lg btn-default glyphicon glyphicon-play'></button>
                    <button data-control="next"
                            class='controls btn-lg btn-default glyphicon glyphicon-step-forward'></button>
                </div>
                <!--        <div class="state"></div>-->

            </div>
        </div>

        <div class="col-xs-12 col-sm-6 ">

            <div class="btn-group-wrap topPad">
                <div class="btn-group">
                    <a id="volUp" class="btn btn-success btn-lg volAlter volUp" data-alter="up"><span
                            class='glyphicon glyphicon-volume-up'></span</a>

                    <a id="volDown" class="btn btn-primary btn-lg volAlter volDown" data-alter="down"><span
                            class='glyphicon glyphicon-volume-down'></span></a>

                    <a id="volMute" class="btn btn-danger btn-lg volAlter volMute" data-alter="mute"><span
                            class='glyphicon glyphicon-volume-off'></span></a>

                    <a id="volume" class="btn btn-info btn-lg"><?php echo $myMpd->volume; ?></a>
                </div>
                <span id="voMuteHolding"></span>

            </div>
        </div>

    </div>


    <div class="row topPad">
        <div class="btn-group-wrap">
            <div class="center-block">
                <button id="playlist" class="btn lightGreen"><span class="glyphicon glyphicon-th-list"></span></button>
                <button id="search" class="btn lightGreen"><span class="glyphicon glyphicon-search"></span></button>
                <button id="radio" class="btn lightGreen"><span class="glyphicon glyphicon-globe"></span></button>
                <button id="options" class="btn lightGreen"><span class="glyphicon glyphicon glyphicon-cog"></span>
                </button>
                <!--    <button id="selectserver" class="btn">Servers <span class="glyphicon glyphicon-circle-arrow-down"></span></button>-->
            </div>
        </div>
    </div>
</div>


<!--<div id="serverWell" class="well">-->
<!--    <button id="serverlistplayer" class="btn">Player <span class="glyphicon glyphicon-circle-arrow-up"></span></button>-->
<!--    <h4>Select Server to connect to</h4>-->
<!---->
<!--    <div id="serverListItems"></div>-->
<!--</div>-->


<div id="optionsWell" class="playlist well">
    <button id="optionsplayer" class="btn center-block lightGreen"><span
            class="glyphicon glyphicon-circle-arrow-up"></span>
    </button>
    <h4 class="text-center">Options</h4>

    <div id="playlistMessage" class="alert alert-success message"></div>
    <button type="button" class="btn listcontrol lightGreen" data-name="refresh" id="refresh">Refresh MPD database</button>
    <button type="button" class="btn listcontrol lightGreen" data-name="info" id="refresh">Refresh MPD Info</button>
</div>

<div id="radioWell" class="playlist well">
    <button id="radioplayer" class="btn center-block lightGreen"><span
            class="glyphicon glyphicon-circle-arrow-up"></span>
    </button>
    <h4 class="text-center">Radio</h4>

    <div id="playlistMessage" class="alert alert-success message"></div>
    <div id="radioText"></div>
</div>


<div id="playlistWell" class="playlist well">

    <button id="playlistplayer" class="btn center-block  lightGreen"><span
            class="glyphicon glyphicon-circle-arrow-up"></span>
    </button>

    <div id="playlistMessage" class="alert alert-success message"></div>
    <h4 class="text-center"><span id="playlistcount"></span> items in playlist - click to remove</h4>

    <div class="row">
        <button type="button" class="btn listcontrol pull-left lightGreen" data-name="shuffle" id="shuffle">Shuffle Playlist
        </button>
        <button type="button" class="btn listcontrol pull-right lightGreen" data-name="clear" id="clear">Clear Playlist</button>
    </div>
    <div class="row">
        <div id="playListItems"></div>
    </div>
</div>

<div id="searchWell" class="playlist well">
    <a id="top"></a>
    <button id="searchlistplayer" class="btn center-block lightGreen"><span
            class="glyphicon glyphicon-circle-arrow-up"></span>
    </button>
    <h4 class="text-center"> Search for items - click to add to playlist</h4>

    <div id="allSearch" class="btn-group control-buttons radio text-center">
        <button type="button" class="btn bulksearchArtists mobileText lightGreen">All Artists</button>
        <button type="button" class="btn bulksearchAlbums mobileText lightGreen">All Albums</button>
        <button type="button" class="btn advancedSearch mobileText lightGreen">Advanced</button>
        <!--            <button type="button" class="btn" name="track" id="tracksearch">Track</button>-->
    </div>


    <div id="alphabetAnchors">
        <?php
        // echo the alphabet
        for ($i = 65; $i <= 90; $i++) {
            echo '<a class="btn btn-anchor lightGreen" href="#', chr($i), '">', chr($i), '</a>';
        } ?>
    </div>


    <div id="searchListItemsAdded" class="alert alert-success"></div>
    <div id="searchListItems"></div>

    <div id="advancedsearchWell">

        <div id="alphabetSearch">
            <?php
            // echo the alphabet
            for ($i = 65; $i <= 90; $i++) {
                echo '<a class="btn btn-anchor btn-search lightGreen" href="#', chr($i), '">', chr($i), '</a>';
            } ?>
        </div>

        <span name="searchText" id="searchText" class="searchText"></span>

        <div class="btn-group control-buttons radio text-center" data-toggle="buttons-radio">
            <button type="button" class="btn active lightGreen" name="artist" id="artistsearch">Artist</button>
            <button type="button" class="btn lightGreen" name="album" id="albumsearch">Album</button>
            <button type="button" class="btn lightGreen" name="track" id="tracksearch">Track</button>
        </div>

        <button type="button" class="btn lightGreen" name="clearsearch" id="clearsearch">Clear Search</button>

        <div id="advancedsearchListItemsAdded" class="alert alert-success"></div>
        <div id="advancedSearchListItems"></div>
    </div>


</div>
<!--</div>-->

</body>
</html>


