var fs    = require('fs'),
    path  = require('path'),
    less  = require('less'),
    error = false,
    src   = 'public/admin/assets/less/styles.less';

console.log('Compiling less files...');

less.logger.addListener({
  debug: function(msg) {
    console.log('Debug: '+msg);
  },
  info: function(msg) {
    console.log('Info: '+msg);
  },
  warn: function(msg) {
    console.log('Warn: '+msg);
  },
  error: function(msg) {
    error = true;
    console.log('Error: '+msg);
  }
});

less.render(fs.readFileSync(src).toString(), {
  paths: ['.','/base'],
  filename: path.resolve(src),
  compress: true
}, function(e, output) {
  if(error != true){
    console.log('Compile successful, writing file...');
    fs.writeFile('public/admin/css/theme.css', output.css ,function (err,data) {
      if (err) {
        console.log('Write failed...');
        return console.log(err);
      } else{
        console.log('Write completed...');
      }
    });
  }else{
    console.log('Compile failed...');
  }
});