var fs = require('fs');
var gulp = require('gulp');

gulp.task('build', function(cb) {
  fs.writeFile('hello_gulp.txt', 'Hello world from gulp.', cb);
});
