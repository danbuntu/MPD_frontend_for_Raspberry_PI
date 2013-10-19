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
    <link rel="stylesheet" type="text/css" href="bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.min.css">
    <script src="jquery-2.0.3.min.js" type="text/javascript"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="script.js" type="text/javascript"></script>
</head>
<body class="defaultText">
<?php
include('config.php');
include('mpd.class.php');
$myMpd = new mpd($mpdhost, $mpdport);
//$myMpd->SetVolume(50);
?>


<div class="row connected">
    <A HREF="<?php echo $_SERVER[PHP_SELF] ?>">Refresh Page</A>
    <?php
    if ($myMpd->connected == FALSE) {
        echo "Error Connecting: " . $myMpd->errStr;
    } else {
        ?>
        <B><? echo $myMpd->host ?></B>
    <?php } ?>
</div>

<div id="player" class="row player well">
    <div class="col-md-7">


        <b>Current Track:</b> <span id="artist"></span> - <span id="title"></span><br>
        <b>Album:</b> <span id="album"></span>
        <br>
        <b>Track Position:</b> <span id="trackPosition"></span>/<span id="trackLength"></span>(<span
            id="trackPercentage"></span>%)
        <br>
        <b>Playlist Position:</b> <span id="listPosition"></span>/<span id="listCount"></span> (<span
            id="listPercentage"></span>%)
        <br>

        <div class="controlsDiv">
            <div class="btn-group">
                <button data-control="previous"
                        class='controls btn btn-default glyphicon glyphicon-step-backward'></button>
                <button data-control="stop" class='controls btn btn-default glyphicon glyphicon-stop'></button>
                <button data-control="pause" class='controls btn btn-default glyphicon glyphicon-pause'></button>
                <button data-control="play" class='controls btn btn-default glyphicon glyphicon-play'></button>
                <button data-control="next" class='controls btn btn-default glyphicon glyphicon-step-forward'></button>
            </div>
            <div class="state"></div>
        </div>
    </div>
    <div class="col-md-3">
        <span id="albumCover"></span>
    </div>
    <div class="col-md-2">
        <b>Volume: <span id="volume"><?php echo $myMpd->volume; ?></span></b><br>

        <div class="btn-group-vertical">

            <a id="volUp" class="btn btn-success btn-lg volAlter volUp" data-alter="up"><span
                    class='glyphicon glyphicon-volume-up'></span</a>

            <a id="volDown" class="btn btn-primary btn-lg volAlter volDown" data-alter="down"><span
                    class='glyphicon glyphicon-volume-down'></span></a>

            <a id="volMute" class="btn btn-danger btn-lg volAlter volMute" data-alter="mute"><span
                    class='glyphicon glyphicon-volume-off'></span></a>

        </div>
        <span id="voMuteHolding"></span>

    </div>
    <button id="playlist" class="btn">Playlist <span class="glyphicon glyphicon-circle-arrow-down"></span></button>
    <button id="search" class="btn">Search <span class="glyphicon glyphicon-circle-arrow-down"></span></button>
    <button id="options" class="btn">Options <span class="glyphicon glyphicon-circle-arrow-down"></span></button>
    <!--    <button id="selectserver" class="btn">Servers <span class="glyphicon glyphicon-circle-arrow-down"></span></button>-->

</div>


<!--<div id="serverWell" class="well">-->
<!--    <button id="serverlistplayer" class="btn">Player <span class="glyphicon glyphicon-circle-arrow-up"></span></button>-->
<!--    <h4>Select Server to connect to</h4>-->
<!---->
<!--    <div id="serverListItems"></div>-->
<!--</div>-->


<div id="optionsWell" class="playlist well">
    <button id="optionsplayer" class="btn">Player <span class="glyphicon glyphicon-circle-arrow-up"></span></button>
    <h4>Options</h4>
    <div id="playlistMessage" class="alert alert-success message"></div>
    <button type="button" class="btn listcontrol" data-name="refresh" id="refresh">Refresh MPD database</button>
    <button type="button" class="btn listcontrol" data-name="info" id="refresh">Refresh MPD Info</button>


    </div>


<div id="playlistWell" class="playlist well">
    <button id="playlistplayer" class="btn">Player <span class="glyphicon glyphicon-circle-arrow-up"></span></button>
    <div id="playlistMessage" class="alert alert-success message"></div>
    <h4> Number of item in playlist <span id="playlistcount"></span> - click to remove</h4>

    <button type="button" class="btn listcontrol" data-name="shuffle" id="shuffle">Shuffle Playlist</button>
    <button type="button" class="btn listcontrol" data-name="clear" id="clear">Clear Playlist</button>

    <div id="playListItems"></div>
</div>

<div id="searchWell" class="playlist well">
    <a id="top"></a>
    <button id="searchlistplayer" class="btn">Player <span class="glyphicon glyphicon-circle-arrow-up"></span></button>
    <h4> Search for items - click to add to playlist</h4>

        <div id="allSearch" class="btn-group control-buttons radio" >
            <button type="button" class="btn bulksearchArtists" >All Artists</button>
            <button type="button" class="btn bulksearchAlbums" >All Albums</button>
            <button type="button" class="btn advancedSearch" >Advanced</button>
<!--            <button type="button" class="btn" name="track" id="tracksearch">Track</button>-->
        </div>



<div id="alphabetAnchors">
    <?php
    // echo the alphabet
    for ($i = 65; $i <= 90; $i++) {
        echo '<a class="btn btn-primary btn-anchor" href="#' , chr($i) , '">', chr($i), '</a>';
    } ?>
</div>


    <div id="searchListItemsAdded" class="alert alert-success"></div>
    <div id="searchListItems"></div>

    <div id="advancedsearchWell">

        <div id="alphabetSearch">
        <?php
        // echo the alphabet
        for ($i = 65; $i <= 90; $i++) {
            echo '<a class="btn btn-primary btn-anchor btn-search" href="#' , chr($i) , '">', chr($i), '</a>';
        } ?>
</div>

        <span name="searchText" id="searchText"></span>

            <div class="btn-group control-buttons radio" data-toggle="buttons-radio">
                <button type="button" class="btn active" name="artist" id="artistsearch">Artist</button>
                <button type="button" class="btn" name="album" id="albumsearch">Album</button>
                <button type="button" class="btn" name="track" id="tracksearch">Track</button>
            </div>

        <button type="button" class="btn" name="clearsearch" id="clearsearch">Clear Search</button>

        <div id="advancedsearchListItemsAdded" class="alert alert-success"></div>
        <div id="advancedSearchListItems"></div>
    </div>


</div>
</body>
</html>


