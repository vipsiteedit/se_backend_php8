(function () {
    $(document).ready(function () {
        if (jQuery.browser.mobile == false) {
            var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;

            var setImage = function (elem) {
                var match = $(elem).attr("data-yt-video-url").match(regExp);
                //if (match[1] && typeof match[1]!= "undefined") {
                if (match && typeof match != "undefined") {
                    $(elem).css("background-image", "url(https://img.youtube.com/vi/" + match[1] + "/maxresdefault.jpg)");
                }
            };

            var parseBoolean = function (val, def) {
                if (typeof val != "undefined") {
                    if ((typeof val === "string" && val.toLowerCase() === "true" || val == "1") ||
                        val == true || val == 1)
                        return true;
                    if ((typeof val === "string" && val.toLowerCase() === "false" || val == "0") ||
                        val == false || val == 0)
                        return false;
                    return false;
                } else {
                    return def;
                }
            };

            var bindPlayer = function (elem, data) {
                var match = $(elem).attr("data-yt-video-url").match(regExp);
                //if (match[1] && typeof match[1]!= "undefined") {
                if (match && typeof match != "undefined") {
                    var ratio = ["auto", "16/9", "4/3"];
                    var quality = ["default", "small", "medium", "large", "hd720", "hd1080", "highres"];

                    var options = {};
                    options.videoURL = match[1];
                    options.containment = "self";

                    if (typeof data.ytRatio != "undefined") {
                        options.ratio = (ratio.indexOf(data.ytRatio) != -1) ? data.ytRatio : "auto";
                    } else {
                        options.ratio = "auto";
                    }

                    if (typeof data.ytStartAt != "undefined" &&
                        typeof parseFloat(data.ytStartAt) == "number") {
                        options.startAt = data.ytStartAt;
                    } else {
                        options.startAt = 0;
                    }

                    if (typeof data.ytStopAt != "undefined" &&
                        typeof parseFloat(data.ytStopAt) == "number") {
                        options.stopAt = data.ytStopAt;
                    } else {
                        options.stopAt = 0;
                    }

                    if (typeof data.ytVol != "undefined" &&
                        typeof parseFloat(data.ytVol) == "number" &&
                        parseFloat(data.ytVol) >= 0 &&
                        parseFloat(data.ytVol) <= 100) {
                        options.vol = data.ytVol;
                    } else {
                        options.vol = 50;
                    }

                    if (typeof data.ytOpacity != "undefined" &&
                        typeof parseFloat(data.ytOpacity) == "number") {
                        options.opacity = data.ytOpacity;
                    } else {
                        options.opacity = 1;
                    }

                    if (typeof data.ytQuality != "undefined") {
                        options.quality = (quality.indexOf(data.ytQuality) != -1) ? data.ytQuality : "default";
                    } else {
                        options.quality = "default";
                    }

                    options.autoPlay = parseBoolean(data.ytAutoPlay, true);
                    options.addRaster = parseBoolean(data.ytAddRaster, false);
                    options.mute = parseBoolean(data.ytMute, true);
                    options.loop = parseBoolean(data.ytLoop, true);
                    options.showControls = parseBoolean(data.ytShowControls, false);
                    options.showAnnotations = parseBoolean(data.ytShowAnnotations, false);
                    options.showYTLogo = parseBoolean(data.ytShowYtLogo, false);
                    options.stopMovieOnBlur = parseBoolean(data.ytStopMovieOnBlur, true);
                    options.realfullscreen = parseBoolean(data.ytRealfullscreen, true);
                    options.gaTrack = parseBoolean(data.ytGaTrack, false);
                    options.optimizeDisplay = parseBoolean(data.ytOptimizeDisplay, true);
                    options.backgroundUrl = "https://img.youtube.com/vi/" + match[1] + "/maxresdefault.jpg";

                    //console.log(options);

                    $(elem).YTPlayer(options);

                    if (!options.loop) {
                        $(elem).on("YTPEnd", function (e) {
                            $(elem).attr("data-yt-stopped", 1);
                        });
                    }

                }
            };

            $(document).find("[data-yt-video-url]").each(function () {
                setImage(this);
                bindPlayer(this, $(this).data());
            });

            var inWindow = function (s) {
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();
                var currentEls = $(s);
                var result = [];
                currentEls.each(function () {
                    var el = $(this);
                    var offset = el.offset();
                    if ((offset.top <= scrollTop + windowHeight) && (scrollTop <= el.height() + offset.top))
                        result.push(this);
                });
                return $(result);
            };

            var onScroll = function () {
                var visible = inWindow('div.mb_YTPlayer, section.mb_YTPlayer, header.mb_YTPlayer');
                $('.mb_YTPlayer').each(function () {
                    var self = this;
                    var notstopped = (typeof $(self).attr("data-yt-stopped") == "undefined");
                    if (visible.length) {
                        $(visible).each(function () {
                            if (self != this && notstopped) {
                                $(self).YTPPause();
                            }
                        });
                    } else if (notstopped) {
                        $(this).YTPPause();
                    }

                });
                $(visible).each(function () {
                    if (typeof $(this).attr("data-yt-stopped") == "undefined") {
                        $(this).YTPPlay();
                    }
                });
            };

            $(document).on('scroll resize', onScroll);
        }
    });
})(jQuery);