require("../scss/custom.scss");
require("../scss/bootstrap.scss");
require("bootstrap/scss/bootstrap.scss");
require("slick-carousel/slick/slick-theme.scss");
require("slick-carousel/slick/slick.scss");
require("../scss/main.scss");
require('./myfont.font');
var req = require.context("../img", true);
req.keys().forEach(function (key) {
    req(key);
});

import 'jquery';
import 'greensock'
import 'jquery-migrate';
import 'popper.js';
import 'bootstrap';
import 'slick-carousel';
import 'ekko-lightbox';

// require("../vendor/layerslider/js/layerslider.transitions.js");
// require("../vendor/layerslider/js/layerslider.kreaturamedia.jquery.js");
// require("../vendor/layerslider/plugins/origami/layerslider.origami");
// require("../vendor/layerslider/plugins/popup/layerslider.popup");
// require("../vendor/layerslider/plugins/timeline/layerslider.timeline");

require("./main.js");