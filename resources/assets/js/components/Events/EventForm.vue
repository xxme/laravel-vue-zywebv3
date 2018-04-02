<template>
  <div>
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">add event</h3>
      </div>
      <div class="box-body">
        <div class="form-group"> 
          <label>{{ $t('event.workType') }}:</label> 
 
          <select2 :options="options"></select2> 
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
              <input type="text" name="stime" class="form-control datepickertime" disabled v-model="eventtime" /> 
            </div> 
          </div> 
        </div> 
        <!-- Date -->
        <div class="form-group">
          <label>Date:</label>

          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control pull-right" id="datepicker">
          </div>
          <!-- /.input group -->
        </div>
        <!-- /.form group -->

        <!-- Date range -->
        <div class="form-group">
          <label>Date range:</label>

          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservation">
          </div>
          <!-- /.input group -->
        </div>
        <!-- /.form group -->

        <!-- Date and time range -->
        <div class="form-group">
          <label>Date and time range:</label>

          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-clock-o"></i>
            </div>
            <input type="text" class="form-control pull-right" id="reservationtime">
          </div>
          <!-- /.input group -->
        </div>
        <!-- /.form group -->

        <!-- Date and time range -->
        <div class="form-group">
          <label>Date range button:</label>

          <div class="input-group">
            <button type="button" class="btn btn-default pull-right" id="daterange-btn">
              <span>
                <i class="fa fa-calendar"></i> Date range picker
              </span>
              <i class="fa fa-caret-down"></i>
            </button>
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
  },
  components: {
    Select2
  },
  data() {
    return {
      options: [
        { id: 1, text: 'Hello' },
        { id: 2, text: 'World' }
      ],
      value: [],
      eventdate: "",
      eventtime: ""
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
      // $('.datepickertime').on('change', (e) => {
      //   self.eventtime = moment($(e.target).val()).format('h:mm')
      // })
    }
  },
  created() {
    
  }
}
</script>
