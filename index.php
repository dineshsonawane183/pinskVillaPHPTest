<?php
$current = 0;
$feedData = [];
$sourceString = "";

function makecurlcall() {
    $url = "https://www.pinkvilla.com/feed/video-test/video-feed.json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $result = json_decode($response);
    curl_close($ch);
    return $result;
}

function init() {
    global $feedData;
    $feedData = makecurlcall();
}

init();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <script src = "./lib/lib.js"></script>
    </head>
    <body>
        <input id = "currentVideo" type="hidden" value="1"/>
        <div>
            <video id= "videoPlayer" width="100%" height="100%" autoplay>
                <source id = "srcPlayer" src="<?php
                        global $current;
                        echo ($feedData[$current]->url);
                        $current++;
                        ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <script>
            var videoPlayer = document.getElementById("videoPlayer");
            videoPlayer.addEventListener("click", mouseOver);
            function mouseOver() {
                videoPlayer.play();
                videoPlayer.setAttribute('muted', false);
                videoPlayer.addEventListener('swiped-up', function (e) {
                    playVideo(videoPlayer, "next");
                });
                videoPlayer.addEventListener('swiped-down', function (e) {
                    playVideo(videoPlayer, "prev");
                });
                function playVideo(videoPlayer, step) {
                    var data = `<?php echo (json_encode($feedData)); ?>`;
                            data = JSON.parse(data);
                    var id = parseInt(document.getElementById('currentVideo').value);
                    if (videoPlayer) {
                        videoPlayer.setAttribute("src", data[id].url);
                    }
                    if (step == 'next') {
                        if (id < data.length - 1) {
                            document.getElementById('currentVideo').setAttribute('value', id + 1)
                        }
                    }
                    if (step == 'prev') {
                        if (id > 0) {
                            document.getElementById('currentVideo').setAttribute('value', id - 1)
                        }
                    }
                }
            }
        </script>
    </body>
</html>