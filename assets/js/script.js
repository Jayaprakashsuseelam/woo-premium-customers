$( document ).ready(function() {
  $(".btn-block").click(function(e) {
      var ajax_url = $('.admin-ajax').val();
      e.preventDefault();
      var time_frame = $('#time_frame').val();
      var order_frequency = $('.order_frequency').val();
      var amount = $('.amount').val();
      if(time_frame == '-1'){ $('.invalid-feedback-time-frame').show(); return false; }else{ $('.invalid-feedback-time-frame').hide(); }
      if(order_frequency === ''){ $('.invalid-feedback-order-frequency').show(); return false; }else{ $('.invalid-feedback-order-frequency').hide(); }
      if(amount === ''){ $('.invalid-feedback-amount').show(); return false; }else{ $('.invalid-feedback-amount').hide(); }

      // Ajax function for saving settings
      $.ajax({
          url: ajax_url,
          data: {
              'action': 'save_settings',
              'time_frame': time_frame,
              'order_frequency' : order_frequency,
              'amount' : amount
          },
          type: 'POST',
          success:function(data) {
            if(data != 0){
                $('.settings-save-succ').show();
                location.reload();
            }else if(data == 0){
                location.reload();
            }
          },
          error: function(errorThrown){
              console.log(errorThrown);
          }
      });

  });
});
