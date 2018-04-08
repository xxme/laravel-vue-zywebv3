<template>
  <div>
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">{{ $t('event.addEvent') }}</h3>
      </div>
      <div class="box-body">
        <div class="form-group"> 
          <label>{{ $t('event.workType') }}:</label> 
          <select2 :options="options1"></select2> 
          <!-- /.input group --> 
        </div> 
        <div class="form-group"> 
          <label>{{ $t('event.typeofcareful') }}:</label> 
          <select2 :options="options2"></select2> 
          <!-- /.input group --> 
        </div> 
        <div class="form-group"> 
          <label>{{ $t('event.typeoftotal') }}:</label> 
          <select2 :options="options3"></select2> 
          <!-- /.input group --> 
        </div>
        <div class="form-group"> 
          <label>{{ $t('event.aboutgoods') }}:</label> 
          <select2 :options="options4"></select2> 
          <!-- /.input group --> 
        </div> 
        <div class="form-group"> 
          <label>{{ $t('event.typeoftruck') }}:</label> 
          <select2 :options="options9"></select2> 
          <!-- /.input group --> 
        </div> 
        <div class="form-group"> 
          <!-- <div class="col-sm-7"> --> 
          <label>{{ $t('event.scheduledDatetime') }}:</label> 
          <div class="form-inline"> 
            <div class="input-group date col-sm-4"> 
              <div class="input-group-addon"> 
                <i class="fa fa-calendar"></i> 
              </div> 
              <input type="text" name="sdate" class="form-control datepicker" v-model="eventdate" /> 
            </div> 
            <div class="input-group date col-sm-4"> 
              <div class="input-group-addon"> 
                <input type="checkbox" id="timeckbox" name="timeckbox" /> 
              </div> 
              <input type="text" name="stime" class="form-control datepickertime" disabled v-model="eventfromtime" /> 
              <div class="input-group-addon"> 
                〜
              </div> 
              <input type="text" name="ttime" class="form-control datepickertime" disabled v-model="eventtotime" /> 
            </div> 
          </div> 
        </div> 
        <!-- /.form group -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <router-link to="/admin"><button type="submit" class="btn btn-default">{{ $t('global.Cancel') }}</button></router-link> 
        <button type="submit" class="btn btn-primary pull-right">{{ $t('global.Submit') }}</button> 
      </div>
    </div>
  </div>
</template>
<script>
var $ = window.jQuery = require('jquery')
import Vue from 'vue'
import Select2 from './Select2'
import moment from 'moment'
import datetimepicker from 'jquery-datetimepicker'
 
export default {
  mounted() {
    this.setDatePicker()
    $('#timeckbox').on('change', function(e){
      if($(this).is(':checked')){
        $('.datepickertime').attr('disabled', false)
      } else {
        $('.datepickertime').attr('disabled', true);
        $('input[name="stime"]').val("");
      }
    })
    this.get_types()
  },
  components: {
    Select2
  },
  data() {
    return {
      options: [
        {id: 1, text: "t1"},
        {id: 2, text: "t2"}
      ],
      options1: [],
      options2: [],
      options3: [],
      options4: [],
      options9: [],
      value: [],
      eventdate: "",
      eventfromtime: "",
      eventtotime: ""
    }
  },
  methods: {
    setDatePicker() {
      const self = this
 
      $.datetimepicker.setLocale('zh')
      $('.datepicker').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('.datepickertime').datetimepicker({datepicker:false, format:'H:i'})
      // $('.datepicker').on('change', (e) => {
      //   self.eventdate = moment($(e.target).val()).format('YYYY-MM-DD')
      // })
    },
    get_types() {
        this.$http({
            url: '/api/get_types/',
            method: 'GET'
        }).then(res =>  {
            for (let index = 0; index < res.data.length; index++) {
              var group_id = res.data[index].group_id;
              var option = {};
              option.id = res.data[index].id;
              option.text = res.data[index].name;
              switch (group_id) {
                case 1:
                  this.options1.push(option);
                  break;
                case 2:
                  this.options2.push(option);
                  break;
                case 3:
                  this.options3.push(option);
                  break;
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                  this.options4.push(option);
                  break;
                case 9:
                  this.options9.push(option);
                  break;
                default:
                  break;
              }
            };
        })
    }
  },
  created() {

  }
}
</script>
