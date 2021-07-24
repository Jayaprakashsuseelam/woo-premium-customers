<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'assets/css/bootstrap.min.css', dirname(__FILE__) ) ) ?>">
<script type="text/javascript" src="<?php echo esc_url( plugins_url( 'assets/js/bootstrap.min.js', dirname(__FILE__) ) ) ?>"></script>
<script type="text/javascript" src="<?php echo esc_url( plugins_url( 'assets/js/jquery-3.5.1.min.js', dirname(__FILE__) ) ) ?>"></script>
<script type="text/javascript" src="<?php echo esc_url( plugins_url( 'assets/js/script.js', dirname(__FILE__) ) ) ?>"></script>
  <style>
  .invalid-feedback-time-frame, .invalid-feedback-order-frequency, .invalid-feedback-amount{color:red;}
  </style>
  <div class="container">
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="<?php echo esc_url( plugins_url( 'woo-premium-customers/assets/images/WooCommerce-logo.png', '' ) ) ?>" alt="" />
      <h2>Premium Customer Settings</h2>
      <p class="lead">You can create/update Premium Customer Settings parameter's here!</p>
    </div>
    <div class="row">
      <div class="col-md-8 order-md-1">
        <form id="myForm">
          <input type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" class="admin-ajax" />
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="firstName">Time Frame</label>
              <select class="form-control" id="time_frame">
                <option value="-1" <?php if($getVipSettings->time_frame == ''){ echo 'selected';}?>>Choose...</option>
                <option value="60" <?php if($getVipSettings->time_frame == 60){ echo 'selected';}?> >60 Days</option>
                <option value="90" <?php if($getVipSettings->time_frame == 90){ echo 'selected';}?> >90 Days</option>
                <option value="120" <?php if($getVipSettings->time_frame == 120){ echo 'selected';}?>>120 Days</option>
                <option value="365" <?php if($getVipSettings->time_frame == 365){ echo 'selected';}?>>365 Days</option>
              </select>
              <div class="invalid-feedback-time-frame" style="display:none;">
              Please select one option!
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="address">Order Frequency</label>
            <input type="text" class="form-control order_frequency" name="order_frequency" placeholder="Ex:100" value="<?php echo $getVipSettings->order_frequency;?>" />
            <div class="invalid-feedback-order-frequency" style="display:none;">
              This field is required!
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="address2">Amount</label>
            <input type="text" class="form-control amount" id="amount" placeholder="Ex:100" value="<?php echo $getVipSettings->amount;?>" />
            <div class="invalid-feedback-amount" style="display:none;">
              This field is required!
            </div>
          </div>
          <hr class="mb-4">
          <div class="col-md-5 mb-3">
          <button type="submit" class="btn btn-success btn-lg btn-block" name="submit" value="submit">Save</button><br>
          </div>
        </form>
        <br>
        <p><i>* Automattic Inc. owns and oversees the trademarks for Woo™, WooCommerce®, and WooThemes® names, logos, and related icons (aka the “Woo Marks”).</i>
        <span style="display:none;" class="alert alert-success settings-save-succ">Settings saved successfully.</span>
      </div>
</div>
