require("../scss/custom.scss");
require("bootstrap/scss/bootstrap.scss");
require("slick-carousel/slick/slick-theme.scss");
require("slick-carousel/slick/slick.scss");
require("../scss/main.scss");
var req = require.context("../img", true);
req.keys().forEach(function (key) {
    req(key);
});

import 'jquery';
import 'jquery-migrate';
import 'popper.js';
import 'bootstrap';
import 'slick-carousel';
import 'ekko-lightbox';