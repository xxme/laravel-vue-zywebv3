<template> 
    <select :multiple="multiple" class="form-control select2x"> 
    </select> 
</template> 
 
<script> 
import select2 from 'select2' 
 
export default { 
  props: ['options', 'value', 'placeholder', 'selected'], 
  template: '#select2-template', 
  ready: function () { 
    var self = this 
    if (self.multiple === true) { 
      $(self.$el).attr('multiple', true) 
    } 
  }, 
  mounted() { 
      $(this.$el).select2({allowClear: true, placeholder: this.placeholder});
  }, 
  data() { 
    return { 
      multiple: true 
    } 
  }, 
  watch: {
    selected: function (value) {
      if(value && (value > 0 || value.length > 0)) {
        $(this.$el).select2({allowClear: true, data: this.options, placeholder: this.placeholder}).val(value).trigger("change");
      } else {
        $(this.$el).select2({allowClear: true, data: this.options, placeholder: this.placeholder}).val($(this).attr('placeholder')).trigger("change");
      }
    }
  },
  destroyed: function () { 
    $(this.$el).off().select2('destroy') 
  } 
} 
</script>