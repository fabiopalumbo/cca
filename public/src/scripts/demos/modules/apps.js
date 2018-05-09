angular.module('theme.demos.apps', ['angular-meditor'])
  .directive('scrumTasks', function () {
    return {
      restrict: 'A',
      priority: -1,
      link: function (scope, element, attr) {

        angular.element('.checklist-toggler').click(function () {
          if ((angular.element(this).parents('.card-checklist').children('.checklist-container').css('display'))=="none") {
            angular.element(this).parents('.card-checklist').children('.checklist-container').slideDown({duration:200});
            angular.element(this).children('.fa').toggleClass('fa-angle-down fa-angle-left');
          } else {
            angular.element(this).parents('.card-checklist').children('.checklist-container').slideUp({duration:200});
            angular.element(this).children('.fa').toggleClass('fa-angle-down fa-angle-left');
          }
        });

        //ID of list
        //Give ID to each nestable list and allow dragging between them
        angular.element(attr.nestableIds).nestable({group: 1});

        //ID of Cards UL
        console.log(attr.cardIds);
        angular.element(attr.cardIds).sortable({
          connectWith: ".sortable-connected",
          receive: function (event, ui) {
            var lists = angular.element('.sortable-connected');
            for (var i = lists.length - 1; i >= 0; i--) {
              if (angular.element(lists[i]).children().length < 1) angular.element(lists[i]).html('');
            };
          }
        });

          angular.element('.card-task input').on('ifChanged', function () {
            var total = angular.element(this).closest('.card-task').find('input').length;
            var checked = angular.element(this).closest('.card-task').find('input:checked').length;
            angular.element(this).closest('.card-task').find('.card-done').html(checked+'/'+total);
            angular.element(this).closest('.card-task').find('.progress-bar').css("width", (checked/total)*100+'%');
          });

          angular.element('.card-task').each( function () {
            var total = angular.element(this).find('input').length;
            var checked = angular.element(this).find('input:checked').length;
            angular.element(this).find('.card-done').html(checked+'/'+total);
            angular.element(this).find('.progress-bar').css("width", (checked/total)*100+'%');
          });

          angular.element('.card-task .card-options .toggle-check').click( function () {
            if (angular.element(this).find('i').hasClass('fa-check')) {
              angular.element(this).closest('.card-task').find('div.icheckbox_minimal-blue:not(.checked) .iCheck-helper').click();
              angular.element(this).find('i').removeClass('fa-check').addClass('fa-undo');
            }
            else {
              angular.element(this).closest('.card-task').find('div.icheckbox_minimal-blue.checked .iCheck-helper').click();
              angular.element(this).find('i').removeClass('fa-undo').addClass('fa-check');
            }
          });

      }
    }
  })

  .controller('notesController', ['$scope', function ($scope) {
    $scope.notes = [{
        content: 'This is simple notes application. Whatever you write here will automatically be saved on the browser. <br>Try selecting the text to format it. <br> <br>Have fun! :)',
        createdAt: '10/01/2015 at 11:00 PM'
    }, {
        content: 'Mei graeco dolores liberavisse et. Recusabo repudiare ad pro, eu commune cotidieque eum. Case labitur moderatius at eam, vix no nulla omnesque. Ne mel altera facete assentior, eam aperiam hendrerit te, mei in illum noster eligendi. Vim ei perpetua pertinacia, ei vis odio facer appellantur, tation fabellas ad vel. <br> <br>Nam erant tantas mediocrem ne, urbanitas persequeris at nec, nulla veniam qui et. Decore deleniti verterem eos no, vidisse lobortis nec ex, elit libris intellegebat nec ex. Docendi molestiae gubergren ea mea, ius wisi ludus cu, prompta tractatos mnesarchum eu vel. Ei movet discere gloriatur per. Est partiendo vulputate in, brute laudem nominavi sea ne. <br> <br>Et sea erant impedit, cu pri docendi accusamus repudiare. Delenit expetenda consetetur eam no, vim eu magna nusquam, no qui volutpat accommodare. Usu at eros voluptaria adversarium. Explicari disputationi cum ad, cu usu inermis repudiare interpretaris, has an case affert virtute. Dicant impedit an nam, pri ei malis quodsi. Ei veritus hendrerit vis, probo munere efficiendi an ius. <br> <br>Lorem ipsum dolor sit amet, posse tacimates et vel, cu malis platonem sed. Vocent gloriatur no duo. Cu mucius mollis usu, harum deterruisset eam no. Velit timeam at vim, graeci petentium sit et. Mel utamur dolores at. Causae cotidieque cu vel. Vim convenire hendrerit eu.',
        createdAt: '10/02/2014 at 01:30 AM'
    }];
  }])

  .directive('notesApp', ['$timeout', function ($timeout) {
    return {
      restrict: 'A',
      scope: {
        notes: '='
      },
      link: function (scope, element, attr) {

        var getViewPort = function() {
            var e = window, a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        }

        var noteHeight = function () {
            var h = getViewPort().height;
            var tOffset = angular.element('.note').offset().top;
            var t = h - tOffset; //removing size from top

            var f = (angular.element('footer').height() + parseInt(angular.element('.static-content').css('margin-bottom').replace('px', '')));
            var t = t - f - 11; //removing size from bottom

            return t;
        }

        var noteListHeight = function () {
            var t = noteHeight() - angular.element('.notes-options').outerHeight() - angular.element('.notes-search').outerHeight();
            return t;
        }

        function resizeNotes() { //change height of scroll based on sidebar viewport height
            angular.element('.note').css('height', noteHeight() + 'px')
            angular.element('.notes-list').css('height', noteListHeight() + 'px')
        }

        angular.element(document).ready(function() {

            var notes = [];
            var noteSelected = 0;
            var demoNotes = scope.notes || [{
                content: 'This is simple notes application. Whatever you write here will automatically be saved on the browser. <br>Try selecting the text to format it. <br> <br>Have fun! :)',
                createdAt: '10/01/2014 at 11:00 PM'
            }, {
                content: 'Mei graeco dolores liberavisse et. Recusabo repudiare ad pro, eu commune cotidieque eum. Case labitur moderatius at eam, vix no nulla omnesque. Ne mel altera facete assentior, eam aperiam hendrerit te, mei in illum noster eligendi. Vim ei perpetua pertinacia, ei vis odio facer appellantur, tation fabellas ad vel. <br> <br>Nam erant tantas mediocrem ne, urbanitas persequeris at nec, nulla veniam qui et. Decore deleniti verterem eos no, vidisse lobortis nec ex, elit libris intellegebat nec ex. Docendi molestiae gubergren ea mea, ius wisi ludus cu, prompta tractatos mnesarchum eu vel. Ei movet discere gloriatur per. Est partiendo vulputate in, brute laudem nominavi sea ne. <br> <br>Et sea erant impedit, cu pri docendi accusamus repudiare. Delenit expetenda consetetur eam no, vim eu magna nusquam, no qui volutpat accommodare. Usu at eros voluptaria adversarium. Explicari disputationi cum ad, cu usu inermis repudiare interpretaris, has an case affert virtute. Dicant impedit an nam, pri ei malis quodsi. Ei veritus hendrerit vis, probo munere efficiendi an ius. <br> <br>Lorem ipsum dolor sit amet, posse tacimates et vel, cu malis platonem sed. Vocent gloriatur no duo. Cu mucius mollis usu, harum deterruisset eam no. Velit timeam at vim, graeci petentium sit et. Mel utamur dolores at. Causae cotidieque cu vel. Vim convenire hendrerit eu.',
                createdAt: '10/02/2014 at 01:30 AM'
            }];

            if (localStorage) {
                notes = JSON.parse(localStorage.getItem('jj.notes')) || demoNotes;
            } else {
                notes = demoNotes;
            }

            var noteLiHtml = '<div class="notes-snippet">'+
                '<h6 class="notes-title">%title%</h6>'+
                '<span class="notes-date">%createdAt%</span>'+
            '</div>';

            var updateNoteList = function (notes) {
                var noteList = '';
                for (var i = 0, length = notes.length; i < length; i++) {
                    var titleElement = document.createElement("DIV");
                    angular.element(titleElement).html(notes[i].content);
                    var title = angular.element.trim(angular.element(titleElement).text());
                    noteList += noteLiHtml.replace('%title%', title.substr(0,20)+'...').replace('%createdAt%', notes[i].createdAt);
                };

                angular.element('div.notes-list > div').html(noteList);

                // add active class to open note
                angular.element('.notes-snippet').removeClass('active');
                angular.element('.notes-snippet').eq(noteSelected).addClass('active');
            };

            updateNoteList(notes);

            var openNote = function (noteIndex) {
                try {
                    noteSelected = noteIndex;
                    angular.element('.note > .note-content .angular-meditor-content').html(notes[noteIndex].content);
                    // add active class to open note
                    angular.element('.notes-snippet').removeClass('active');
                    angular.element('.notes-snippet').eq(noteIndex).addClass('active');
                } catch (e) {
                    //ignore all errors
                }
            };

            angular.element('body').on('click', '.notes-snippet', function () {
                openNote(angular.element(this).index());
            });

            setTimeout(function () {
              openNote(0);
            }, 500)

            angular.element('.notes-options .btn-success').click(function () {
                notes.push( {
                    content: '',
                    createdAt: '9/23/2014 at 11:32 PM'
                });
                updateNoteList(notes);
                openNote(notes.length-1);
                if (localStorage) localStorage.setItem('jj.notes', JSON.stringify(notes));

                // Utility.initScroller();
            });

            angular.element('.notes-options .btn-danger').click(function () {
                notes.splice(noteSelected, 1);
                updateNoteList(notes);
                if (notes.length)
                    openNote(notes.length-1);
                if (localStorage) localStorage.setItem('jj.notes', JSON.stringify(notes));
            });

            setTimeout(function () {
              angular.element('.note > .note-content .angular-meditor-content')[0].addEventListener('input', function () {
                  notes[noteSelected].content = angular.element('.note').html();
                  if (localStorage) localStorage.setItem('jj.notes', JSON.stringify(notes));
                  updateNoteList(notes);
                  // Utility.initScroller();
              }, false);
            }, 500);

            resizeNotes(angular.element('.notes-list'), noteListHeight());
            resizeNotes(angular.element('.note'), noteHeight());
            

            // angular.element('.note > .scroll-content').summernote({
            //     airMode: true
            // });
        });

        angular.element(window).on('resize', function() {
            resizeNotes();
        });

        $timeout(function () {
          resizeNotes();
        }, 10);
      }
    }
  }])

  .directive('todoApp', function () {
    return {
      restrict: 'A',
      link: function(scope, element, attr) {
        angular.element("#sortable-todo, #completed-todo").sortable({
              connectWith: ".connectedSortable",
              receive: function (event, ui) {
                angular.element(ui.item[0]).find('input')[0].dropped = true;
                angular.element(ui.item[0]).find('.iCheck-helper').click()
              }
            }).disableSelection();

        angular.element('#sortable-todo input, #completed-todo input').on('ifChanged', function () {
            if (angular.element(this)[0].dropped == true) { angular.element(this)[0].dropped = false; return; }
            if (angular.element(this).closest('#sortable-todo').length)
                angular.element(this).closest('li').appendTo('#completed-todo');
            else
                angular.element(this).closest('li').appendTo('#sortable-todo');
        });
      }
    }
  })