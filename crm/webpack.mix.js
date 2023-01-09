let mix = require("laravel-mix");
const ReplaceInFileWebpackPlugin = require("replace-in-file-webpack-plugin");
const fs = require("fs");

mix
  .options({
    // Don't perform any css url rewriting by default
    processCssUrls: false,
  })
  .sourceMaps(false)
  .disableNotifications();

mix.postCss("resources/css/tailwind.css", "assets/builds", [
  require("postcss-import"),
  require("tailwindcss/nesting")(require("postcss-nesting")),
  require("tailwindcss"),
  require("autoprefixer"),
]);

mix.minify([
  "assets/plugins/internal/google-picker/picker.js",
  "assets/plugins/internal/validation/app-form-validation.js",
  "assets/plugins/internal/highlight/highlight.js",
  "assets/plugins/internal/hotkeys/hotkeys.js",
  "assets/plugins/internal/desktop-notifications/notifications.js",
  "assets/themes/perfex/js/clients.js",
  "assets/themes/perfex/js/global.js",
  "assets/js/main.js",
  "assets/js/map.js",
  "assets/js/projects.js",
  "assets/js/tickets.js",
  "assets/plugins/tagsinput/js/tag-it.js", // tag-it.js is modified
]);

mix.combine(
  [
    "assets/plugins/jquery/jquery.min.js",
    "assets/plugins/jquery-ui/jquery-ui.min.js",
    "assets/plugins/metisMenu/metisMenu.min.js",
    "assets/plugins/readmore/readmore.min.js",
    "assets/plugins/bootstrap/js/bootstrap.min.js",
    "assets/plugins/tagsinput/js/tag-it.js",
    "assets/plugins/jquery.are-you-sure/jquery.are-you-sure.js",
    "assets/plugins/jquery.are-you-sure/ays-beforeunload-shim.js",
    "assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js",
    "assets/plugins/dropzone/min/dropzone.min.js",
    "assets/plugins/Chart.js/Chart.min.js",
    "assets/plugins/datetimepicker/jquery.datetimepicker.full.min.js",
    "assets/plugins/internal/hotkeys/hotkeys.js",
    "assets/plugins/internal/desktop-notifications/notifications.js",
    "assets/plugins/lightbox/js/lightbox.min.js",
    "assets/plugins/accounting.js/accounting.min.js",
  ],
  "assets/builds/vendor-admin.js"
);

mix.combine(
  [
    "assets/plugins/internal/validation/app-form-validation.min.js",
    "assets/plugins/internal/highlight/highlight.js",
    "assets/plugins/internal/google-picker/picker.min.js",
    "assets/plugins/waypoint/jquery.waypoints.min.js",
    "assets/plugins/internal/bootstrap-nav-tabs-scrollable/bootstrap-nav-tab-scrollable.js",
    "assets/js/app.js",
  ],
  "assets/builds/common.js"
);

mix.combine(
  [
    "assets/plugins/bootstrap-select/js/bootstrap-select.min.js",
    "assets/plugins/bootstrap-select-ajax/js/ajax-bootstrap-select.min.js",
  ],
  "assets/builds/bootstrap-select.min.js"
);

mix.combine(
  [
    "assets/plugins/moment/moment-with-locales.min.js",
    "assets/plugins/moment-timezone/moment-timezone-with-data-10-year-range.min.js",
  ],
  "assets/builds/moment.min.js"
);

mix.combine(
  [
    "assets/plugins/bootstrap/css/bootstrap.min.css",
    "assets/plugins/datatables/datatables.min.css",
    "assets/plugins/jquery-ui/jquery-ui.min.css",
    "assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css",
    "assets/plugins/dropzone/min/dropzone.min.css",
    "assets/plugins/bootstrap-select/css/bootstrap-select.min.css",
    "assets/plugins/bootstrap-select-ajax/css/ajax-bootstrap-select.min.css",
    "assets/plugins/tagsinput/css/jquery.tagit.css",
    "assets/plugins/tagsinput/css/tagit.ui-zendesk.css",
    "assets/plugins/datetimepicker/jquery.datetimepicker.min.css",
    "assets/plugins/lightbox/css/lightbox.min.css",
    // "assets/plugins/internal/bootstrap-nav-tabs-scrollable/bootstrap-nav-tab-scrollable.css",
  ],
  "assets/builds/vendor-admin.css"
);

mix.webpackConfig((webpack) => {
  return {
    plugins: [
      new ReplaceInFileWebpackPlugin([
        {
          files: ["assets/builds/vendor-admin.css"],
          rules: [
            {
              search: /img\/bootstrap-colorpicker/g,
              replace:
                "plugins/bootstrap-colorpicker/img/bootstrap-colorpicker",
            },
            {
              search: /images\/ui-/g,
              replace: "../plugins/jquery-ui/images/ui-",
            },
            {
              search: /..\/fonts\/glyphicons-/g,
              replace: "../plugins/bootstrap/fonts/glyphicons-",
            },
            // lightbox
            {
              search: /..\/images\/close/g,
              replace: "../plugins/lightbox/images/close",
            },
            {
              search: /..\/images\/loading/g,
              replace: "../plugins/lightbox/images/loading",
            },
            {
              search: /..\/images\/next/g,
              replace: "../plugins/lightbox/images/next",
            },
            {
              search: /..\/images\/prev/g,
              replace: "../plugins/lightbox/images/prev",
            },
          ],
        },
        // {
        //   files: [
        //     "assets/plugins/tinymce/skins/lightgray/skin.min.css",
        //     "assets/plugins/tinymce/skins/lightgray/skin.mobile.min.css",
        //   ],
        //   rules: [
        //     {
        //       search: /\/\*# sourceMappingURL=skin\.min\.css\.map \*\//,
        //       replace: "",
        //     },
        //     {
        //       search: /\/\*# sourceMappingURL=skin\.mobile\.min\.css\.map \*\//,
        //       replace: "",
        //     },
        //   ],
        // },
        // {
        //   files: ["assets/plugins/datatables/datatables.min.js"],
        //   rules: [
        //     {
        //       search: /\/\/# sourceMappingURL=pdfmake\.min\.js\.map/,
        //       replace: "",
        //     },
        //   ],
        // },
      ]),
    ],
  };
});

mix.minify([
  "assets/themes/perfex/css/style.css",
  "assets/css/forms.css",
  "assets/css/style.css",
  "assets/css/reset.css",
]);

if (mix.inProduction()) {
  mix.after(() => {
    let migrationFile = fs.readFileSync("./application/config/migration.php");
    let versionRegex = /(\['migration_version'\] = )(\d+;) (\/\/) (\d.\d.\d)/gm;
    let versionConfig = versionRegex.exec(migrationFile)[4];

    [
      "assets/js/main.min.js",
      "assets/js/map.min.js",
      "assets/js/projects.min.js",
      "assets/js/tickets.min.js",
      "assets/themes/perfex/js/clients.min.js",
      "assets/themes/perfex/js/global.min.js",
    ].forEach((headerableFile) => {
      const data = fs.readFileSync(headerableFile);
      const fd = fs.openSync(headerableFile, "w+");
      const insert = Buffer.from("/* " + versionConfig + " */ \n");
      fs.writeSync(fd, insert, 0, insert.length, 0);
      fs.writeSync(fd, data, 0, data.length, insert.length);
      fs.close(fd, (err) => {
        if (err) throw err;
      });
    });
  });
}
