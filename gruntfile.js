module.exports = function(grunt) {
    "use strict";

    grunt.initConfig({
        path: 'bower_components/bootstrap',

        clean: {
            css: ['app/assets/css/*', 'public/css/*', '!app/assets/css/*.less'],
            js: ['app/assets/js/*.min.js']
        },

        less: {
            options: {
                strictMath: true,
                strictUnits: true,
                compress: true,
                paths: '<%= path %>/less'
            },

            all: {
                files: {
                    "app/assets/css/main.css": "app/assets/css/main.less",
                    "app/assets/css/login.css": "app/assets/css/login.less",
                    "app/assets/css/visualize.css": "app/assets/css/visualize.less"
                }
            }
        },

        cssmin: {
            all: {
                files: [{
                    expand: true,
                    cwd: 'app/assets/css',
                    src: ['*.css', '!*.min.css'],
                    dest: 'public/css',
                    ext: '.min.css'
                }]
            }
        },

        concat: {
            options: {
                separator: ';'
            },
            release: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js'
                ],
                // TODO: Select only necessary
                dest: 'app/assets/js/webcfinder.js'
            }
        },

        uglify: {
            options: {
                compress: true,
                screwIE8: true
            },

            all: {
                files: {
                    'public/js/webcfinder.min.js': ['app/assets/js/webcfinder.js'],
                    'public/js/vstart.min.js': ['app/assets/js/vstart.js']
                }
            }
        },

        watch: {
            files: [
                'app/assets/css/main.less',
                'app/assets/css/login.less',
                'app/assets/css/visualize.less'
            ],
            tasks: ['dist-css']
        }

    });

    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-contrib-watch');

    // CSS distribution task
    grunt.registerTask('dist-css', ['clean:css', 'less:all', 'cssmin']);

    // JS distribution task
    grunt.registerTask('dist-js', ['clean:js', 'concat:release', 'uglify:all']);

    grunt.registerTask('default', ['dist-css', 'dist-js']);
};
