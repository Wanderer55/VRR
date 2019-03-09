(function ($) {
 'use strict';

  var showSpinner = function () {
    $('vrr-form').submit(function () {
      $(this).find('.vrr-spinner').addClass('is-active');
    });
  };

  var clipboard = new ClipboardJS('.vrr-instructions-shortcode-btn');
  // var clipboard1= new ClipboardJS('.vrr-instructions-shortcode-wrap');

  clipboard.on('success', function(e) {
      e.clearSelection();
  });
  // clipboard1.on('success', function(e) {
  //     e.clearSelection();
  // });

  // colorpicker
  $('input.vrr-settings-color').wpColorPicker({

  });

  var addOrReplaceParam = function(url, param, value) {
     param = encodeURIComponent(param);
     var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
     var a = document.createElement('a');
     var regex = new RegExp(r);
     var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
     a.href = url;
     var q = a.search.replace(regex, "$1"+str);
     if (q === a.search) {
        a.search += (a.search ? "&" : "") + str;
     } else {
        a.search = q;
     }
     return a.href;
  }

 /* function insertParam(key, value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; var x; while(i--) 
    {
        x = kvp[i].split('=');

        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

    //this will reload the page, it's likely better to store this until finished
    // document.location.search = kvp.join('&'); 
    var new_url = kvp.join('&');
    // console.log(new_url);
    // history.pushState(null, '', new_url);
  }*/

  $('.vrr-settings-tabs-header-btn').on('click', function(e){
    e.preventDefault();
    var tab = $(this).attr('data-tab');

    // var url = window.location;
    // var newurl = addOrReplaceParam(url, "vrr-tab", tab);
    // history.pushState(null, '', newurl);

    $('.vrr-settings-tabs-header-btn').removeClass('active');
    $(this).addClass('active');
    $('.vrr-settings-tab').hide();
    $('.vrr-settings-tab[data-tab="'+tab+'"]').show();
  });


  $('.vrr-more-time-wrap input[type="checkbox"]').on('change', function(){
    if($(this).is(":checked")){
      $(this).closest('.vrr-more-time-wrap-all').find('.vrr-more-time-selects-wrap').fadeIn(100);
    } else {
      $(this).closest('.vrr-more-time-wrap-all').find('.vrr-more-time-selects-wrap').hide();
    }
  });

  $('select[name="visual_restaurant_reservation_settings[canvas_elements_size]"]').on('change',function(){
    if($(this).val() == "medium"){
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-small');
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-big');

      $('.vrr-tables-wrap').addClass('vrr-tables-size-medium');
    } else if($(this).val() == "small"){
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-medium');
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-big');

      $('.vrr-tables-wrap').addClass('vrr-tables-size-small');
    } else if($(this).val() == "big"){
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-small');
      $('.vrr-tables-wrap').removeClass('vrr-tables-size-medium');

      $('.vrr-tables-wrap').addClass('vrr-tables-size-big');
    }

    $('.vrr-tables-wrap').addClass('vrr-loading');
    setTimeout(function() {
      $('.vrr-element').each(function(){
        var element = $(this);
        var rotate = element.attr('data-rotate');
        var q = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
        addCSStoDraw(q,element, rotate);
      });
      $('.vrr-tables-wrap').removeClass('vrr-loading');
    }, 200);
    

  });

  // $('.vrr-instructions-shortcode-wrap').on('click',function(){
  //   var self = $(this);
  //   self.addClass('clipboard');
  //   setTimeout(function() {
  //     self.removeClass("clipboard");
  //   }, 300);
  // }).children().click(function(e) {
  //   return false;
  // });

  function makeid(long) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for (var i = 0; i < long; i++) {
      text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
  }


  var intersect = function(a,b){
   return(
    (
     (
      ( a.x >= b.x && a.x <= b.x1 ) || ( a.x1 >= b.x && a.x1 <= b.x1  )
      ) && (
      ( a.y >= b.y && a.y <= b.y1 ) || ( a.y1 >= b.y && a.y1 <= b.y1 )
      )
      ) || (
      (
       ( b.x >= a.x && b.x <= a.x1 ) || ( b.x1 >= a.x && b.x1 <= a.x1  )
       ) && (
       ( b.y >= a.y && b.y <= a.y1 ) || ( b.y1 >= a.y && b.y1 <= a.y1 )
       )
       )
      ) || (
      (
       (
        ( a.x >= b.x && a.x <= b.x1 ) || ( a.x1 >= b.x && a.x1 <= b.x1  )
        ) && (
        ( b.y >= a.y && b.y <= a.y1 ) || ( b.y1 >= a.y && b.y1 <= a.y1 )
        )
        ) || (
        (
         ( b.x >= a.x && b.x <= a.x1 ) || ( b.x1 >= a.x && b.x1 <= a.x1  )
         ) && (
         ( a.y >= b.y && a.y <= b.y1 ) || ( a.y1 >= b.y && a.y1 <= b.y1 )
         )
         )
        );
  }


  function refreshCoordinates(){
    coordinates = new Array();
    elements = [];
    $('.vrr-draggable .vrr-element').each(function(e){
      elements.push($(this));
    });
    // for (var k in elements){
    for (var k = 0; k <= elements.length - 1; k++) {
      coordinates[k] = {
        x: $(elements[k]).offset().left,
        y: $(elements[k]).offset().top,
        x1: $(elements[k]).offset().left + $(elements[k])[0].clientWidth,
        y1: $(elements[k]).offset().top + $(elements[k])[0].clientHeight
      }
    }
    return coordinates;
  }

  function elementIdRefresh(){
    if($('.vrr-draggable .vrr-element').length > 0){
      var id = 0;
      $('.vrr-draggable .vrr-element').each(function(e){
        var tem_id = parseInt($(this).attr('data-id'));
        if(tem_id > id){
          id = tem_id;
        }
      });
      element_id = id;
    }
  }

  var coordinates = new Array(); // coordinates of all elemnts on canvas
  var new_coordinates = new Array(); // fake coordinates for delete curent
  var coordinates_with_curent = new Array();// coordinates of all elemnts on canvas and custom curent adding
  var elements = []; // Object of elements on canvas with out curent
  var new_elements = [];
  var draggableElementCoord = new Array(); // live position of dragged element
  var revert = null; // revet whene dragged from start to canvas
  var revert_exist = true; // rever whene dragged element on canvas
  var one_time_is_done = 0; // check for one time element of array delete
  var element_id = 0;
  var dropDraggableElementCoord = new Array();

  function initDraggble(){
    coordinates = new Array();
    elements = [];

    $('.vrr-draggable .vrr-element').each(function(e){
      elements.push($(this));
    });
    // for (var k in elements){
    for (var k = 0; k <= elements.length - 1; k++) {
      coordinates[k] = {
        x: $(elements[k]).offset().left,
        y: $(elements[k]).offset().top,
        x1: $(elements[k]).offset().left + $(elements[k])[0].clientWidth,
        y1: $(elements[k]).offset().top + $(elements[k])[0].clientHeight
      }
    }

    $(".vrr-element-sidebar .vrr-element").draggable({
      // grid: [20, 20],
      helper: 'clone',
      opacity: 0.9,
      cursor: "pointer",
      // snap: ".draggable",
      snapMode: "inner",
      scope: "this",
      cancel: ".vrr-element-delete, .vrr-element-edit-wrap, .vrr-element-edit",
      reverDuration: 200,
      // containment: '.draggable',
      drag: function( event, ui ) {
        
        draggableElementCoord = {
          x: ui.offset.left,
          y: ui.offset.top,
          x1: ui.offset.left + ui.helper[0].clientWidth,
          y1: ui.offset.top + ui.helper[0].clientHeight
        };

        draggableElementCoord['x'] = parseInt(draggableElementCoord['x']);
        draggableElementCoord['y'] = parseInt(draggableElementCoord['y']);
        draggableElementCoord['x1'] = parseInt(draggableElementCoord['x1']);
        draggableElementCoord['y1'] = parseInt(draggableElementCoord['y1']);

        dropDraggableElementCoord = draggableElementCoord;

        $('.vrr-draggable .vrr-element').removeClass('dragover');
        if(Object.keys(coordinates).length > 0){
          revert = true;
          for (var k in coordinates){
            if (intersect(coordinates[k], draggableElementCoord)){
              $(elements[k]).addClass('dragover');
              revert = false;
            } 
          }
        }
      }, 
      revert : function(event) {
        if(revert === true){
          return false;
        } else if(revert === false) {
          $('.vrr-draggable .vrr-element').removeClass('dragover');
          return true;
        } else if(revert === null){
          $('.vrr-draggable .vrr-element').removeClass('dragover');
          return true;
        }
      },// revert
      start: function(e, ui){
        $('.vrr-element .vrr-element-edit-wrap').hide();
        $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
        $('.vrr-draggable .vrr-element').removeClass('opened');
        $( ".ui-draggable" ).not( ui.helper.css( "z-index", "3" ) ).css( "z-index", "2" );
        $(this).closest('.vrr-element-sidebar-wrap-all').find('.vrr-element-sidebar-wrap').css('z-index', '1');
        $(this).closest('.vrr-element-sidebar-wrap').css('z-index', '2');
      },
    }); 

    $('.vrr-tables-wrap').removeClass('vrr-loading');

  } // initDraggble



  function ititDroppable(){
    $(".vrr-draggable").droppable({
      scope: "this",
      tolerance: "fit",
      drop: function(e, ui) {
          var $draggable;
          $draggable = ui.helper.clone();
          $draggable.css('opacity', 1);
          $draggable.css('position', 'absolute');
          $draggable.removeClass('ui-draggable-dragging');

          if(revert === null){ // if first element on canvas
            ui.helper.remove();
          }

          coordinates_with_curent[0] = {
            x: ui.offset.left,
            y: ui.offset.top,
            x1: ui.offset.left + ui.draggable[0].clientWidth,
            y1: ui.offset.top + ui.draggable[0].clientHeight
          }

          elements = [];
          $('.vrr-draggable .vrr-element').each(function(e){
            elements.push($(this));
          });

          var canvasOffset = {
            'top': parseInt($(this).offset().top, 10) + parseInt($(this).css('border-top-width'), 10) + parseInt($(this).closest('.vrr-draggable-wrap').css('border-top-width'), 10),
            'left': parseInt($(this).offset().left, 10) + parseInt($(this).css('border-left-width'), 10) + parseInt($(this).closest('.vrr-draggable-wrap').css('border-left-width'), 10)
          }

          var offsetTop = dropDraggableElementCoord['y'] - canvasOffset.top;
          var offsetLeft = dropDraggableElementCoord['x'] - canvasOffset.left;

          $draggable.draggable({
            // grid: [20, 20],
            helper: 'original',
            opacity: 0.9,
            cancel: ".vrr-element-delete, .vrr-element-edit-wrap, .vrr-element-edit",
            containment: '.vrr-draggable',
            // snap: '.element',
            reverDuration: 200,
            snapMode: "outside",
            snapTolerance: 5,
            create: function(event, ui){
              elements = [];
              $('.vrr-draggable .vrr-element').each(function(e){
                elements.push($(this));
              });
              // console.log(elements);
              if(revert === true || revert === null){
                coordinates = new Array();
                var i = 0;
                if(elements.length > 0){
                  // for (var k in elements){
                  for (var k = 0; k <= elements.length - 1; k++) {
                    coordinates[k] = {
                      x: $(elements[k]).offset().left,
                      y: $(elements[k]).offset().top,
                      x1: $(elements[k]).offset().left + $(elements[k])[0].clientWidth,
                      y1: $(elements[k]).offset().top + $(elements[k])[0].clientHeight
                    }
                    i = k;
                  }
                  coordinates[parseInt(i)+1] = coordinates_with_curent[0];
                } else {
                  coordinates[0] = coordinates_with_curent[0];
                }

                element_id++;
                var unique = makeid(10);
                $(this).find('.vrr-element-table').prepend('<div class="vrr-element-edit"></div>')
                $(this).find('.vrr-element-table').prepend('<div class="vrr-element-id" data-default="'+element_id+'">'+element_id+'</div>');
                $(this).attr('data-unique', unique);
                $(this).attr('data-id', element_id);
                $(this).find('.vrr-element-id').text(element_id);
                $(this).find('.vrr-element-id').attr('data-default', element_id);
              }
            },
            start: function(e, ui){
              $('.vrr-element .vrr-element-edit-wrap').hide();
              $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
              $('.vrr-draggable .vrr-element').removeClass('opened');
              $( ".ui-draggable" ).not( ui.helper.css( "z-index", "3" ) ).css( "z-index", "2" );
              coordinates = refreshCoordinates();
              new_coordinates = new Array();
              new_elements = new Array();
              new_coordinates = coordinates;
              new_elements = elements;

            },
            drag: function( event, ui ) {
             
              draggableElementCoord = {
                x: ui.offset.left,
                y: ui.offset.top,
                x1: ui.offset.left + ui.helper[0].clientWidth,
                y1: ui.offset.top + ui.helper[0].clientHeight
              };

              var obj = {};
              obj = {0 : draggableElementCoord}

              var count = 0;
              for (var k  in coordinates){
                if(typeof new_coordinates[k] != "undefined"){

                  draggableElementCoord['x'] = parseInt(draggableElementCoord['x']);
                  new_coordinates[k]['x'] = parseInt(new_coordinates[k]['x']);
                  draggableElementCoord['y'] = parseInt(draggableElementCoord['y']);
                  new_coordinates[k]['y'] = parseInt(new_coordinates[k]['y']);

                  // if moved to 1px in any way then delet from fake array to let move forward(8 ways and static)
                  if(intersect(new_coordinates[k], draggableElementCoord) && one_time_is_done != 1){
                    if(one_time_is_done === 1){}else{
                        new_coordinates = new_coordinates.filter(function(item) {
                          return item !== new_coordinates[k];
                        });
                        new_elements = new_elements.filter(function(item) {
                          return item !== new_elements[k];
                        });
                        one_time_is_done = 1;
                      }
                  } else {
                    $('.vrr-draggable .vrr-element').removeClass('dragover');
                    revert_exist = true;
                    for (var k in new_coordinates){
                      if (intersect(new_coordinates[k], draggableElementCoord)){
                        $(new_elements[k]).addClass('dragover');
                        revert_exist = false;
                      } 
                    }
                  }
                  count++;
                }
              }
            },
            stop: function( event, ui ) {
              one_time_is_done = 0;
            },
            revert : function(event) {
              if(revert_exist === true){
                return false;
              } else if(revert_exist === false) {
                $('.vrr-draggable .vrr-element').removeClass('dragover');
                return true;
              } else if(revert_exist === null){
                $('.vrr-draggable .vrr-element').removeClass('dragover');
                return true;
              }
            },
          });
          
          if(revert === true || revert === null){
            $draggable.css({
              "top": offsetTop,
              "left": offsetLeft
            }).appendTo('.vrr-draggable');
          }

          elements = [];
          $('.vrr-draggable .vrr-element').each(function(e){
            elements.push($(this));
          });
        
      } // drop: function(){
    }); // $(".draggable").droppable({
  } // ititDroppable

  function initDraggbleLoad(){
    $('.vrr-draggable .vrr-element').draggable({
      // grid: [20, 20],
      helper: 'original',
      opacity: 0.9,
      cancel: ".vrr-element-delete, .vrr-element-edit-wrap, .vrr-element-edit",
      containment: '.vrr-draggable',
      // snap: '.draggable',
      reverDuration: 200,
      snapMode: "inside",
      snapTolerance: 5,
      create: function(event, ui){
        if(revert === true || revert === null){
          coordinates = new Array();
          var i = 0;
          // for (var k in elements){
          for (var k = 0; k <= elements.length - 1; k++) {
            coordinates[k] = {
              x: $(elements[k]).offset().left,
              y: $(elements[k]).offset().top,
              x1: $(elements[k]).offset().left + $(elements[k])[0].clientWidth,
              y1: $(elements[k]).offset().top + $(elements[k])[0].clientHeight
            }
            i = k;
          }
        }
      },
      start: function(e, ui){
        $('.vrr-element .vrr-element-edit-wrap').hide();
        $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
        $('.vrr-draggable .vrr-element').removeClass('opened');

        $( ".ui-draggable" ).not( ui.helper.css( "z-index", "3" ) ).css( "z-index", "2" );
        coordinates = refreshCoordinates();
        new_coordinates = new Array();
        new_elements = new Array();
        new_coordinates = coordinates;
        new_elements = elements;
      },
      drag: function( event, ui ) {
        draggableElementCoord = {
          x: ui.offset.left,
          y: ui.offset.top,
          x1: ui.offset.left + ui.helper[0].clientWidth,
          y1: ui.offset.top + ui.helper[0].clientHeight
        };

       

        var count = 0;
        for (var k  in coordinates){
          if(typeof new_coordinates[k] != "undefined"){
            draggableElementCoord['x'] = parseInt(draggableElementCoord['x']);
            new_coordinates[k]['x'] = parseInt(new_coordinates[k]['x']);
            draggableElementCoord['y'] = parseInt(draggableElementCoord['y']);
            new_coordinates[k]['y'] = parseInt(new_coordinates[k]['y']);

            // if moved to 1px in any way then delet from fake array to let move forward(8 ways and static)
            if(intersect(new_coordinates[k], draggableElementCoord) && one_time_is_done != 1){
                // this need to do only one time, on first check
                if(one_time_is_done === 1){}else{
                  new_coordinates = new_coordinates.filter(function(item) {
                    return item !== new_coordinates[k];
                  });
                  new_elements = new_elements.filter(function(item) {
                    return item !== new_elements[k];
                  });
                  one_time_is_done = 1;
                }
            } else {
              $('.vrr-draggable .vrr-element').removeClass('dragover');
              revert_exist = true;
              for (var k in new_coordinates){
                if (intersect(new_coordinates[k], draggableElementCoord)){
                  $(new_elements[k]).addClass('dragover');
                  revert_exist = false;
                } 
              }
            }
            count++;
          }
        }
      },
      stop: function( event, ui ) {
        one_time_is_done = 0;
        coordinates = refreshCoordinates();
      },
      revert : function(event) {
        if(revert_exist === true){
          return false;
        } else if(revert_exist === false) {
          $('.vrr-draggable .vrr-element').removeClass('dragover');
          return true;
        } else if(revert_exist === null){
          $('.vrr-draggable .vrr-element').removeClass('dragover');
          return true;
        }
      },
    });
  }

  function drawSeats(q,element, rotate = 0){
    var current_seats = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
    var max = element.attr('data-max-seats');
    var start = element.attr('data-seats');
    var total = 0;
    if(current_seats != q){
      total = q - current_seats;
      if(total != 0){
        if(total > 0){  
          $('<div class="vrr-element-seat" style="display: none;"></div>').appendTo(element.find('.vrr-element-seats-wrap')).hide(function(){

            addCSStoDraw(q,element, rotate);

          }).fadeIn(200);
        } else if(total < 0){
          element.find('.vrr-element-seats-wrap .vrr-element-seat:last-child').fadeOut(0,function(){
            $.when($(this).remove()).then(function(){

              addCSStoDraw(q,element, rotate);

            });
          });
        }
      }
    }
  }

  function addCSStoDraw(q,element, rotate = 1){
    var way_string = "";
    var second_lap = 0;
    var iter = 1 ;
    var second_lap_start = 0;
    var third_lap_start = 0;
    var el_table = element.find('.vrr-element-table');
    var table_w = el_table.outerWidth(true);
    var table_h = el_table.outerHeight(true);
    var seat_size = 30;
    if($('.vrr-tables-wrap').hasClass('vrr-tables-size-small')){
      seat_size = 26;
    } else if($('.vrr-tables-wrap').hasClass('vrr-tables-size-medium')){
      seat_size = 30;
    } else if($('.vrr-tables-wrap').hasClass('vrr-tables-size-big')){
      seat_size = 34;
    } 

    var closest_margin = 4;
    var max = parseInt(element.attr('data-max-seats'));
    var first_seats = 0;
    var el_type = element.attr('data-type');
    first_seats = max - 4;

    if(q > 4){
      second_lap = q - 4;
    } 

    var has_second = 0;
    var has_third = 0;

    var w_first = 0;
    var h_first = 0;
    
    if(element.attr('data-big-side') == 'h'){
      h_first = 1;
    }

    /* 
    [
      {0:4},
      {1:2},
      {2:4},
      {3:2},
      {4:1}
    ]
    */
    var numbers_array = [];
    var all_seats = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
    element.find('.vrr-element-seats-wrap .vrr-element-seat').each(function(seat){ 
      rotate = parseInt(rotate); // default - 1
      seat = parseInt(seat); // first - 0
      var number = parseInt(seat + rotate ); // because of rotate default = 1, seat != seat+1
      if(h_first == 1){ // if height is bigger then width
        number = number - 1;
      }
        
      // if number is > then 4 or less then 1
      if(number > 4){  
        number = number - 4;
        if(number > 4){
          number = number - 4;
          if(number > 4){
            number = number - 4;
          } 
        } 
      } else if(number <= 0){
        number = 4;
      }

      if(el_type != 'type-4'){// not circle
        // this is to make 1 3 1 3 2 4 and not 1 2 3 4 1 2

        if(all_seats > 8){ // 3 seats on one side

          if(parseInt(seat+1) <= 6){
            var new_number = number + seat;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
                if(new_number > 4){
                  new_number = new_number - 4;
                }
              }
            }
          } else {
            var new_number = number + seat + 1;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
                if(new_number > 4){
                  new_number = new_number - 4;
                }
              }
            }
          }

          // if(element.hasClass('type-5')){
          //   console.log(number);
          // } 

        } else if(all_seats <= 8) { // 2 seats on one side

          if(parseInt(seat+1) <= 4){
            var new_number = number + seat;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
              }
            }
          } else {
            var new_number = number + seat + 1;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
              }
            }
          }

        }

        number = new_number;

      }

      // push to new array
      numbers_array.push(number);

    });

    // if(element.hasClass('type-5')){
    //   console.log(numbers_array);
    // }
    

    // DRAW START //

    element.find('.vrr-element-seats-wrap .vrr-element-seat').each(function(seat){
      
      rotate = parseInt(rotate); // default - 1
      seat = parseInt(seat); // first - 0
      var number = parseInt(seat  + rotate ); // because of rotate default = 1, seat != seat+1
      if(h_first == 1){ // if height is bigger then width
        number = number - 1;
      } 

      // if number is > then 4 or less then 1
      if(number > 4){  
        number = number - 4;
        if(number > 4){
          number = number - 4;
          if(number > 4){
            number = number - 4;
          } 
        } 
      } else if(number <= 0){
        number = 4;
      }

      if(el_type != 'type-4'){// not circle
        // this is to make 1 3 1 3 2 4 and not 1 2 3 4 1 2

        if(all_seats > 8){ // 3 seats on one side

          if(parseInt(seat+1) <= 6){
            var new_number = number + seat;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
                if(new_number > 4){
                  new_number = new_number - 4;
                }
              }
            }
          } else {
            var new_number = number + seat + 1;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
                if(new_number > 4){
                  new_number = new_number - 4;
                }
              }
            }
          }

          /*if(element.hasClass('type-5')){
            console.log(number);
          } */

        } else if(all_seats <= 8) { // 2 seats on one side

          if(parseInt(seat+1) <= 4){
            var new_number = number + seat;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
              }
            }
          } else {
            var new_number = number + seat + 1;
            if(new_number > 4){
              new_number = new_number - 4;
              if(new_number > 4){
                new_number = new_number - 4;
              }
            }
          }

        }

        number = new_number;

      }

      var more_two = 0;
      var not_two = 0;
      var is_two = [];
      // if more than two elements in array of all or push two if two or more
      for (var i = numbers_array.length - 1; i >= 0; i--) {
        if(numbers_array[i] == number){
          more_two++;
          not_two = i+1;
          is_two.push(i+1);
        }
      }
      if(more_two < 2){
        
      } else {
        not_two = 0;
      }


      // if(element.hasClass('type-5')){
      //   console.log(not_two);
      // }

      // REMOVE ARRAY ELEMENT
      Array.prototype.remove = function() {
          var what, a = arguments, L = a.length, ax;
          while (L && this.length) {
              what = a[--L];
              while ((ax = this.indexOf(what)) !== -1) {
                  this.splice(ax, 1);
              }
          }
          return this;
      };

      second_lap_start = 0;// second lap of draw for "has_second"
      third_lap_start = 0;// third lap of draw for "has_third"
      has_second = 1; // element has second as pair
      has_third = 1; // element has third as pair
      if(not_two > 0){ // ony one elment/no pair
        if(parseInt(seat+1) == not_two){
          has_second = 0;
        } 
        if(element.hasClass('type-5')){
          // console.log(has_second);
          // console.log(not_two);
        }
      } else { // has pair/check lap
        if(is_two.length == 3){ // if more then 8 elements
          has_third = 1;
          var max = Math.max.apply(null, is_two);
          var new_is_to = is_two;
          new_is_to.remove(max);
          var mid = Math.max.apply(null, new_is_to);
          if(max == seat+1){
            third_lap_start = 1;
          } else if(mid == seat+1){
            second_lap_start = 1;
          } else {
            // second_lap_start = 0;
          }
        } else if(is_two.length == 2){ // // if 8 elements or less
          has_third = 0;
          var max = Math.max.apply(null, is_two);
          if(max == seat+1){
            second_lap_start = 1;
          } else {
            second_lap_start = 0;
          }
        }
        
        if(element.hasClass('type-5')){
          // console.log('third = '+third_lap_start);
          // console.log('second = '+second_lap_start);
          // console.log('max - '+max);
          // console.log('mid - '+mid);
          // console.log('has_third = '+has_third);
          // console.log('has_second = '+has_second);
          // console.log((seat+1)+'------');
        }
        
      }

      // DRAW part/check side of element
      
      if(number == 1){ // FIRST
        way_string = "top";
        $(this).css('width', seat_size);
        $(this).css('height', '');
        if(has_second == 1 && has_third == 0){ // two seats on the way
          if(second_lap_start == 1){
            var left_second = (el_table.outerWidth(true)/2) + (((el_table.outerWidth(true)/2) - $(this).outerWidth(true))/2) - closest_margin;
            $(this).css('left', left_second);
            $(this).css('top', -$(this).outerHeight(true));
          } else {
            var left = (el_table.outerWidth(true)/2) - $(this).outerWidth(true) - (((el_table.outerWidth(true)/2) - $(this).outerWidth(true))/2) + closest_margin;
            $(this).css('left', left);
            $(this).css('top', -$(this).outerHeight(true));
          }
        } else if(has_second == 1 && has_third == 1){ // THIRD HAS
          if(second_lap_start == 1 && third_lap_start == 0){
            var left_second = (el_table.outerWidth(true)/2) - (($(this).outerWidth(true))/2) - closest_margin;
            $(this).css('left', left_second);
            $(this).css('top', -$(this).outerHeight(true));
          } else if(third_lap_start == 1 && second_lap_start == 0){
            var left_third = (el_table.outerWidth(true)/3) + (el_table.outerWidth(true)/3) + ( (el_table.outerWidth(true)/3) - $(this).outerWidth(true) )/2 - closest_margin;
            $(this).css('left', left_third);
            $(this).css('top', -$(this).outerHeight(true));
          } else { 
            var left = (el_table.outerWidth(true)/3) - $(this).outerWidth(true) - (((el_table.outerWidth(true)/3) - $(this).outerWidth(true))/2) + closest_margin;
            $(this).css('left', left);
            $(this).css('top', -$(this).outerHeight(true));
          }
        } else { // one seat
          var left_one_seat = (el_table.outerWidth(true)/2) - ($(this).outerWidth(true)/2);
          $(this).css('left', left_one_seat);
          $(this).css('top', -$(this).outerHeight(true));
        }
      } else if(number == 2){ // 
        way_string = "right";
        $(this).css('height', seat_size);
        $(this).css('width', '');
        if(has_second == 1 && has_third == 0){ // two seats on the way
          if(second_lap_start == 1){
            var top_second = (el_table.outerHeight(true)/2) + (((el_table.outerHeight(true)/2) - $(this).outerHeight(true))/2) - closest_margin;
            $(this).css('left', 'calc(100%)');
            $(this).css('top', top_second);
          } else {
            var top = (el_table.outerHeight(true)/2) - $(this).outerHeight(true) - (((el_table.outerHeight(true)/2) - $(this).outerHeight(true))/2) + closest_margin;
            $(this).css('left', 'calc(100%)');
            $(this).css('top', top);
          }
        } else if(has_second == 1 && has_third == 1){ // THIRD HAS
          if(second_lap_start == 1 && third_lap_start == 0){
            var top_second = (el_table.outerHeight(true)/2) - (($(this).outerHeight(true))/2) ;
            $(this).css('left', 'calc(100%)');
            $(this).css('top', top_second);
          } else if(second_lap_start == 0 && third_lap_start == 1){
            var top_third = (el_table.outerHeight(true)/3) + (el_table.outerHeight(true)/3) + ( (el_table.outerHeight(true)/3) - $(this).outerHeight(true) )/2 - closest_margin;
            $(this).css('left', 'calc(100%)');
            $(this).css('top', top_third);
          } else {
            var top = (el_table.outerHeight(true)/3) - $(this).outerHeight(true) - (((el_table.outerHeight(true)/3) - $(this).outerHeight(true))/2) + closest_margin;
            $(this).css('left', 'calc(100%)');
            $(this).css('top', top);
          }
        } else { // one seat
          var top_one_seat = (el_table.outerHeight(true)/2) - ($(this).outerHeight(true)/2);
          $(this).css('left', 'calc(100%)');
          $(this).css('top', top_one_seat);
        }
      } else if(number == 3){ // 
        way_string = "bottom";
        $(this).css('width', seat_size);
        $(this).css('height', '');
        if(has_second == 1 && has_third == 0){ // two seats on the way
          if(second_lap_start == 1){
            var left_second = (el_table.outerWidth(true)/2) + (((el_table.outerWidth(true)/2) - $(this).outerWidth(true))/2) - closest_margin;
            $(this).css('left', left_second);
            $(this).css('top', 'calc(100%)');
          } else {
            var left = (el_table.outerWidth(true)/2) - $(this).outerWidth(true) - (((el_table.outerWidth(true)/2) - $(this).outerWidth(true))/2) + closest_margin;
            $(this).css('left', left);
            $(this).css('top', 'calc(100%)');
          }
        } else if(has_second == 1 && has_third == 1){ // THIRD HAS
          if(second_lap_start == 1 && third_lap_start == 0){
            var left_second = (el_table.outerWidth(true)/2) - (($(this).outerWidth(true))/2) - closest_margin;
            $(this).css('left', left_second);
            $(this).css('top', 'calc(100%)');
          } else if(third_lap_start == 1 && second_lap_start == 0){
            var left_third = (el_table.outerWidth(true)/3) + (el_table.outerWidth(true)/3) + ( (el_table.outerWidth(true)/3) - $(this).outerWidth(true) )/2 - closest_margin;
            $(this).css('left', left_third);
            $(this).css('top', 'calc(100%)');
          } else { 
            var left = (el_table.outerWidth(true)/3) - $(this).outerWidth(true) - (((el_table.outerWidth(true)/3) - $(this).outerWidth(true))/2) + closest_margin;
            $(this).css('left', left);
            $(this).css('top', 'calc(100%)');
          }
        } else { // one seat
          var left_one_seat = (el_table.outerWidth(true)/2) - ($(this).outerWidth(true)/2);
          $(this).css('left', left_one_seat);
          $(this).css('top', 'calc(100%)');
        }
      } else if(number == 4){
        way_string = "left";
        $(this).css('height', seat_size);
        $(this).css('width', '');
        if(has_second == 1 && has_third == 0){ // two seats on the way
          if(second_lap_start == 1){
            var top_second = (el_table.outerHeight(true)/2) + (((el_table.outerHeight(true)/2) - $(this).outerHeight(true))/2) - closest_margin;
            $(this).css('left', -$(this).outerWidth(true));
            $(this).css('top', top_second);
          } else {
            var top = (el_table.outerHeight(true)/2) - $(this).outerHeight(true) - (((el_table.outerHeight(true)/2) - $(this).outerHeight(true))/2) + closest_margin;
            $(this).css('left', -$(this).outerWidth(true));
            $(this).css('top', top);
          }
        } else if(has_second == 1 && has_third == 1){ // THIRD HAS
          if(second_lap_start == 1 && third_lap_start == 0){
            var top_second = (el_table.outerHeight(true)/2) - (($(this).outerHeight(true))/2) ;
            $(this).css('left', -$(this).outerWidth(true));
            $(this).css('top', top_second);
          } else if(second_lap_start == 0 && third_lap_start == 1){
            var top_third = (el_table.outerHeight(true)/3) + (el_table.outerHeight(true)/3) + ( (el_table.outerHeight(true)/3) - $(this).outerHeight(true) )/2 - closest_margin;
            $(this).css('left', -$(this).outerWidth(true));
            $(this).css('top', top_third);
          } else {
            var top = (el_table.outerHeight(true)/3) - $(this).outerHeight(true) - (((el_table.outerHeight(true)/3) - $(this).outerHeight(true))/2) + closest_margin;
            $(this).css('left', -$(this).outerWidth(true));
            $(this).css('top', top);
          }
        } else { // one seat
          var top_one_seat = (el_table.outerHeight(true)/2) - ($(this).outerHeight(true)/2);
          $(this).css('top', top_one_seat);
          $(this).css('left', -$(this).outerWidth(true));
        }
      }

      iter++;

    }).promise().done(function(){ 

    });
  }

  // Edit element
  $('.vrr-draggable .vrr-element .vrr-element-edit').live('click',function(){
    var element = $(this).closest('.vrr-element');
    var editor = element.find('.vrr-element-edit-wrap');
    $('.vrr-element').css('z-index', '');
    $('.vrr-draggable .vrr-element').css('z-index', '2');
    element.css('z-index', '3');
    if(editor.hasClass('opened')){ // close
      $('.vrr-element .vrr-element-edit-wrap').hide();
      $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
      $('.vrr-draggable .vrr-element').removeClass('opened');
    } else { // open
      $('.vrr-element .vrr-element-edit-wrap').hide();
      $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
      $('.vrr-draggable .vrr-element').removeClass('opened');
      editor.find('.vrr-element-edit-seats-input').text(element.attr('data-seats'));
      editor.find('.vrr-element-edit-id-input').val(element.attr('data-id'));
      editor.show();
      editor.addClass('opened');   
      element.addClass('opened');
    }
  });

  $(document).on('click', function (e) {
      if(!$(e.target).hasClass('vrr-element-edit')){
        if ($(e.target).closest(".vrr-element-edit-wrap").length === 0) {
          $('.vrr-element .vrr-element-edit-wrap').hide();
          $('.vrr-element .vrr-element-edit-wrap').removeClass('opened');
          $('.vrr-draggable .vrr-element').removeClass('opened');
        }
      }
  });

  // Plus seats
  $('.vrr-draggable .vrr-element .vrr-element-edit-seats-plus').live('click',function(){
    var element = $(this).closest('.vrr-element');
    var editor = element.find('.vrr-element-edit-wrap');
    var current = $(this).closest('.vrr-element-edit-seats').find('.vrr-element-edit-seats-input').text();
    var max = element.attr('data-max-seats');
    var rotate = element.attr('data-rotate');
    current = parseInt(current);
    if(current+1 <= max){
      element.attr('data-seats', current+1);
      $(this).closest('.vrr-element-edit-seats').find('.vrr-element-edit-seats-input').text(current+1);
      element.find('.vrr-element-seats').text(current+1);
      drawSeats(current+1, element, rotate);
    }
  });

  // Minus seats
  $('.vrr-draggable .vrr-element .vrr-element-edit-seats-minus').live('click',function(){
    var element = $(this).closest('.vrr-element');
    var editor = element.find('.vrr-element-edit-wrap');
    var current = $(this).closest('.vrr-element-edit-seats').find('.vrr-element-edit-seats-input').text();
    var max = element.attr('data-max-seats');
    var rotate = element.attr('data-rotate');
    current = parseInt(current);
    if(current-1 >= 1){
      element.attr('data-seats', current-1);
      $(this).closest('.vrr-element-edit-seats').find('.vrr-element-edit-seats-input').text(current-1);
      element.find('.vrr-element-seats').text(current-1);
      drawSeats(current-1,element, rotate);
    }
  });

  // Change Id
  $('.vrr-draggable .vrr-element .vrr-element-edit-id-input').live('keyup change input', function(){
    var element = $(this).closest('.vrr-element');
    var def_val = element.find('.vrr-element-id').attr('data-default');
    var wrap = element.closest('.vrr-draggable');
    var def = 0;
    var editor = element.find('.vrr-element-edit-wrap');
    var val = parseInt($(this).val());
    if(val != "" && val == $(this).val()){
      if (val === parseInt(val, 10) && val >= 1 && val <= 99 )  {
        wrap.find('.vrr-element').each(function(e){
          if($(this).attr('data-id') == val){
            def = 1;
          }
        });
        if(def == 0){
          $(this).attr('data-id', val);
          element.find('.vrr-element-id').text(val);
          element.attr('data-id', val);
        } else if(def == 1){
          $(this).attr('data-id', $(this).attr('data-id'));
          element.find('.vrr-element-id').text($(this).attr('data-id'));
          element.attr('data-id', $(this).attr('data-id'));
        }
      } else {
        $(this).val($(this).attr('data-id'));
        element.find('.vrr-element-id').text($(this).attr('data-id'));
        element.attr('data-id', $(this).attr('data-id'));
        return false;
      }
    } else {
      var new_val = parseInt(toString($(this).val()).replace(',', '.'));
      $(this).val('');   
      $(this).attr('data-id', '');
      element.find('.vrr-element-id').text(element.find('.vrr-element-id').attr('data-default'));
      element.attr('data-id', element.find('.vrr-element-id').attr('data-default'));
    }
  });

  // Rotate element
  $('.vrr-element-edit-rotate').live('click', function(){
    var element = $(this).closest('.vrr-element');
    var rotate = parseInt(element.attr('data-rotate'));
    var rotate_next = parseInt(rotate+1);
    var q = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
    var editor = element.find('.vrr-element-edit-wrap');
    if(rotate_next > 4){
      element.attr('data-rotate', '1');
      element.removeClass('rotate-'+rotate+'');
      element.addClass('rotate-1');
      element.find('.vrr-element-seats-wrap .vrr-element-seat').hide();
      setTimeout(function() {
        addCSStoDraw(q,element, 1);
        element.find('.vrr-element-seats-wrap .vrr-element-seat').fadeIn(200);
      }, 200);
    } else {
      element.attr('data-rotate', rotate_next);
      element.removeClass('rotate-'+rotate+'');
      element.addClass('rotate-'+rotate_next+'');
      element.find('.vrr-element-seats-wrap .vrr-element-seat').hide();
      setTimeout(function() {
        addCSStoDraw(q,element, rotate_next);
        element.find('.vrr-element-seats-wrap .vrr-element-seat').fadeIn(200);
      }, 200);
      
    } 
  });

  // Delete element
  $('.vrr-element-delete').live('click', function(){
    var element = $(this).closest('.vrr-element');
    element.fadeOut(200,function(){
      $.when($(this).remove()).then(function(){
        coordinates = refreshCoordinates();
        elementIdRefresh();
      });
    });
  });
  


  $(document).ready(function () {
    
    elementIdRefresh();
    showSpinner();
    initDraggble();
    ititDroppable();
    initDraggbleLoad();
    
    $('.vrr-element').each(function(){
      var element = $(this);
      var rotate = element.attr('data-rotate');
      var q = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
      addCSStoDraw(q,element, rotate);
    });
    
    $('.vrr-tables-wrap').removeClass('vrr-loading');

  });



  $('#vrr-options-save').on('submit',function(e){
    // e.preventDefault();
    var coordinates_save = new Array();
    var elements_save = [];
    $('.vrr-draggable .vrr-element').each(function(e){
      elements_save.push($(this));
    });
    // for (var k in elements_save){
    for (var k = 0; k <= elements_save.length - 1; k++) {
      var type;
      var classList = $(elements_save[k]).attr('class').split(/\s+/);
      $.each(classList, function(index, item) {
        if (item.toLowerCase().indexOf("type-") >= 0){
          type = item;
        }
      });

      coordinates_save[k] = {
        x: $(elements_save[k]).position().left,
        y: $(elements_save[k]).position().top,
        x1: $(elements_save[k]).position().left + $(elements_save[k])[0].clientWidth,
        y1: $(elements_save[k]).position().top + $(elements_save[k])[0].clientHeight,
        id: $(elements_save[k]).attr('data-id'),
        unique: $(elements_save[k]).attr('data-unique'),
        class: $(elements_save[k]).attr('data-type'),
        rotate: $(elements_save[k]).attr('data-rotate'),
        big_side: $(elements_save[k]).attr('data-big-side'),
        seats: $(elements_save[k]).attr('data-seats'),
        max_seats: $(elements_save[k]).attr('data-max-seats'),
      }
    }
    if(coordinates_save.length > 0){
      var json_coordinates = JSON.stringify(coordinates_save);
      $('input[name="visual_restaurant_reservation_settings[position]"]').val(encodeURIComponent(json_coordinates));
    } else {
      $('input[name="visual_restaurant_reservation_settings[position]"]').val('');
    }

    return true;

  });


})(jQuery);