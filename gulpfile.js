var gulp = require('gulp')
  , concat = require('gulp-concat')
  , uglify = require('gulp-uglify')
  , ngmin = require('gulp-ngmin')
  , minifyCSS = require('gulp-minify-css')
  , replace = require('gulp-replace')
  , fs = require('fs')
  , stamp = (new Date()).getTime()
  , script_dest = 'public/static/scripts'
  , style_dest = 'public/static/styles'
  , changeInclusion = function (target, prefix, style) {
    var extension = style ? 'css' : 'js'
      , dest = style ? style_dest : script_dest
      , expression = new RegExp(dest.replace('public/', '\'') + '/' + prefix + '.+' + extension)
      , newPath = dest.replace('public/', '\'') + '/' + prefix + stamp + '.min.' + extension;
    console.log('reading', target);
    fs.readFile(target, 'utf8', function (err, data) {
      if (err) {
        return console.log(err);
      }
      var result = data.replace(expression, newPath);
      fs.writeFile(target, result, 'utf8', function (err) {
        if (err) return console.log(err);
        return console.log('done', target);
      });
      return console.log('writing', target);
    });
  };

gulp.task('clean', function () {
  try {
    var scripts = fs.readdirSync(script_dest);
    var styles = fs.readdirSync(style_dest);
  }
  catch (e) {
    console.log(e);
    return;
  }
  if (scripts.length > 0)
    for (var i = 0; i < scripts.length; i++) {
      console.log(scripts[i].indexOf(stamp + '.min.js') == -1 ? 'delete' : 'isNew', scripts[i], stamp);
      if (scripts[i].indexOf(stamp + '.min.js') == -1) {
        fs.unlinkSync(script_dest + '/' + scripts[i]);
      }
    }
  if (styles.length > 0)
    for (var j = 0; j < styles.length; j++) {
      console.log(styles[j].indexOf(stamp) == -1 ? 'delete' : 'isNew', styles[j], stamp);
      if (styles[j].indexOf(stamp) == -1) {
        fs.unlinkSync(style_dest + '/' + styles[j]);
      }
    }
});

// Concatenate & Minify Own Admin App JS (with angular)
gulp.task('scripts_bower', function () {
  var newName = 'bower' + stamp;
  gulp.src([
    "public/bower_components/jquery/dist/jquery.js",
    "public/bower_components/underscore/underscore.js",
    "public/bower_components/lodash/dist/lodash.js",
    "public/bower_components/jquery/dist/jquery.js",
    "public/bower_components/angular/angular.js",
    "public/bower_components/angular-route/angular-route.js",
    "public/bower_components/bootstrap/dist/js/bootstrap.min.js",
    "public/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js",
    "public/bower_components/angular-growl/build/angular-growl.js",
    "public/bower_components/restangular/dist/restangular.js"
  ])
    .pipe(concat(newName + '.min.js'))
    .pipe(ngmin())
    .pipe(uglify())
    .pipe(gulp.dest(script_dest));
  changeInclusion('resources/views/app/admin.blade.php', 'bower');
});

// Concatenate & Minify Own Admin App JS (with angular)
gulp.task('scripts_admin', function () {
  var newName = 'scripts' + stamp;
  gulp.src([
    "public/admin-app/scripts/app.js",
    "public/admin-app/scripts/controllers/main/main.js",
    "public/admin-app/services/globalFactory.js",
    "public/admin-app/scripts/controllers/crud/directive-confirm-button.js",
    "public/admin-app/scripts/controllers/home.js",
    "public/admin-app/scripts/controllers/login/login-controller.js",
    "public/admin-app/scripts/controllers/register/register-controller.js",
    "public/admin-app/scripts/controllers/profile/profile.js",
    "public/admin-app/scripts/controllers/profile/controller-modal-modificar-datos-usuario.js",
    "public/admin-app/scripts/controllers/modules/modules.js",
    "public/admin-app/scripts/controllers/groups/groups.js",
    "public/admin-app/scripts/controllers/users/users.js",
    "public/admin-app/scripts/controllers/stock/stock.js"
  ])
    .pipe(concat(newName + '.min.js'))
    .pipe(ngmin())
    .pipe(uglify())
    .pipe(gulp.dest(script_dest));
  changeInclusion('views/app/admin.blade.php', 'scripts');
});
gulp.task('styles_admin', function () {
  var newName = 'styles' + stamp;
  gulp.src([
    "public/bower_components/bootstrap/dist/css/bootstrap.min.css",
    "public/bower_components/font-awesome/css/font-awesome.min.css",
    "public/src/css/font.css",
    "public/src/css/plugin.css",
    "public/src/css/style.css",
    "public/src/css/estilos.css",
    "public/src/css/index.css",
    "public/src/css/styles.css"
  ])
    .pipe(concat(newName + '.min.css'))
    .pipe(minifyCSS({
      noAdvanced: true
    }))
    .pipe(gulp.dest(style_dest));
  changeInclusion('resources/views/app/admin.blade.php', 'styles', 'css');
});

// Concatenate & Minify Own Apps JS (with angular)
gulp.task("admin", ["scripts_admin", "styles_admin"]);