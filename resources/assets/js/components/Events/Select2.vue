<template> 
    <select :multiple="multiple" class="form-control"> 
    </select> 
</template> 
 
<script> 
import select2 from 'select2' 
 
export default { 
  props: ['options', 'value'], 
  template: '#select2-template', 
  ready: function () { 
    var self = this 
    if (self.multiple === true) { 
      $(self.$el).attr('multiple', true) 
    } 
  }, 
  mounted() { 
      const select = $(this.$el); 
      // $(this.$el) 
      select 
      // init select2 
      .select2({data: this.options}) 
      .val(this.value) 
      .on('change', (event) => { 
          const selecions = select.select2('data') 
          .map((element) => parseInt(element.id, 10)); 
          // this.$emit('input', parseInt(event.target.value, 10)) 
          this.$emit('input', selecions); 
          this.selected = selecions; 
          console.log(this.selected); 
      }) 
      .trigger('change'); 
  }, 
  data() { 
    return { 
      selected: [], 
      multiple: true 
    } 
  }, 
  destroyed: function () { 
    $(this.$el).off().select2('destroy') 
  } 
} 
</script>