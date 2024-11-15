/*=========================================================================================
	File Name: form-number-input.js
	Description: Number Input
	----------------------------------------------------------------------------------------
	Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
	Author: PIXINVENT
	Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

(function (window, document, $) {
  'use strict';

  // Default Spin
  $('.touchspin').TouchSpin({
    buttondown_class: 'btn btn-primary',
    buttonup_class: 'btn btn-primary',
    buttondown_txt: feather.icons['minus'].toSvg(),
    buttonup_txt: feather.icons['plus'].toSvg()
  });

  // Icon Change
  $('.touchspin-icon').TouchSpin({
    buttondown_txt: feather.icons['chevron-down'].toSvg(),
    buttonup_txt: feather.icons['chevron-up'].toSvg()
  });


  // Step
  $('.touchspin-step').TouchSpin({
    step: ,
    buttondown_txt: feather.icons['minus'].toSvg(),
    buttonup_txt: feather.icons['plus'].toSvg()
  });

  // Color Options
  $('.touchspin-color').each(function (index) {
    var down = 'btn btn-primary',
      up = 'btn btn-primary',
      $this = $(this);
    if ($this.data('bts-button-down-class')) {
      down = $this.data('bts-button-down-class');
    }
    if ($this.data('bts-button-up-class')) {
      up = $this.data('bts-button-up-class');
    }
    $this.TouchSpin({
      mousewheel: false,
      buttondown_class: down,
      buttonup_class: up,
      buttondown_txt: feather.icons['minus'].toSvg(),
      buttonup_txt: feather.icons['plus'].toSvg()
    });
  });
})(window, document, jQuery);
