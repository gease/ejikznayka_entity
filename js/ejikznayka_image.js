(function ($) {
  'use strict';
  Drupal.behaviors.ejikznaykaImage = {
    attach: function (context, settings) {
      $('button.ejikznayka_page', context).click(function () {
        $(this).next('.overlay').css('display', 'flex');
        $(this).next('.overlay').children('div:first-of-type').show();
      });
      $('.overlay', context).click(function () {
        $(this).children('div:visible').hide().next().not('.field--name-field-paragraph-image-based-soun').show();
        if ($(this).children('div:visible').length === 0) {
          $(this).children('.field--name-field-paragraph-image-based-soun').find('audio').get(0).play();
          $(this).hide();

        }
      });
    }
  };
})(jQuery);
