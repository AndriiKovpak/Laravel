const mix = require('laravel-mix');

mix.copy("resources/bower_components/animate.css/animate.css", "resources/assets/css/vendor/animate.css")
    .copy("resources/bower_components/font-awesome/css/font-awesome.css", "resources/assets/css/vendor/font-awesome.css")
    .copy("resources/bower_components/tether/dist/css/tether.css", "resources/assets/css/vendor/tether.css")
    .copy("resources/bower_components/EasyAutocomplete/dist/easy-autocomplete.min.css", "resources/assets/css/vendor/easy-autocomplete.min.css")
    .copy('resources/bower_components/jquery-ui/themes/base/jquery-ui.min.css', 'resources/assets/css/vendor/jquery-ui.min.css')
    .copy("resources/bower_components/jquery-ui/themes/base/images", "resources/assets/images")
    .copy("resources/bower_components/jquery/dist/jquery.js", "resources/assets/js/vendor/jquery.js")
    .copy("resources/bower_components/bootstrap/dist/js/bootstrap.js", "resources/assets/js/vendor/bootstrap.js")
    .copy("resources/bower_components/jquery-ui/jquery-ui.js", "resources/assets/js/vendor/jquery-ui.js")
    .copy("resources/bower_components/respond/dest/respond.src.js", "resources/assets/js/vendor/respond.js")
    .copy("resources/bower_components/bloodhound/dist/bloodhound.min.js", "resources/assets/js/vendor/bloodhound.js")
    .copy("resources/bower_components/multiselect-two-sides/dist/js/multiselect.js", "resources/assets/js/vendor/multiselect.js")
    .copy("resources/bower_components/cleave.js/dist/cleave.min.js", "resources/assets/js/vendor/cleave.js")
    .copy("resources/bower_components/tether/dist/js/tether.js", "resources/assets/js/vendor/tether.js")
    .copy("resources/bower_components/EasyAutocomplete/dist/jquery.easy-autocomplete.min.js", "resources/assets/js/vendor/jquery.easy-autocomplete.min.js")
    .copy("resources/bower_components/font-awesome/fonts", "public/assets/fonts")
    .copy("resources/assets/images", "public/assets/images")

    .combine([
        "resources/assets/js/vendor/jquery.js",
        "resources/assets/js/vendor/bloodhound.js",
        "resources/assets/js/vendor/cleave.js",
        "resources/assets/js/vendor/tether.js",
        "resources/assets/js/vendor/bootstrap.js",
        "resources/assets/js/vendor/respond.js",
        "resources/assets/js/vendor/jquery-ui.js",
        "resources/assets/js/vendor/multiselect.js",
        "resources/assets/js/vendor/jquery.easy-autocomplete.min.js",
        "resources/assets/js/jquery-input-mask-phone-number.min.js",
        "resources/assets/js/jquery-input-mask-phone-number.js",
        "resources/assets/js/main.js",
        "resources/assets/js/reports.js",
        "resources/assets/js/inventory.js",
        "resources/assets/js/settings.js",
        "resources/assets/js/carriers.js",
        "resources/assets/js/invoices.js",
        "resources/assets/js/users.js",
        "resources/assets/js/orders.js",

    ], 'public/js/all.js', true)
    .combine([
        "resources/assets/css/vendor/*.css",
        "resources/assets/css/*.css"
    ], 'public/css/vendor.css')
    .sass('resources/assets/sass/app.scss', 'public/css/all.css')
    .sourceMaps();
