(function ($) {
  'use strict';
  // @TODO: though we are using behaviors, this is not likely to work with Ajax.
  Drupal.behaviors.ejikznaykaPlay = {
    attach: function (context, settings) {
      let item = {};
      for (let field in settings.ejikznayka.fields) {
        if (settings.ejikznayka.fields.hasOwnProperty(field)) {
          const o = settings.ejikznayka.fields[field];
          for (let i in o) {
            if (o.hasOwnProperty(i)) {
              item.field = field;
              item.delta = i;
              $('.' + field + ' .field__item:eq(' + i + ')  button.ejikznayka_page', context).ejikznayka(o[i].sequence, o[i].positions, o[i].options, item);
            }
          }
        }
      }
    }
  };
  Drupal.behaviors.ejikznaykaAdmin = {
    attach: function (context, settings) {
      $('input[type="button"][name="generate"]', context).click(function () {
        const options = {};
        let sequence = [];
        const target = $(this).data('ejikznayka-target');
        options.max = $(this).siblings('.' + target + '-max').children('input').val();
        options.min = $(this).siblings('.' + target + '-min').children('input').val();
        options.count = $(this).siblings('.' + target + '-count').children('input').val();
        options.minus = $(this).siblings('.' + target + '-minus').children('input').prop('checked');
        sequence = $.ejikznayka.generate(options);
        $(this).siblings('.' + target + '-sequence').children('input').val(sequence.join());
      });
    }
  };
})(jQuery);
