(function ($) {
  'use strict';
  // @TODO: though we are using behaviors, this is not likely to work with Ajax.
  Drupal.behaviors.ejikznayka = {
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
})(jQuery);
