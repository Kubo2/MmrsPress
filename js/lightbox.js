
/*
Lightbox v2.51
by Lokesh Dhakar - http://www.lokeshdhakar.com

For more information, visit:
http://lokeshdhakar.com/projects/lightbox2/

Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
- free for use in both personal and commercial projects
- attribution requires leaving author name, author link, and the license info intact
	
Thanks
- Scott Upton(uptonic.com), Peter-Paul Koch(quirksmode.com), and Thomas Fuchs(mir.aculo.us) for ideas, libs, and snippets.
- Artemy Tregubenko (arty.name) for cleanup and help in updating to latest proto-aculous in v2.05.


Table of Contents
=================
LightboxOptions

Lightbox
- constructor
- init
- enable
- build
- start
- changeImage
- sizeContainer
- showImage
- updateNav
- updateDetails
- preloadNeigbhoringImages
- enableKeyboardNav
- disableKeyboardNav
- keyboardAction
- end

options = new LightboxOptions
lightbox = new Lightbox options
*/

(function() {
  var $, Lightbox, LightboxOptions;

  $ = jQuery;

  LightboxOptions = (function() {

    function LightboxOptions() {
      this.fileLoadingImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAvNJREFUWEfNV8mK6kAUrcQ4Io7oSmwUBQVdufRj/FNXbm1omkZBu0VxXoiCEw6dV6fghsqg6HsGXkGoeOvWPedOlZKx/310u13dTY7aLeP9fl8fDoes1WrdUnmJXHGyAq87nQ7bbDZiWVEU1mg0HHWd9j8jU63Kn5+f+vv7uwAHMMb5fGY/Pz+upMJEAJ5zAgKQwDF7vV7WbDatXF/y2yAADxF2gGN4PB4TQCqVegmg1YhRhCi43W7nCF4oFFi9XnevBhD62WxmA0cUKpWKa+AAFCmYTCbs9/fXGh2Wy+VYrVZzxXMCU3u9nr5YLExFh8KLx+Ouem4QWK/XTNd1UXRU+VjMZrO2iLgh0EAAwEgBZlVVWTAYZNVq1dXQGxHY7/cCVB6xWMwNZx1tatT3RAJRCIfDjspuCLXr9WrKPWrB5/O5geVoUxxE1lPPqSUdd79AqDp5e7lcXmD6MRM2AqiB4/H42O4ntHiqbV9TyNRQKGSYoTbcbrdPmH5cFYBEhGY1EokICwDXNE20JCLw9fVlY/w4lFnzdDoBmOHBkEgwLRqNiu+9tfDm8/nf4tn2UU3BQSIBJV5/iso/tUoikbBtQhTa7fY/R4GnUyfnQIQekokjEJcNOogwgykGooArmo3dg4LVaqXz8JtAAUwPzAgCpVJJSafTwqz1WB6Px+zj4+NpEsvl0gQuew8cXvziW2N8cHAN//7+FmzlQRFBtySTSUHWpGD5MZ1OdTnnWKaIwhYennLDhskYbkaDwUCECIo0U3dAhoMLnQMZihcyqboFHdInYHnG+00C2MwvpvpoNDK6gljLhkkmE5PfcbTTQ7pEnqfa5LTtf0G5XFby+TwLBALCG2yUZ7mA5HeEnX7jgkN9T9WOyFnBhV1h3TKKxaLy9vaGUBkrZEhWJZl1DWSIABxAdJzAYetuQUEBd0beToyOZzn8RAYAcp6hgwjiZpXJZO5i3F2UvcUfl8PhII5p9DZAqNopXZj9fr8oVH6nfMj2H9jagcSzKw2/AAAAAElFTkSuQmCC';
      this.fileCloseImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAAy9JREFUOE+llcsrdVEYxp9zbPdLKHdxUMpAiXQG5i4zM1KSkYF/wl+g/AcyY6AoJopymSiJiZyBXHMn97vz+b3ft3ZbKb68tTt7v2s9z3re2zqhycnJuAIWDoeDn//9/v7+bhgvISFBl5eXur+/Nwffv7FQKKTs7Gx58Xhcj4+POjo6Eu+/VZyUlKSMjAx5T09Pen19FSGgNjU1VYmJib5o/Bz8/Pzs+zzPU0pKivh1BgdRIw6MreDk4bT6+npVVVXZImFBuLGxofX1dSGCw8vLy1VXV2fKXJT7+/uan5/X29vbX2JeIH15edHDw4OOj4/V1NRkhzgjZ9QhFospPz/f1iORiL8Ox8rKiuFJJXxhnI6cBdTNzs6az1leXp4aGxtVVlamaDRqv85QjNK1tTXd3d0ZKVgvqJjQr6+vtby8rJycHCNxVllZqaysLKt4sMAQLi0t6erqytJC5EZMLh05xNjJyYnm5uaMvLq62nwUq7S01N4hYO/29rZmZmYsffiwT4o5hSIFlezu7mpqasoIg6EDhvTg4EAfw6WdnR1T6Q6kuJD7qeADJxHQNjyA09PT1dvb+6kFIVlcXNTCwoIJojsQgPmKWXDpIPk3NzdWBHqXDsjNzf1yaGjJtLQ07e3tWX4hpwbMgIn8aJ2Bs7MzEfrFxYUVj9DY2NbWpo6ODl+NSfpnxcXFRrK5uanz83OLEDFEDTZMi7FwenrqTw4T1dDQoM7OTn8IghV35C0tLWpublZmZqapRDk8RB12AHcrASLMnp4eFRUV+R1AyKOjo9ra2jJecCju6uqytnQDRWptGl2b0HKQFxQUqLu7W7W1tUZAB5BzOmR4eFjj4+M2hfjB0pJ9fX2qqakxHxw8RswLvyS/vb1dra2tRuqMDpiYmNDh4aGmp6dtMsG4vq+oqFB/f78KCwsNApddm+6U5ORkC391ddWGhr6mqGNjY9Z6GIUeGRkxDNMIngMoONESjRG7E/gg8YODg5+uQypNUTjIhU+eh4aG7Ip1Bp59bo+NNE6AkBDudwYG5V+Zu0r9m5oJczn7CvBTn/uT8G5vbxX5uFtLSkp+iv12H339B6nVUikx/ygwAAAAAElFTkSuQmCC';
      this.resizeDuration = 700;
      this.fadeDuration = 500;
      this.labelImage = "Obrázek";
      this.labelOf = "z";
    }

    return LightboxOptions;

  })();

  Lightbox = (function() {

    function Lightbox(options) {
      this.options = options;
      this.album = [];
      this.currentImageIndex = void 0;
      this.init();
    }

    Lightbox.prototype.init = function() {
      this.enable();
      return this.build();
    };

    Lightbox.prototype.enable = function() {
      var _this = this;
      return $('body').on('click', 'a[class^=lightbox], area[class^=lightbox]', function(e) {
        _this.start($(e.currentTarget));
        return false;
      });
    };

    Lightbox.prototype.build = function() {
      var $lightbox,
        _this = this;
      $("<div>", {
        id: 'lightboxOverlay'
      }).after($('<div/>', {
        id: 'lightbox'
      }).append($('<div/>', {
        "class": 'lb-outerContainer'
      }).append($('<div/>', {
        "class": 'lb-container'
      }).append($('<img/>', {
        "class": 'lb-image'
      }), $('<div/>', {
        "class": 'lb-nav'
      }).append($('<a/>', {
        "class": 'lb-prev'
      }), $('<a/>', {
        "class": 'lb-next'
      })), $('<div/>', {
        "class": 'lb-loader'
      }).append($('<a/>', {
        "class": 'lb-cancel'
      }).append($('<img/>', {
        src: this.options.fileLoadingImage
      }))))), $('<div/>', {
        "class": 'lb-dataContainer'
      }).append($('<div/>', {
        "class": 'lb-data'
      }).append($('<div/>', {
        "class": 'lb-details'
      }).append($('<span/>', {
        "class": 'lb-caption'
      }), $('<span/>', {
        "class": 'lb-number'
      })), $('<div/>', {
        "class": 'lb-closeContainer'
      }).append($('<a/>', {
        "class": 'lb-close'
      }).append($('<img/>', {
        src: this.options.fileCloseImage
      }))))))).appendTo($('body'));
      $('#lightboxOverlay').hide().on('click', function(e) {
        _this.end();
        return false;
      });
      $lightbox = $('#lightbox');
      $lightbox.hide().on('click', function(e) {
        if ($(e.target).attr('id') === 'lightbox') _this.end();
        return false;
      });
      $lightbox.find('.lb-outerContainer').on('click', function(e) {
        if ($(e.target).attr('id') === 'lightbox') _this.end();
        return false;
      });
      $lightbox.find('.lb-prev').on('click', function(e) {
        _this.changeImage(_this.currentImageIndex - 1);
        return false;
      });
      $lightbox.find('.lb-next').on('click', function(e) {
        _this.changeImage(_this.currentImageIndex + 1);
        return false;
      });
      $lightbox.find('.lb-loader, .lb-close').on('click', function(e) {
        _this.end();
        return false;
      });
    };

    Lightbox.prototype.start = function($link) {
      var $lightbox, $window, a, i, imageNumber, left, top, _len, _ref;
      $(window).on("resize", this.sizeOverlay);
      $('select, object, embed').css({
        visibility: "hidden"
      });
      $('#lightboxOverlay').width($(document).width()).height($(document).height()).fadeIn(this.options.fadeDuration);
      this.album = [];
      imageNumber = 0;
      if ($link.attr('class') === 'lightbox') {
        this.album.push({
          link: $link.attr('href'),
          title: $link.attr('title')
        });
      } else {
        _ref = $($link.prop("tagName") + '[class="' + $link.attr('class') + '"]');
        for (i = 0, _len = _ref.length; i < _len; i++) {
          a = _ref[i];
          this.album.push({
            link: $(a).attr('href'),
            title: $(a).attr('title')
          });
          if ($(a).attr('href') === $link.attr('href')) imageNumber = i;
        }
      }
      $window = $(window);
      top = $window.scrollTop() + $window.height() / 10;
      left = $window.scrollLeft();
      $lightbox = $('#lightbox');
      $lightbox.css({
        top: top + 'px',
        left: left + 'px'
      }).fadeIn(this.options.fadeDuration);
      this.changeImage(imageNumber);
    };

    Lightbox.prototype.changeImage = function(imageNumber) {
      var $image, $lightbox, preloader,
        _this = this;
      this.disableKeyboardNav();
      $lightbox = $('#lightbox');
      $image = $lightbox.find('.lb-image');
      this.sizeOverlay();
      $('#lightboxOverlay').fadeIn(this.options.fadeDuration);
      $('.loader').fadeIn('slow');
      $lightbox.find('.lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption').hide();
      $lightbox.find('.lb-outerContainer').addClass('animating');
      preloader = new Image;
      preloader.onload = function() {
        $image.attr('src', _this.album[imageNumber].link);
        $image.width = preloader.width;
        $image.height = preloader.height;
        return _this.sizeContainer(preloader.width, preloader.height);
      };
      preloader.src = this.album[imageNumber].link;
      this.currentImageIndex = imageNumber;
    };

    Lightbox.prototype.sizeOverlay = function() {
      return $('#lightboxOverlay').width($(document).width()).height($(document).height());
    };

    Lightbox.prototype.sizeContainer = function(imageWidth, imageHeight) {
      var $container, $lightbox, $outerContainer, containerBottomPadding, containerLeftPadding, containerRightPadding, containerTopPadding, newHeight, newWidth, oldHeight, oldWidth,
        _this = this;
      $lightbox = $('#lightbox');
      $outerContainer = $lightbox.find('.lb-outerContainer');
      oldWidth = $outerContainer.outerWidth();
      oldHeight = $outerContainer.outerHeight();
      $container = $lightbox.find('.lb-container');
      containerTopPadding = parseInt($container.css('padding-top'), 10);
      containerRightPadding = parseInt($container.css('padding-right'), 10);
      containerBottomPadding = parseInt($container.css('padding-bottom'), 10);
      containerLeftPadding = parseInt($container.css('padding-left'), 10);
      newWidth = imageWidth + containerLeftPadding + containerRightPadding;
      newHeight = imageHeight + containerTopPadding + containerBottomPadding;
      if (newWidth !== oldWidth && newHeight !== oldHeight) {
        $outerContainer.animate({
          width: newWidth,
          height: newHeight
        }, this.options.resizeDuration, 'swing');
      } else if (newWidth !== oldWidth) {
        $outerContainer.animate({
          width: newWidth
        }, this.options.resizeDuration, 'swing');
      } else if (newHeight !== oldHeight) {
        $outerContainer.animate({
          height: newHeight
        }, this.options.resizeDuration, 'swing');
      }
      setTimeout(function() {
        $lightbox.find('.lb-dataContainer').width(newWidth);
        $lightbox.find('.lb-prevLink').height(newHeight);
        $lightbox.find('.lb-nextLink').height(newHeight);
        _this.showImage();
      }, this.options.resizeDuration);
    };

    Lightbox.prototype.showImage = function() {
      var $lightbox;
      $lightbox = $('#lightbox');
      $lightbox.find('.lb-loader').hide();
      $lightbox.find('.lb-image').fadeIn('slow');
      this.updateNav();
      this.updateDetails();
      this.preloadNeighboringImages();
      this.enableKeyboardNav();
    };

    Lightbox.prototype.updateNav = function() {
      var $lightbox;
      $lightbox = $('#lightbox');
      $lightbox.find('.lb-nav').show();
      if (this.currentImageIndex > 0) $lightbox.find('.lb-prev').show();
      if (this.currentImageIndex < this.album.length - 1) {
        $lightbox.find('.lb-next').show();
      }
    };

    Lightbox.prototype.updateDetails = function() {
      var $lightbox,
        _this = this;
      $lightbox = $('#lightbox');
      if (typeof this.album[this.currentImageIndex].title !== 'undefined' && this.album[this.currentImageIndex].title !== "") {
        $lightbox.find('.lb-caption').html(this.album[this.currentImageIndex].title).fadeIn('fast');
      }
      if (this.album.length > 1) {
        $lightbox.find('.lb-number').html(this.options.labelImage + ' ' + (this.currentImageIndex + 1) + ' ' + this.options.labelOf + '  ' + this.album.length).fadeIn('fast');
      } else {
        $lightbox.find('.lb-number').hide();
      }
      $lightbox.find('.lb-outerContainer').removeClass('animating');
      $lightbox.find('.lb-dataContainer').fadeIn(this.resizeDuration, function() {
        return _this.sizeOverlay();
      });
    };

    Lightbox.prototype.preloadNeighboringImages = function() {
      var preloadNext, preloadPrev;
      if (this.album.length > this.currentImageIndex + 1) {
        preloadNext = new Image;
        preloadNext.src = this.album[this.currentImageIndex + 1].link;
      }
      if (this.currentImageIndex > 0) {
        preloadPrev = new Image;
        preloadPrev.src = this.album[this.currentImageIndex - 1].link;
      }
    };

    Lightbox.prototype.enableKeyboardNav = function() {
      $(document).on('keyup.keyboard', $.proxy(this.keyboardAction, this));
    };

    Lightbox.prototype.disableKeyboardNav = function() {
      $(document).off('.keyboard');
    };

    Lightbox.prototype.keyboardAction = function(event) {
      var KEYCODE_ESC, KEYCODE_LEFTARROW, KEYCODE_RIGHTARROW, key, keycode;
      KEYCODE_ESC = 27;
      KEYCODE_LEFTARROW = 37;
      KEYCODE_RIGHTARROW = 39;
      keycode = event.keyCode;
      key = String.fromCharCode(keycode).toLowerCase();
      if (keycode === KEYCODE_ESC || key.match(/x|o|c/)) {
        this.end();
      } else if (key === 'p' || keycode === KEYCODE_LEFTARROW) {
        if (this.currentImageIndex !== 0) {
          this.changeImage(this.currentImageIndex - 1);
        }
      } else if (key === 'n' || keycode === KEYCODE_RIGHTARROW) {
        if (this.currentImageIndex !== this.album.length - 1) {
          this.changeImage(this.currentImageIndex + 1);
        }
      }
    };

    Lightbox.prototype.end = function() {
      this.disableKeyboardNav();
      $(window).off("resize", this.sizeOverlay);
      $('#lightbox').fadeOut(this.options.fadeDuration);
      $('#lightboxOverlay').fadeOut(this.options.fadeDuration);
      return $('select, object, embed').css({
        visibility: "visible"
      });
    };

    return Lightbox;

  })();

  $(function() {
    var lightbox, options;
    options = new LightboxOptions;
    return lightbox = new Lightbox(options);
  });

}).call(this);
