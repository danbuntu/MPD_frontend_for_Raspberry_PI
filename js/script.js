$(document).ready(function () {
//    console.log('ready');


//    connectToServer();
    doPoll();
    function connectToServer() {
        var selectedOp = $('#hostlist option:selected').val();
//        console.log('selected item' + selectedOp);
    }


    function loadServers() {
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'getServers'},
            success: function (data) {
//                console.log(data);
                var hostsarray = $.parseJSON(data);
//                console.log(hostsarray);
                var items = '';

                $.each(hostsarray, function (key, value) {
                    items += '<button  class="btn btn-default serverButton" data-hostip="' + value.hostip + '" data-port="' + value.port + '">' + value.name + '</button>';

                });
//  items += '</select>';
//                console.log(items);
                $('#serverListItems').html(items);
            }
        });
    }


    function doPoll() {
        // get the current coverimage
        var cover = $('.cover').attr('src');
//        console.log('cover: ' + cover);

//        console.log('poll');
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'poll'},
            success: function (data) {
//                console.log(data);
                var json = $.parseJSON(data)
//                console.log(json);
                // set the various options

                $('#artist').html(json.artist);
                $('#title').html(json.title);
                $('#album').html(json.album);
                $('#trackPosition').html(json.position);
                $('#trackLength').html(json.length);
                $('#trackPercentage').html(json.positionPercentage);
                $('#listPosition').html(json.listPosition);
                $('#listPercentage').html(json.listTrack);
                $('#listCount').html(json.listCount);

                // check the cover image and only update if it new to stop onscreen flashing
//                console.log('cover: ' + cover + ' path:' +  'imageprocessor.php?name=' + json.cover);
                if (cover != '../imageprocessor.php?name=' + json.cover) {
                    $('#albumCover').html('<img class="cover img-responsive center-block" src="../imageprocessor.php?name=' + json.cover + '" ">');
                }

                if (json.state == 'stop') {
                    $('.state').html('Stopped<br>');
                } else if (json.state == 'pause') {
                    $('.state').html('Paused<br>');
                } else if (json.state == 'play') {
                    $('.state').html('');
                }
                setTimeout(doPoll, 1000);
            }
        });
    }


    $('.controls').click(function (value) {
//        console.log('vol up clicked' + value);
        var controlType = $(this).attr('data-control');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'control', controlType: controlType},
            success: function (data) {
//                console.log(data);
                // get the current volume
            }
        });
    });


    $('.listcontrol').click(function (value) {
//        console.log('listcontrol clicked');
        var controlType = $(this).attr('data-name');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'listcontrol', controlType: controlType},
            success: function (data) {
                console.log(data);

                $('.message').show();
                $('.message').html(data);
                $('.message').fadeOut(5000);
                // get the current volume
                loadtheplaylist()
            }
        });
    });


    $('.volAlter').click(function (value) {

//        console.log('vol up clicked' + value);
// get the current volume

        var voltype = $(this).attr('data-alter');
        var vol = $('#volume').html();
        if (voltype == 'up') {
            vol = parseInt(vol) + 1;
        } else if (voltype == 'down') {
            vol = parseInt(vol) - 1;
        } else if (voltype == 'mute') {
//console.log(vol);
            if (vol != 0) {
                // mute the volume
                // get the current volume and pop ininto a holding div
                $('#voMuteHolding').html(vol);
                $('#voMuteHolding2').html(vol);
                vol = 0
                $('#volMute').removeClass('btn-danger');
                $('#volMute').addClass('btn-warning');
                // set the mute button colour

            } else {
                // unmute the volume
                $('#volMute').addClass('btn-danger');
                $('#volMute').removeClass('btn-warning');
                vol = $('#voMuteHolding').html();
                $('#voMuteHolding').html('');
            }
        }
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'volume', vol: vol},
            success: function (data) {
//                console.log(data);
                $('#volume').html(vol);

                if (vol == 100) {
                    $('#volUp').attr("disabled", true);
                    $('#volDown').attr("disabled", false);
                } else if (vol == 0) {
                    $('#volDown').attr("disabled", true);
                    $('#volUp').attr("disabled", false);

                } else {
                    $('#volUp').attr("disabled", false);
                    $('#volDown').attr("disabled", false);
                }

                if ($('#voMuteHolding').html() > 0) {
                    $('#volDown').attr("disabled", true);
                    $('#volUp').attr("disabled", true);
                }

            }
        });
    });


    $('#playlist').click(function (value) {
//        console.log('playlist pressed');
        loadtheplaylist();
        $('#playlistWell').slideDown("slow");
        $('#player').slideUp("slow");

    });


    $('#radio').click(function (value) {
//        console.log('playlist pressed');
        loadRadio();
        $('#radioWell').slideDown("slow");
        $('#player').slideUp("slow");

    });


    $('#options').click(function (value) {
//        console.log('playlist pressed');
        loadtheplaylist();
        $('#optionsWell').slideDown("slow");
        $('#player').slideUp("slow");

    });


    $('#playlistplayer').click(function (value) {
//        console.log('playlist pressed');
        $('#playlistWell').slideUp("slow");
        $('#player').slideDown("slow");
    });

    $('#optionsplayer').click(function (value) {
//        console.log('playlist pressed');
        $('#optionsWell').slideUp("slow");
        $('#player').slideDown("slow");
    });

    $('#radioplayer').click(function (value) {
//        console.log('playlist pressed');
        $('#radioWell').slideUp("slow");
        $('#player').slideDown("slow");
    });

    $('#search').click(function (value) {
//        console.log('search pressed');
        loadtheplaylist();
        $('#searchWell').slideDown("slow");
        $('#player').slideUp("slow");

    });

//    $('#selectserver').click(function (value) {
//        console.log('search pressed');
//        loadServers();
//        $('#serverWell').slideDown("slow");
//        $('#player').slideUp("slow");
//    });

    $('#searchlistplayer').click(function (value) {
//        console.log('search pressed');
        $('#searchWell').slideUp("slow");
        $('#player').slideDown("slow");
    });

//    $('#serverlistplayer').click(function (value) {
//        console.log('search pressed');
//        $('#serverWell').slideUp("slow");
//        $('#player').slideDown("slow");
//    });


    function loadtheplaylist() {
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'playlistget'},
            success: function (data) {
//                console.log(data);
                var list = $.parseJSON(data)

                var count = list.length;
                $('#playlistcount').html(count);

                var items = '<ul class="playlistlist">';

                $.each(list, function (key, value) {
//                    console.log(key + ' ' + value);
                    items += '<li class=" playListItem ' + value.Active + '" data-id="' + value.Pos + '">' + key + ' - ' + value.Artist + ' - ' + value.Album + ' - ' + value.Title + '</li>';
                });
                items += '</ul>';

                $('#playListItems').html(items);
            }
        });
    }


    $('#playListItems').on('click', '.playListItem', function () {
//        console.log('playlistitem pressed');
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'removeItem', id: $(this).attr('data-id')},
            success: function (data) {
//                console.log(data);
                loadtheplaylist()
            }
        });
    });

    $('#radioText').on('click', '.playListItem', function () {
//        console.log('radio pressed' + $(this).attr('data-id'));
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'addRadio', id: $(this).attr('data-id'), name: $(this).attr('data-name')},
            success: function (data) {
//                console.log(data);
                loadtheplaylist()
            }
        });
    });


    $('#searchListItems').on('click', '.searchListItem', function () {
//        console.log('playlistitem pressed');
//        console.log('name: ' + $(this).attr('data-name'));
        if ($(this).attr('data-name')) {
            var bulk = 'yes';
        } else {
            var bulk = 'no';
        }
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'addItem', id: $(this).attr('data-name'), bulk: bulk},
            success: function (data) {
//                console.log(data);
                $('#searchListItemsAdded').show();
                $('#searchListItemsAdded').html('Item added');
                $('#searchListItemsAdded').fadeOut(5000);
//                    loadtheplaylist()
            }
        });
    });


    $('#advancedSearchListItems').on('click', '.searchListItem', function () {
//        console.log('playlistitem pressed');
//        console.log('name: ' + $(this).attr('data-name'));
        if ($(this).attr('data-name')) {
            var bulk = 'yes';
        } else {
            var bulk = 'no';
        }
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'addItem', id: $(this).attr('data-name'), bulk: bulk},
            success: function (data) {
//                console.log(data);
                $('#searchListItemsAdded').show();
                $('#searchListItemsAdded').html('Item added');
                $('#searchListItemsAdded').fadeOut(5000);
//                    loadtheplaylist()
            }
        });
    });


    $('.alphabutton').on('click', function () {
//        console.log('alpha search');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'lettersearch', id: $(this).attr('data-letter'), letter: 'letter'},
            success: function (data) {
//                console.log(data);
                var list = $.parseJSON(data);
                var items = '<ol>';

                $.each(list, function (key, value) {
                    items += '<li class=" searchListItem" data-name="' + value.filename + '"';
//                    console.log(value.multiple);
                    if (value.multiple == 'yes') {
                        items += ' data-type="bulk"><button class=" btn btn-default">' + value.result + '</button></li>';
                    } else {
                        items += '><button class=" btn btn-default">' + value.result + '</button></li>';
                    }

                });
                items += '</ol>';

                $('#searchListItems').html(items);
            }
        });
    });


// search box
    $('#searchText').on('change', function () {
//        console.log('change' + $(this).val());

        var search = $(this).val()
        if (search.length > 3) {
            // get the button states

            var artist = $('#artistsearch').hasClass('active');
            var album = $('#albumsearch').hasClass('active');
            var track = $('#tracksearch').hasClass('active');

//            console.log(artist + ' ' + album + ' ' + track);

            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {type: 'search', search: search, artist: artist, album: album, track: track},
                success: function (data) {
//                    console.log(data);
                    // get the current volume
                    var list = $.parseJSON(data)
                    var items = '<ol>';

                    $.each(list, function (key, value) {
                        items += '<li class=" searchListItem" data-name="' + value.filename + '"';
//                        console.log(value.multiple);
                        if (value.multiple == 'yes') {
                            items += ' data-type="bulk"><button class=" btn btn-default">' + value.result + '</button></li>';
                        } else {
                            items += '><button class=" btn btn-default">' + value.result + '</button></li>';
                        }

                    });
                    items += '</ol>';

                    $('#searchListItems').html(items);
                }
            });
        }
    });


    $('.bulksearchArtists').on('click', function () {
        $('#advancedsearchWell').hide();
        $('#alphabetAnchors').show();
        $('#searchListItems').show();

        $('#searchListItems').html('<div class="text-center spiner"><img src="ajax-loader.gif"><br> loading...</div>');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'bulksearchArtists'},
            success: function (data) {
//                console.log(data);
//                var list = $.parseJSON(data);
//                var items = '<ol>';
//                $.each(list, function (key, value) {
//                items += '<button class=" btn btn-default searchButtonArtist">' + value + '</button></li>';
//                });
//                items += '</ol>';

                $('#searchListItems').html(data);
            }
        });
    });


    $('.bulksearchAlbums').on('click', function () {
        $('#advancedsearchWell').hide();
        $('#alphabetAnchors').show();
        $('#searchListItems').show();
        $('#searchListItems').html('<div class="text-center spiner"><img src="ajax-loader.gif"><br> loading...</div>');

        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'bulksearchAlbums'},
            success: function (data) {
//                console.log(data);
                $('#searchListItems').html(data);
            }
        });
    });


    $('.advancedSearch').on('click', function () {
        $('#alphabetAnchors').hide();
        $('#searchListItems').hide();
        $('#advancedsearchWell').show();

    });


    $('#searchListItems').on('click', '.searchButtonArtist', function () {
        var artist = $(this).text();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'bulksearchAlbumsByArtist', artist: artist},
            success: function (data) {
//                console.log(data);
                var list = $.parseJSON(data);
                var items = '<ol>';
                $.each(list, function (key, value) {
                    items += '<button class=" btn btn-default searchButtonAlbum searchListItem">' + value + '</button></li>';
                });
                items += '</ol>';

                $('#searchListItems').html(items);
            }
        });
    });

    $('#advancedSearchListItems').on('click', '.searchButtonArtist', function () {
        var artist = $(this).text();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'bulksearchAlbumsByArtist', artist: artist},
            success: function (data) {
//                console.log(data);
                var list = $.parseJSON(data);
                var items = '<ol>';
                $.each(list, function (key, value) {
                    items += '<button class=" btn btn-default searchButtonAlbum searchListItem">' + value + '</button></li>';
                });
                items += '</ol>';

                $('#advancedSearchListItems').html(items);
            }
        });

    });


    $('#searchListItems').on('click', '.searchButtonAlbum', function () {
        var album = $(this).text();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'getAndAddAllAlbumTracks', album: album},
            success: function (data) {
//                console.log(data);
                $('#searchListItemsAdded').show();
                $('#searchListItemsAdded').html('Album added');
                $('#searchListItemsAdded').fadeOut(5000);
            }
        });
    });

    $('#advancedSearchListItems').on('click', '.searchButtonAlbum', function () {
        var album = $(this).text();
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'getAndAddAllAlbumTracks', album: album},
            success: function (data) {
//                console.log(data);
                $('#advancedsearchListItemsAdded').show();
                $('#advancedsearchListItemsAdded').html('Item added');
                $('#advancedsearchListItemsAdded').fadeOut(5000);
            }
        });
    });


//    advanced search
    $('#alphabetSearch').on('click', '.btn-search', function () {
        var letter = $(this).text();

        var fullSearchText = $('#searchText').text() + letter;
//        console.log(letter);
        $('#searchText').html(fullSearchText);

//        console.log('search test', fullSearchText);

//        console.log('change' + $(this).text());

//        var search = $(this).val()
        if (fullSearchText.length > 3) {
            // get the button states

            var artist = $('#artistsearch').hasClass('active');
            var album = $('#albumsearch').hasClass('active');
            var track = $('#tracksearch').hasClass('active');

//            console.log(artist + ' ' + album + ' ' + track);

            $('#advancedSearchListItems').html('<div class="text-center spiner"><img src="ajax-loader.gif"><br> loading...</div>');
            $.ajax({
                type: "POST",
                url: 'ajax.php',
                data: {type: 'search', search: fullSearchText, artist: artist, album: album, track: track},
                success: function (data) {
//                    console.log(data);

                    if (artist == true) {
                        $('#advancedSearchListItems').html(data);
                    } else {
//                    get the current volume
                        var list = $.parseJSON(data)
                        var items = '<ol>';

                        $.each(list, function (key, value) {
//                        items += '<li class=" searchListItem" data-name="' + value.filename + '"';
//                            console.log(value.multiple);
                            if (value.multiple == 'yes') {
                                items += '<li class="searchListItem" data-name="' + value.filename + '" data-type="bulk"><button class=" btn btn-default">' + value.result + '</button></li>';
                            } else {
                                items += '<li class=" searchListItem" data-name="' + value.filename + '"><button class=" btn btn-default">' + value.result + '</button></li>';
                            }

                        });
                        items += '</ol>';

                        $('#advancedSearchListItems').html(items);
                    }
                }
            });
        }
    });


//    Reset the search buttons to make them radio button like
    $('#artistsearch').click(function (value) {
        $('#tracksearch').removeClass('active');
        $('#albumsearch').removeClass('active');
    });

    $('#albumsearch').click(function (value) {
        $('#tracksearch').removeClass('active');
        $('#artistsearch').removeClass('active');
    });

    $('#tracksearch').click(function (value) {
        $('#albumsearch').removeClass('active');
        $('#artistsearch').removeClass('active');
    });


    $('#clearsearch').click(function (value) {
//        console.log('Clear the search');
        $('#searchText').html('');
        $('#advancedSearchListItems').html('');
        $('#advancedsearchListItemsAdded').html('');
    });

//    function loadRadio() {
//        $.ajax({
//            url : "radio.txt",
//            dataType: "text",
//            success : function (data) {
//                $(".radioText").html(data);
//                <li class=" radioItem" data-id="' + value.Pos + '">' + value.Artist + ' - ' + value.Album + ' - ' + value.Title + '</li>
//
//            }
//        });
//    }

    function loadRadio() {

        $('#radioText').html('');
        var items = '<ul class="playlistlist">';
        // use the now function to break the text file caching
        jQuery.get('radio.txt', {
            now: jQuery.now()
        }, function (data) {
            var lines = data.split("\n");
            //process text file line by line

            $.each(lines, function (n, urlRecord) {
//console.log(urlRecord);
                var values = urlRecord.split('~');
//            alert(values[1]);
                items += '<li class="playListItem" data-id="' + values[1] + '" data-name="' + values[0] + '">' + values[0] + '</li>';

            });
            items += '</ul>';
            $('#radioText').html(items);
        });

    }

    $('#radioText').on('click', '.radioItem', function () {
//        console.log('radioItem pressed');
        $.ajax({
            type: "POST",
            url: 'ajax.php',
            data: {type: 'addRadio', id: $(this).attr('data-id'), name: $(this).attr('data-name')},
            success: function (data) {
//                console.log(data);
                loadtheplaylist()
            }
        });
    });


});


