<template> 
    <select :multiple="multiple" class="form-control select2x"> 
    </select> 
</template> 
 
<script> 
import select2 from 'select2' 
 
export default { 
  props: ['options', 'value', 'placeholder', 'selected', 'change'], 
  template: '#select2-template', 
  ready: function () { 
    var self = this 
    if (self.multiple === true) { 
      $(self.$el).attr('multiple', true) 
    } 
  }, 
  mounted() { 
    $(this.$el).select2({allowClear: true, placeholder: this.placeholder});
    if(this.change) {
      var self = this
      $(this.$el).on('select2:select', function (e) {
        var data = e.params.data;
        self.changeVal(data.id);
      })
      // $(this.$el)
      //   .on('change', function () {
      //     console.log("this.value",this.value)
      //     self.changeVal(this.value);
      //   })
    }
  }, 
  data() { 
    return { 
      multiple: true 
    } 
  }, 
  watch: {
    selected: function (value) {
      if(this.options) {
        $(this.$el).select2({allowClear: true, data: this.options, placeholder: this.placeholder}).val($(this).attr('placeholder')).trigger("change");
      }
      if(value && (value > 0 || value.length > 0)) {
        $(this.$el).select2({allowClear: true, data: this.options, placeholder: this.placeholder}).val(value).trigger("change");
      }
      if(this.change) {
        var self = this
        self.changeVal(value);
      }
    },
    options: function (options) {
      $(this.$el).select2({allowClear: true, data: options, placeholder: this.placeholder}).val($(this).attr('placeholder')).trigger("change");
      if(this.value && (this.value > 0 || this.value.length > 0)) {
        $(this.$el).select2({allowClear: true, data: options, placeholder: this.placeholder}).val(this.value).trigger("change");
      }
    },
    value: function (value) {
      console.log("value", value);
      if(this.change) {
        var self = this;
        $(this.$el).select2({allowClear: true, data: options, placeholder: this.placeholder}).val(value).trigger("change");
        self.$emit('change', value);
      }
    }
  },
  methods: {
    changeVal(value) {
      var self = this;
      self.$emit('change', value);
    }
  },
  destroyed: function () { 
    $(this.$el).off().select2('destroy') 
  } 
} 
</script>