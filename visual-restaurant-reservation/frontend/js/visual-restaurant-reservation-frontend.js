(function ($) {
 'use strict';

  function checkTelephone(t){
    var pattern = new RegExp(/^[0-9\-\+]{7,15}$/);
    return pattern.test(t);
  }
  function checkEmail(m){
    var pattern = new RegExp(/^((\"[\w-\s]+\")|([\w-]+(?:\.[\w-]+)*)|(\"[\w-\s]+\")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(m);
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

  var datapicker = $('#visual_restaurant_reservation_datepicker');

  function refreshCoordinates(){
    coordinates = new Array();
    elements = [];
    // console.log(elements);
    $('.vrr-draggable .vrr-element').each(function(e){
      elements.push($(this));
    });
    // console.log(elements);
    // for (var k in elements){
    for (var k = 0; k <= elements.length - 1; k++) {
      coordinates[k] = {
        x: $(elements[k]).offset().left,
        y: $(elements[k]).offset().top,
        x1: $(elements[k]).offset().left + $(elements[k])[0].clientWidth,
        y1: $(elements[k]).offset().top + $(elements[k])[0].clientHeight
      }
    }
    // console.log(coordinates);
    return coordinates;
  }

  function addCSStoDraw(q,element, rotate = 1){
    var way_string = "";
    var second_lap = 0;
    var iter = 1;
    var second_lap_start = 0;
    var third_lap_start = 0;
    var el_table = element.find('.vrr-element-table');
    var table_w = el_table.outerWidth(true);
    var table_h = el_table.outerHeight(true);
    var seat_size = 30;
    if(table_w > table_h){
      seat_size = Math.round(table_h/6 * 2); 
    } else {
      seat_size = Math.round(table_w/6 * 2); 
    }
    /*if(element.attr('data-seats-size').length > 0){
      seat_size = element.attr('data-seats-size');
    } */
    
    if($('.vrr-tables-wrap').hasClass('vrr-tables-size-small')){
      seat_size = Math.round(seat_size*0.8);
    } else if($('.vrr-tables-wrap').hasClass('vrr-tables-size-medium')){
      // seat_size = 30;
    } else if($('.vrr-tables-wrap').hasClass('vrr-tables-size-big')){
      seat_size = Math.round(seat_size*1.2);
    } 

    var closest_margin = 2;
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
  
  function layoutResize(){
    var wrap = $('.vrr-tables-wrap-all'); // test wrap all
    var tables_wrap = wrap.outerWidth();
    // var tables_draggable = wrap.find('.vrr-draggable').outerWidth();
    var tables_draggable = parseInt(wrap.find('.vrr-draggable').attr('data-width'));
    var def_font_size = 0.1;
    if($(window).width() >= 768 && $(window).width() <= 1024){
      // def_font_size = 0.08;
    } 
    if(tables_draggable > tables_wrap){
      var tables_draggable_per = parseInt(tables_draggable)/100;
      
      var tables_wrap_per = parseInt(tables_wrap)/tables_draggable_per;
      tables_wrap_per = tables_wrap_per - 1;
      
      var tables_wrap_px = tables_wrap_per*def_font_size;// def 10px;
      // console.log(def_font_size);
      // console.log(tables_draggable_per+"px = 1% of draggable");
      // console.log(tables_wrap_per+"% of 100% is now");
      // console.log(tables_wrap_px+"px = 1%");
      // console.log('---');
      // console.log(tables_draggable+"px width of draggable");
      // console.log(tables_wrap+"px width of wrap");
      wrap.find('.vrr-draggable').css('font-size', tables_wrap_px+'px');
      wrap.find('.vrr-draggable *').css('font-size', tables_wrap_px+'px');
      var min_tables_wrap_px = tables_wrap_px;
      if(min_tables_wrap_px < 8){
        min_tables_wrap_px = 8;
      }
      wrap.find('.vrr-draggable .vrr-element-seats').css('font-size', min_tables_wrap_px*2+'px');
      wrap.find('.vrr-draggable .vrr-element-id').css('font-size', min_tables_wrap_px+'px');

      $('.vrr-element').each(function(){
        var element = $(this);
        /*var left = element.position().left;
        var top = element.position().top;*/
        var left = parseInt(element.attr('data-left'));
        var top = parseInt(element.attr('data-top'));
        var left_new = Math.round(left*(tables_wrap_px/10));
        var top_new = Math.round(top*(tables_wrap_px/10));
        // console.log(tables_wrap_per/100);
        // console.log(tables_wrap_px/10);
        // console.log(left+" = "+left_new);
        element.css('left', left_new);
        element.css('top', top_new);
      });

    }

    setTimeout(function() {
      $('.vrr-element').each(function(){
        var element = $(this);
        var rotate = element.attr('data-rotate');
        var q = element.find('.vrr-element-seats-wrap .vrr-element-seat').length;
        addCSStoDraw(q,element, rotate);
      });
      wrap.find('.vrr-draggable').removeClass('vrr-loading');
    }, 300);
  }  

  var exclude_data = [];

  $(document).ready(function () {

    var wrap = $('.vrr-tables-wrap');
    
    layoutResize();

    
    

    if(wrap.hasClass('vrr-tables-wrap-default')){ // default
      if ($(window).width() <= 767) {
        // hide TIME row before pick date
        $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
        $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').find('.vrr-error').hide();
        $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
        $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').find('.vrr-error').hide();

        reInitForm('', datapicker, wrap);
       
        // var dt = datapicker.datepicker({ dateFormat: 'MM/dd/yyyy' }).val();

        // var array_exclude_times_settings = [];
        // array_exclude_times_settings = decodeURIComponent(datapicker.attr('data-time'));
        // array_exclude_times_settings = JSON.parse(array_exclude_times_settings);

        // initSelect('default',array_exclude_times_settings, dt, wrap);

      }
    } else if(wrap.hasClass('vrr-tables-wrap-second')){ // second
      initFormSecond(datapicker, wrap);
    } else if(wrap.hasClass('vrr-tables-wrap-third')){ // third
      $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
      $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').find('.vrr-error').hide();
      $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
      $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').find('.vrr-error').hide();


      reInitForm('', datapicker, wrap);
    }


    $('.vrr-people-select').select2({
      minimumResultsForSearch: -1,
    });
    
  });


  $(window).on('resize',function(){
    var wrap = $('.vrr-tables-wrap');
    if ($(window).width() <= 767) {
      if(wrap.hasClass('vrr-tables-wrap-default')){ // default
        // hide TIME row before pick date
        wrap.find('.vrr-form-back').hide();
        wrap.find('.vrr-form-thx').hide();
        $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
        $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').find('.vrr-error').hide();
        $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
        $('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').find('.vrr-error').hide();

        reInitForm('', datapicker, wrap);
      } else if(wrap.hasClass('vrr-tables-wrap-second')){ // second
        initFormSecond(datapicker, wrap);
      } else if(wrap.hasClass('vrr-tables-wrap-third')){ // third

      }
    } else {
      layoutResize();

      if(wrap.hasClass('vrr-tables-wrap-default')){ // default
       
      } else if(wrap.hasClass('vrr-tables-wrap-second')){ // second

      } else if(wrap.hasClass('vrr-tables-wrap-third')){ // third

      }
    }
  });


  var select_html;
  function initSelect(layout, array_exclude_times_settings, dt, wrap) {
    var array_exclude_times = [];
    array_exclude_times = array_exclude_times.concat(array_exclude_times_settings);

    
    var new_exclude = [];
    var new_exclude_array = [];

    var $select1 = $('select#visual_restaurant_reservation_time, select#visual_restaurant_reservation_time_fake, select#visual_restaurant_reservation_time_to');

    if($select1.length > 0){
      $select1.each(function(e){

        var $select = $(this);
        var data = [];
        var select_placeholder = $select.attr('data-placeholder');
        var select_val = $select.val();

        if(typeof select_html != "undefined"){
          $select.html(select_html);
        } else {
          select_html = $select.html();
        }

        if(layout == "second"){
          for (var i = array_exclude_times.length - 1; i >= 0; i--) {
            wrap.find('#visual_restaurant_reservation_time_fake option').each(function(el){
              if($(this).val() == array_exclude_times[i]['time'] && dt == array_exclude_times[i]['date']){
                new_exclude.push(array_exclude_times[i]['time']);
              }
            });
          }
        } else if(layout == "default"){
          for (var i = array_exclude_times.length - 1; i >= 0; i--) {
            wrap.find('#visual_restaurant_reservation_time option').each(function(el){
              // console.log(dt+' = '+array_exclude_times[i]['date']);
              // console.log()
              if($(this).val() == array_exclude_times[i]['time'] && dt == array_exclude_times[i]['date']){
                new_exclude.push(array_exclude_times[i]['time']);
              }
            });
          }
        }

        // console.log(data);
        $select.find('option').each(function(option){
          data.push($(this).val());
        });
        
        data = data.filter( function( el ) {
          return !new_exclude.includes( el );
        } );
        
        $select.select2({
          placeholder: select_placeholder
        });

        $select.html('');

        var items = [];
        for (var i = 0; i < data.length; i++) {
            items.push({
                'id': i,
                'text': data[i]
            });
            $select.append("<option value=\"" + data[i] + "\">" + data[i] + "</option>");
        }
        $select.data('select2').options.options.data = items;

        $select.select2({
          data: $select.data('select2').options.options,
          placeholder: select_placeholder
        });
        

        if(layout == "second"){
          if(select_val){
            $select.val(select_val).trigger('change');
            if($select.val() == null){
              $select.val($select.find('option:first-child').val()).trigger('change');
            }
          } else {
            $select.val($select.find('option:first-child').val()).trigger('change');
          }
        } else {
          $select.val('').trigger('change');
        }

      });//each
    }// if

  }

  // init datapicker on load for second type layout
  function initFormSecond(datapicker, wrap){
    var array_exclude_dates = [];
    var array_exclude_times = [];
    var array_exclude_dates_settings = [];
    var array_exclude_times_settings = [];
    var plusDate = 30;
    
    array_exclude_times_settings = decodeURIComponent(datapicker.attr('data-time'));
    array_exclude_times_settings = JSON.parse(array_exclude_times_settings);

    if(datapicker.attr('data-maxdate').length > 0){
      plusDate = parseInt(datapicker.attr('data-maxdate'));
      if(typeof plusDate != 'number' || isNaN(plusDate)){
        plusDate = 30;
      }
    }

    datapicker.datepicker({ 
      minDate: 0, 
      maxDate: "+"+plusDate-1,
      altField: "#visual_restaurant_reservation_date",
      dateFormat: "mm/dd/yy",
      setDate: 'null',
      setDate: false,
      autoUpdateInput: false,
      firstDay: 1,
      onSelect: function(dt) {
        $('#visual_restaurant_reservation_date').removeClass('vrr-error-input');
        $('#visual_restaurant_reservation_date').closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeOut(100);
        $('#visual_restaurant_reservation_time_fake').removeClass('vrr-error-input');
        $('#visual_restaurant_reservation_time_fake').closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeOut(100);

        // wrap.find('#visual_restaurant_reservation_time_fake option').show();

        initSelect('second',array_exclude_times_settings, dt, wrap);
        
        var fake_val = wrap.find('#visual_restaurant_reservation_time_fake').val();
        if(fake_val != null){
          if( wrap.find('#visual_restaurant_reservation_time').val() == ""){
            wrap.find('#visual_restaurant_reservation_time').val(fake_val);
            wrap.find('#visual_restaurant_reservation_time_fake').val(fake_val);
          }
        } else {
          wrap.find('#visual_restaurant_reservation_time_fake').val(wrap.find('#visual_restaurant_reservation_time').val());
        }

        wrap.find('#visual_restaurant_reservation_time_fake').closest('.vrr-input-wrap-time').show();
        // wrap.find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').show();

        reInitElementsSecond(wrap, datapicker);

      },
      beforeShowDay: function(dt) {

        array_exclude_dates_settings = decodeURIComponent(datapicker.attr('data-date'));
        array_exclude_dates_settings = JSON.parse(array_exclude_dates_settings);
        // console.log(array_exclude_dates_settings);
        array_exclude_dates = array_exclude_dates.concat(array_exclude_dates_settings);
        
        var dateString = $.datepicker.formatDate('mm/dd/yy', dt);
        // console.log(array_exclude_dates.length);
        return [array_exclude_dates.indexOf(dateString) == -1];
      }, 
    });

    datapicker.datepicker( "setDate", null );
    $('#visual_restaurant_reservation_date').val('');
  }

  // init datapicker form default type layout/ itit on element click
  function reInitForm(element = null, datapicker, wrap){
    var array_exclude_dates = [];
    var array_exclude_times = [];
    var array_exclude_dates_settings = [];
    var array_exclude_times_settings = [];
    var plusDate = 30;

    datapicker.datepicker( "destroy" );
    
    array_exclude_times_settings = decodeURIComponent(datapicker.attr('data-time'));
    array_exclude_times_settings = JSON.parse(array_exclude_times_settings);

    if(datapicker.attr('data-maxdate').length > 0){
      plusDate = parseInt(datapicker.attr('data-maxdate'));
      if(typeof plusDate != 'number' || isNaN(plusDate)){
        plusDate = 30;
      }
    }
    // console.log(plusDate);
    datapicker.datepicker({ 
      minDate: 0, 
      maxDate: "+"+plusDate-1,
      altField: "#visual_restaurant_reservation_date",
      dateFormat: "mm/dd/yy",
      setDate: false,
      firstDay: 1,
      onSelect: function(dt, ui) {
        // console.log(dt);
        $('#visual_restaurant_reservation_date').removeClass('vrr-error-input');
        $('#visual_restaurant_reservation_date').closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeOut(100);
        $('#visual_restaurant_reservation_time').removeClass('vrr-error-input');
        $('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeOut(100);

        // wrap.find('#visual_restaurant_reservation_time option').show();
        if(element.length > 0){
          if(element.attr('data-time').length > 0){

            array_exclude_times = decodeURIComponent(element.attr('data-time'));
            array_exclude_times = JSON.parse(array_exclude_times);
            
            array_exclude_times = array_exclude_times.concat(array_exclude_times_settings);
            // console.log(array_exclude_times);
            // for (var i = array_exclude_times.length - 1; i >= 0; i--) {
            //   // console.log(array_exclude_times[i]['time']);
            //   wrap.find('#visual_restaurant_reservation_time option').each(function(el){
            //     if($(this).val() == array_exclude_times[i]['time'] && dt == array_exclude_times[i]['date']){
            //       $(this).hide();
            //     }
            //   });
            // }

            initSelect('default',array_exclude_times, dt, wrap);

            var fake_val = wrap.find('#visual_restaurant_reservation_time').val();
            // console.log(fake_val);
            // if(fake_val != null){
            //   // if( wrap.find('#visual_restaurant_reservation_time').val() == ""){
            //   //   wrap.find('#visual_restaurant_reservation_time').val(fake_val);
            //   //   wrap.find('#visual_restaurant_reservation_time_fake').val(fake_val);
            //   // }
            // } else {
            //   wrap.find('#visual_restaurant_reservation_time').val(wrap.find('#visual_restaurant_reservation_time').val());
            // }
          } 
        } else { // click on date on mobile, no element
          // console.log(array_exclude_times_settings);
          initSelect('default',array_exclude_times_settings, dt, wrap);
        }
        wrap.find('#visual_restaurant_reservation_time').val('');
        wrap.find('#visual_restaurant_reservation_time_to').val('');
        wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').show();
        wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').show();
      },
      beforeShowDay: function(dt) {
        if(element.length > 0){
          if(element.attr('data-date').length > 0){

            array_exclude_dates = decodeURIComponent(element.attr('data-date'));
            array_exclude_dates = JSON.parse(array_exclude_dates);
            // console.log(array_exclude_dates);
            array_exclude_dates_settings = decodeURIComponent(datapicker.attr('data-date'));
            array_exclude_dates_settings = JSON.parse(array_exclude_dates_settings);
            // console.log(array_exclude_dates_settings);
            array_exclude_dates = array_exclude_dates.concat(array_exclude_dates_settings);
          }
        } else { // on load mobile
          array_exclude_dates_settings = decodeURIComponent(datapicker.attr('data-date'));
          array_exclude_dates_settings = JSON.parse(array_exclude_dates_settings);
          // console.log(array_exclude_dates_settings);
          array_exclude_dates = array_exclude_dates.concat(array_exclude_dates_settings);
        }

        var dateString = $.datepicker.formatDate('mm/dd/yy', dt);
        // console.log(array_exclude_dates.length);
        return [array_exclude_dates.indexOf(dateString) == -1];
      },
    });

    $('#visual_restaurant_reservation_date').val('');
  }

 

 /* function reInitFormSecond(element, datapicker, wrap){

  }*/

  // add class 'disabled' for element if on this time and date table already booked(second type layout)
  function reInitElementsSecond(wrap, datapicker){
    var array_exclude_dates = [];
    var array_exclude_times = [];
    var array_exclude_dates_settings = [];
    var array_exclude_times_settings = [];
    
    array_exclude_times_settings = decodeURIComponent(datapicker.attr('data-time'));
    array_exclude_times_settings = JSON.parse(array_exclude_times_settings);

    var dt = $('#visual_restaurant_reservation_date').val();
    var val = $('#visual_restaurant_reservation_time').val();

    wrap.find('.vrr-draggable .vrr-element').removeClass('disabled');
    wrap.find('.vrr-draggable .vrr-element').each(function(el){
      var element = $(this);
      if(element.attr('data-time').length > 0){

        array_exclude_times = decodeURIComponent(element.attr('data-time'));
        array_exclude_times = JSON.parse(array_exclude_times);
          
        array_exclude_times = array_exclude_times.concat(array_exclude_times_settings);
        // console.log(array_exclude_times);
        for (var i = array_exclude_times.length - 1; i >= 0; i--) {
          if(array_exclude_times[i]['date'] == dt){
            
            if(array_exclude_times[i]['time'] == val){
              // console.log(array_exclude_times[i]);
              element.addClass('disabled');
            }
          }
        }
      } 
    });
    wrap.find('.vrr-draggable').removeClass('vrr-loading');
  }

  // pick time(second type layout)
  $('#visual_restaurant_reservation_time_fake').on('change',function(){
    var wrap = $(this).closest('.vrr-tables-wrap');
    $('#visual_restaurant_reservation_time').val($(this).val());
    reInitElementsSecond(wrap, datapicker);
  });


  $('.vrr-draggable .vrr-element').on('click',function(){
    if(!$(this).hasClass('disabled')){
      var wrap = $(this).closest('.vrr-tables-wrap');
      if(wrap.find('.vrr-max-people-amount').length > 0){
        var people = $(this).attr('data-max-seats');
        if(people.length > 0){
          wrap.find('.vrr-max-people-amount').text(people);
          wrap.find('.vrr-max-people-amount').show();
          wrap.find('.vrr-max-people-amount-text').show();
        } else {
          wrap.find('.vrr-max-people-amount').hide();
          wrap.find('.vrr-max-people-amount-text').hide();
        }
      }
      wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_id').val($(this).attr('data-id'));
      wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_unique').val($(this).attr('data-unique'));
      wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_seats').val($(this).attr('data-seats'));
      var data_text = wrap.find('.vrr-form-booking').find('.vrr-form-booking-title').attr('data-text');
      wrap.find('.vrr-form-booking').find('.vrr-form-booking-title').text(data_text+''+$(this).attr('data-id'));

      wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
      if(!wrap.hasClass('vrr-tables-wrap-second')){// quick fix
        wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
      } else {
        wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').show();
      }

      wrap.find('.vrr-form-booking .vrr-error').fadeOut(100);
      wrap.find('.vrr-send-respons-error').fadeOut(100);
      wrap.find('.vrr-send-booking').removeClass('vrr-sended');
      wrap.find('.vrr-send-booking').removeClass('vrr-sended-error');
      // console.log($(this));

      if(wrap.hasClass('vrr-tables-wrap-default')){
        reInitForm($(this), datapicker, wrap);
      } else if(wrap.hasClass('vrr-tables-wrap-second')){
        // reInitFormSecond($(this), datapicker, wrap);
      }
      
      wrap.find('.vrr-form-back').fadeIn(100,function(){
        wrap.find('.vrr-form-booking').fadeIn(100,function(){});
      });
    }
  });

  $('.vrr-close, .vrr-form-back').on('click',function(){
    var wrap = $(this).closest('.vrr-tables-wrap');
    wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_id').val('');
    wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_seats').val('');
    wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_unique').val('');
    wrap.find('.vrr-form-booking').fadeOut(100);
    wrap.find('.vrr-form-thx').fadeOut(100);
    wrap.find('.vrr-form-back').fadeOut(100);
    wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
    wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
  });

  $('.vrr-input, .vrr-select, .vrr-checkbox').on('focus click change', function(){
    $(this).removeClass('vrr-error-input');
    $(this).closest('.vrr-input-wrap').find('.vrr-error').fadeOut(100);  
  });

  function hideOption(wrap, time, date, element = null){
    var array_exclude_dates = [];
    var array_exclude_times = [];
    var table_before_booked_time = 0;
    table_before_booked_time = decodeURIComponent(datapicker.attr('data-before-time'));
    table_before_booked_time = parseInt(table_before_booked_time);
    var table_after_booked_time = 0;
    table_after_booked_time = decodeURIComponent(datapicker.attr('data-after-time'));
    table_after_booked_time = parseInt(table_after_booked_time);
    // console.log(table_before_booked_time);
    // console.log(table_after_booked_time);
    // console.log(select_html); 
    if(element.length > 0){
      if(element.attr('data-time').length > 0){
        array_exclude_times = decodeURIComponent(element.attr('data-time'));
        array_exclude_times = JSON.parse(array_exclude_times);
        var el = {};
        el.date = date.val();
        el.time = time.val();
        array_exclude_times.push(el);

        array_exclude_times = JSON.stringify(array_exclude_times);
        array_exclude_times = encodeURIComponent(array_exclude_times);
        
        element.attr('data-time', array_exclude_times);
      } 
    }
  }

  $('.vrr-tables-wrap .vrr-form-booking .vrr-send-booking').on('click',function(){
    var error = 0;
    var self = $(this);
    var name = $('#visual_restaurant_reservation_name');
    var phone = $('#visual_restaurant_reservation_phone');
    var email = $('#visual_restaurant_reservation_email');
    var date = $('#visual_restaurant_reservation_date');
    var time = $('#visual_restaurant_reservation_time');
    var time_to = $('#visual_restaurant_reservation_time_to');
    var people = $('#visual_restaurant_reservation_people');
    var confirm = $('#visual_restaurant_reservation_form_confirm');
    var wrap = $(this).closest('.vrr-tables-wrap');
    var element = wrap.find('.vrr-element[data-unique="'+$('#visual_restaurant_reservation_unique').val()+'"]');

    self.removeClass('vrr-sended');
    self.removeClass('vrr-sended-error');
    wrap.find('.vrr-error').fadeOut(100);
    wrap.find('.vrr-send-respons-error').fadeOut(100);

    if(name.length > 0){
      if(name.val() == ""){
        error = 1;
        name.addClass('vrr-error-input');
        name.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      }
    }  
    if(phone.length > 0){
      if(phone.val() == ""){
        error = 1;
        phone.addClass('vrr-error-input');
        phone.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      } else if(checkTelephone(phone.val()) != true){
        error = 1;
        phone.addClass('vrr-error-input');
        phone.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-phone').fadeIn(100);
      }
    }

    if(email.length > 0){
      if(email.val() == ""){
        error = 1;
        email.addClass('vrr-error-input');
        email.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      } else if(checkEmail(email.val()) != true){
        error = 1;
        email.addClass('vrr-error-input');
        email.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-email').fadeIn(100);
      }
    }

    if(date.length > 0){
      if(date.val() == ""){
        error = 1;
        date.addClass('vrr-error-input');
        date.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
        datapicker.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      } 
    }

    if(confirm.length > 0){
      if(confirm.attr('checked') != "checked"){
        error = 1;
        confirm.addClass('vrr-error-input');
        confirm.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
        confirm.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      } 
    }

    if(time.length > 0){
      if(time.val() == "" || time.val() == null){
        error = 1;
        time.addClass('vrr-error-input');
        time.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      }
    }

    if(time_to.length > 0 && time.length > 0){
      if(time_to.val() == "" || time_to.val() == null || time.val() == "" || time.val() == null){

      } else {
        var timeReg = /(\d+)\:(\d+)/;

        var time_parts = time.val().match(timeReg);
        var hours = parseInt(time_parts[1], 10); 
        var minutes = parseInt(time_parts[2], 10);

        var time_to_parts = time_to.val().match(timeReg);
        var hours_to = parseInt(time_to_parts[1], 10); 
        var minutes_to = parseInt(time_to_parts[2], 10);

        if(hours_to > hours){
          // 0k
        } else if(hours_to < hours){
          error = 1;
          time_to.addClass('vrr-error-input');
          time_to.closest('.vrr-input-wrap').find('.vrr-error').fadeIn(100);
          if(wrap.hasClass('vrr-tables-wrap-second')){
            wrap.find('.vrr-send-respons-error').text(time_to.closest('.vrr-input-wrap').find('.vrr-error').attr('data-error-text'));
            wrap.find('.vrr-send-respons-error').fadeIn(100);
          }
        } else if(hours_to == hours){
          if(minutes_to > minutes){
            //Ok
          } else {
            error = 1;
            time_to.addClass('vrr-error-input');
            time_to.closest('.vrr-input-wrap').find('.vrr-error').fadeIn(100);
            if(wrap.hasClass('vrr-tables-wrap-second')){
              wrap.find('.vrr-send-respons-error').text(time_to.closest('.vrr-input-wrap').find('.vrr-error').attr('data-error-text'));
              wrap.find('.vrr-send-respons-error').fadeIn(100);
            }
          }
        }
      }
    }

    if(people.length > 0){
      if(people.val() == "" || people.val() == null){
        error = 1;
        people.addClass('vrr-error-input');
        people.closest('.vrr-input-wrap').find('.vrr-error.vrr-error-empty').fadeIn(100);
      }
    }

    if(error == 0){
      self.addClass('vrr-sended');
      var postData = $(this).closest('form').serialize();
      postData += '&' + $.param({
          action: 'send_book',
      });

      // if second add time to if need
      if(wrap.hasClass('vrr-tables-wrap-second')){
        if(time_to.length > 0 && time.length > 0){
          if(time_to.val() == "" || time_to.val() == null || time.val() == "" || time.val() == null){

          } else {
            postData += '&' + $.param({
              visual_restaurant_reservation_time_to: time_to.val(),
            });
          }
        }
      }

      if ($(window).width() <= 767) {
        postData += '&' + $.param({
          visual_restaurant_reservation_mob: '1',
        });
      }
      var ajaxurl = myajax.url;
      // console.log(ajaxurl);
      // console.log(postData);

      $.ajax({
        type:"POST",
        url: ajaxurl,
        dataType: 'html',
        // contentType: 'text/plain',
        data: postData,
        success:function(data, status, xhr){
          // console.log(data);
          var json = JSON.parse(data);
          // console.log(json);
          if(json.echo == 0){
            console.log(json.error);
            self.addClass('vrr-sended-error');
            wrap.find('.vrr-send-respons-error').text(json.error);
            wrap.find('.vrr-send-respons-error').fadeIn(100);
          } else { // all ok
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_id').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_unique').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_seats').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_name').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_phone').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_date').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').val('');
            wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').val('');

            if(element.length > 0){ // not mob
              hideOption(wrap, time, date, element);

              // datapicker.datepicker( "setDate", new Date() );
              datapicker.datepicker( "setDate", null );
              wrap.find('.vrr-form-booking').fadeOut(100, function(){
                wrap.find('.vrr-form-thx').fadeIn(100);
                wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
                wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
              });
            } else { // mob
              hideOption(wrap, time, date, '');

              // datapicker.datepicker( "setDate", new Date() );
              datapicker.datepicker( "setDate", null );
              wrap.find('.vrr-form-back').fadeIn(100);
              wrap.find('.vrr-form-thx').fadeIn(100);
              wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time').closest('.vrr-input-wrap').hide();
              wrap.find('.vrr-form-booking').find('#visual_restaurant_reservation_time_to').closest('.vrr-input-wrap').hide();
            }

            
            // console.log(json.echo);
          }
          self.removeClass('vrr-sended');
        },
        error: function(error){
          console.log(error);
          self.removeClass('vrr-sended');
          self.addClass('vrr-sended-error');
          wrap.find('.vrr-send-respons-error').text("Error");
          wrap.find('.vrr-send-respons-error').fadeIn(100);
        } 
      });
    }

  });

  
	
  

})(jQuery);